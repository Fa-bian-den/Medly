<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Stub mínimo de EncryptCookies.
 * No cifra/descifra cookies; solo pasa la petición.
 * Reemplaza por la versión oficial si requieres encriptación de cookies.
 */
class EncryptCookies
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}