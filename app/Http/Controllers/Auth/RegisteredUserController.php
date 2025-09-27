<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password'   => ['required', 'confirmed', Rules\Password::defaults()],
            'role'       => ['required', Rule::in(['paciente', 'doctor'])],
            'carnet_minsa' => [
                Rule::requiredIf(fn () => $request->input('role') === 'doctor'),
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        // Determinar estado inicial según el rol
        $role = $request->input('role');
        $status = $role === 'doctor' ? 'pending' : 'active';

        // Crear usuario
        $user = User::create([
            'first_name'   => $request->input('first_name'),
            'last_name'    => $request->input('last_name'),
            'email'        => $request->input('email'),
            'carnet_minsa' => $request->input('carnet_minsa'),
            'status'       => $status,
            'password'     => Hash::make($request->input('password')),
        ]);

        // Asignar rol con Spatie si está disponible
        if (method_exists($user, 'assignRole')) {
            $user->assignRole($role);
        }

        // Crear perfil mínimo asociado (si existe la relación)
        if (method_exists($user, 'profile')) {
            $displayName = trim($user->first_name . ' ' . $user->last_name);
            $user->profile()->create([
                'display_name' => $displayName,
            ]);
        }

        // Disparar evento Registered (listeners habituales, incluido envío de verificación si aplica)
        event(new Registered($user));

        // Comportamiento post-registro para pacientes (acceso inmediato)
        if ($role === 'paciente') {
            Auth::login($user);

            // Enviar verificación de email en segundo plano si el usuario implementa MustVerifyEmail
            if ($user instanceof MustVerifyEmail) {
                $user->sendEmailVerificationNotification();
            }

            // Mensaje flash de bienvenida y orientación
            session()->flash('status', 'Bienvenido. Tu cuenta está activa. Te hemos enviado un correo para verificar tu dirección, esto no impide que uses la plataforma.');

            return redirect()->route('dashboard');
        }

        // Para médicos: no iniciar sesión automático; informar que su cuenta está pendiente
        return redirect()->route('login')->with('status', 'Registro recibido. Tu solicitud como médico está pendiente de aprobación.');
    }
}