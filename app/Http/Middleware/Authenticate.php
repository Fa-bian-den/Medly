<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Stub mínimo de Authenticate.
 * Si el usuario no está autenticado redirige a login.
 * Usa la guardia por defecto de Laravel a través de Auth facade.
 */
class Authenticate
{
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if (! Auth::check()) {
            // redirigir a ruta de login si existe, sino abort 401
            if (function_exists('route') && route('login', [], false)) {
                return redirect()->guest(route('login'));
            }
            abort(401);
        }

        return $next($request);
    }
}