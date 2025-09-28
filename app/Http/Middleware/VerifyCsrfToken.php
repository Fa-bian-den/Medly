<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Stub mínimo de VerifyCsrfToken.
 * Este stub NO valida tokens CSRF. Usar solo en desarrollo temporalmente.
 * Para producción debes restaurar la implementación original.
 */
class VerifyCsrfToken
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}