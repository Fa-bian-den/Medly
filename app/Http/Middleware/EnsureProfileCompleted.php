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

        // Recomendación: usar un boolean claro en users, por ejemplo completed_profile
        // y comprobar estrictamente === true to avoid surprises with empty strings/null.
        if (! empty($user->completed_profile)) {
            return $next($request);
        }

        // Permitir rutas de setup y logout para evitar loop
        // Asegúrate que tus rutas realmente se llaman así en routes/web.php
        if ($request->routeIs('profile.setup') ||
            $request->routeIs('profile.setup.store') ||
            $request->routeIs('profile.edit') ||
            $request->routeIs('logout')) {
            return $next($request);
        }

        // Redirige a la ruta que muestre el formulario de setup (ajusta el nombre si tu ruta es distinta)
        return redirect()->route('profile.setup');
    }
}