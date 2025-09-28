<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Stub mínimo de RedirectIfAuthenticated.
 * Si el usuario ya está autenticado redirige a /dashboard (o route 'dashboard' si existe).
 */
class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if (Auth::check()) {
            if (function_exists('route') && route('dashboard', [], false)) {
                return redirect(route('dashboard'));
            }
            return redirect('/');
        }

        return $next($request);
    }
}