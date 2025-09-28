<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Schema;

class ProfileController extends Controller
{
    /**
     * Mostrar el formulario para editar perfil.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Actualizar solo los campos permitidos del perfil (contacto rápido).
     * Nota: eliminamos manejo de emergency_contact_*; esos datos están en medical_histories.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Asegurar que existe profile asociado
        $profile = $user->profile ?? $user->profile()->create([]);

        // Actualizar solo los campos permitidos por ProfileUpdateRequest
        // Aquí mantenemos solo 'phone' como ejemplo si quieres conservarlo,
        // pero según tu indicación lo eliminamos; dejamos este bloque vacío
        // salvo para otros campos permitidos en ProfileUpdateRequest.
        $allowed = $request->validated();

        // Filtrar cualquier campo no deseado por seguridad (defensa en profundidad)
        $permitted = array_intersect_key($allowed, array_flip(['phone'])); // si eliminas phone, pon []

        foreach ($permitted as $key => $value) {
            $profile->{$key} = $value;
        }

        $profile->save();

        return Redirect::route('profile.edit')->with('status', 'Contacto actualizado');
    }

    /**
     * Eliminar la cuenta del usuario.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Controlador responsable del acceso al dashboard.
     * Si el usuario no tiene el perfil completado lo redirige a profile.setup
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();

        // Si no hay usuario autenticado, dejar que el middleware auth lo maneje.
        if (! $user) {
            return redirect()->route('login');
        }

        // Si el email no está verificado dejamos que el middleware 'verified' lo redirija.
        if (empty($user->email_verified_at)) {
            return redirect()->route('verification.notice');
        }

        // Si la columna profile_completed existe y el usuario no la tiene en true, redirige al setup
        if (Schema::hasColumn('users', 'profile_completed') && empty($user->profile_completed)) {
            return redirect()->route('profile.setup.show');
        }

        // Si todo ok, mostrar dashboard
        return view('dashboard');
    }
}