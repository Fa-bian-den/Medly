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
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar el formulario para editar perfil.
     */
    public function edit(Request $request): View|RedirectResponse
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Actualizar solo los campos permitidos del perfil.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        if (! method_exists($user, 'profile')) {
            return Redirect::back()->withErrors(['profile' => 'Relación profile no definida en User.']);
        }

        $profile = $user->profile ?? $user->profile()->create([]);

        $allowed = $request->validated();

        // Ajusta la lista de campos que quieres persistir en profile
        $permittedKeys = ['phone', 'emergency_contact_name', 'emergency_contact_phone'];
        $permitted = array_intersect_key($allowed, array_flip($permittedKeys));

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

        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Controlador responsable del acceso al dashboard.
     * Si el usuario no tiene el perfil completado lo redirige al setup.
     */
    public function dashboard(Request $request): View|RedirectResponse
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        if (empty($user->email_verified_at)) {
            return redirect()->route('verification.notice');
        }

        // Comprueba la columna real en tu esquema; aquí usamos completed_profile
        if (Schema::hasColumn('users', 'completed_profile') && empty($user->completed_profile)) {
            return redirect()->route('profile.setup');
        }

        return view('dashboard');
    }
}