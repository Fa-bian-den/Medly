<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * Controller base para la aplicación.
 * Contiene los traits estándar de Laravel que proporcionan:
 *  - authorize / authorizeForUser (AuthorizesRequests)
 *  - dispatch / dispatchNow (DispatchesJobs)
 *  - validate (ValidatesRequests)
 *
 * Mantén los comentarios en español para coherencia del proyecto.
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}