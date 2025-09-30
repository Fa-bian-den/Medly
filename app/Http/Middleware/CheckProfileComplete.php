<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckProfileComplete
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (! $user) {
            return $next($request);
        }

        // Rutas permitidas mientras el perfil no está completo
        $except = [
            'profile.setup',      // GET form
            'profile.setup.store' // POST save
        ];

        $routeName = optional($request->route())->getName();

        if (! $user->completed_profile) {
            if (! in_array($routeName, $except, true)) {
                return redirect()->route('profile.setup');
            }
        }

        return $next($request);
    }
}