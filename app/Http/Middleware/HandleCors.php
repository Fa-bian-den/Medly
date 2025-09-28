<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Stub mínimo de CORS. Solo pasa la petición.
 * Reemplazar por logic real si tu app necesita CORS en endpoints API.
 */
class HandleCors
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}