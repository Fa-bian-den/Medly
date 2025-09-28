<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureProfileCompleted
{
    /**
     * Redirige al setup de perfil si el usuario no completó el perfil.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (! $user) {
            return $next($request);
        }

        // Si tienes columna boolean profile_completed en users, úsala.
        if (! empty($user->profile_completed)) {
            return $next($request);
        }

        // Permitir rutas de setup, profile edit y logout para evitar loop
        if ($request->routeIs('profile.setup.*') ||
            $request->routeIs('profile.edit') ||
            $request->routeIs('logout')) {
            return $next($request);
        }

        return redirect()->route('profile.setup.show');
    }
}