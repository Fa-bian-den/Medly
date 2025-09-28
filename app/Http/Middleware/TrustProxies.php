<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Stub mínimo de TrustProxies.
 * Permite que la aplicación no falle si la clase no existía.
 * No realiza manipulación real de proxies; si necesitas soporte real de proxies,
 * reemplaza por la implementación del framework.
 */
class TrustProxies
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}