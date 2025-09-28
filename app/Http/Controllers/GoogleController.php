<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class GoogleController extends Controller
{
    // Redirige a Google para iniciar el flujo OAuth
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Callback: solo permite crear/autenticar usuarios con rol paciente
    public function callback(Request $request)
    {
        try {
            // Obtener datos desde Google
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            Log::error('OAuth callback failed (Socialite): '.$e->getMessage(), ['exception' => $e]);
            return redirect()->route('login')->with('oauth_error', 'Error al iniciar sesión con Google.');
        }

        $provider = 'google';
        $providerId = $googleUser->getId();
        $email = $googleUser->getEmail();
        $fullName = $googleUser->getName() ?? $email;
        $avatar = $googleUser->getAvatar();

        if (! $email) {
            Log::warning('OAuth callback: proveedor no devolvió email', ['provider_user' => $googleUser]);
            return redirect()->route('login')->with('oauth_error', 'Google no proporcionó un correo electrónico público.');
        }

        DB::beginTransaction();
        try {
            // Buscar por provider+provider_id primero, luego por email
            $user = User::where('provider', $provider)
                        ->where('provider_id', $providerId)
                        ->first();

            if (! $user) {
                $user = User::where('email', $email)->first();
            }

            if (! $user) {
                // Crear nuevo usuario mínimo
                $names = explode(' ', $fullName);
                $firstName = $names[0] ?? explode('@', $email)[0];
                $lastName = count($names) > 1 ? implode(' ', array_slice($names, 1)) : '';

                $user = User::create([
                    'first_name'   => $firstName,
                    'last_name'    => $lastName,
                    'email'        => $email,
                    'password'     => bcrypt(Str::random(24)), // password aleatorio, no usado
                    'provider'     => $provider,
                    'provider_id'  => $providerId,
                    'avatar'       => $avatar,
                    'status'       => 'active',
                ]);
            } else {
                // Actualizar datos no manuales si faltan
                $changed = false;

                if (! $user->provider) {
                    $user->provider = $provider;
                    $changed = true;
                }

                if (! $user->provider_id) {
                    $user->provider_id = $providerId;
                    $changed = true;
                }

                if (! $user->avatar && $avatar) {
                    $user->avatar = $avatar;
                    $changed = true;
                }

                if ($changed) {
                    $user->save();
                }
            }

            // Marcar email como verificado para cuentas OAuth (Google ya validó el correo)
            if (method_exists($user, 'markEmailAsVerified')) {
                $user->markEmailAsVerified();
            } else {
                $user->email_verified_at = now();
                $user->save();
            }

            // Asegurarnos de que profile_completed esté false/null para forzar setup
            if (isset($user->profile_completed) && $user->profile_completed) {
                $user->profile_completed = false;
                $user->save();
            }

            // Asignación de rol segura: comprobar existencia antes de assignRole
            $roleName = 'paciente';
            $roleExists = Role::where('name', $roleName)->where('guard_name', 'web')->exists();

            if ($roleExists) {
                // Asignar solo si no lo tiene
                if (method_exists($user, 'getRoleNames') && $user->getRoleNames()->isEmpty()) {
                    $user->assignRole($roleName);
                }
            } else {
                Log::error("Rol esperado no existe: {$roleName}", ['user_email' => $email]);
                DB::rollBack();
                return redirect()->route('login')->with('oauth_error', 'Configuración de roles incompleta. Contacta soporte.');
            }

            // Crear perfil mínimo si la relación existe y no tiene profile
            if (method_exists($user, 'profile') && ! $user->profile) {
                $displayName = trim($user->first_name . ' ' . $user->last_name);
                $user->profile()->create([
                    'display_name' => $displayName,
                ]);
            }

            DB::commit();

            // Login del usuario (sesión persistente)
            Auth::login($user, true);

            // Redirigir siempre al setup de perfil para completar datos (paciente)
            return redirect()->route('profile.setup.show');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('OAuth callback processing failed: '.$e->getMessage(), ['exception' => $e, 'provider_user' => $googleUser ?? null]);
            return redirect()->route('login')->with('oauth_error', 'Error interno al procesar el inicio de sesión.');
        }
    }
}