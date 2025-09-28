<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Stub mínimo para TrimStrings.
 * En entornos reales debes usar Illuminate\Foundation\Http\Middleware\TrimStrings.
 * Este stub solo pasa la petición sin modificarla.
 */
class TrimStrings
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}