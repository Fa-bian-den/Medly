<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use Illuminate\Support\Facades\Route;

Route::post('auth/send-code', [EmailVerificationController::class, 'sendCode']);
Route::post('auth/verify-code', [EmailVerificationController::class, 'verifyCode']);