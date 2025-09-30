<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\ProfileSetupController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AppointmentSlotController;
use App\Http\Controllers\AppointmentDocumentController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\CenterController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/*
Rutas de autenticación pública (registro / Google OAuth)
*/
// Mostrar formulario de registro (guest)
Route::get('register', [RegisteredUserController::class, 'create'])
    ->middleware('guest')
    ->name('register.show');

// Enviar formulario de registro (guest)
Route::post('register', [RegisteredUserController::class, 'store'])
    ->middleware('guest')
    ->name('register');

// Google OAuth: redirección y callback
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

/*
Verificación de email
*/
// Mostrar aviso para verificar email (usuario autenticado)
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Endpoint de verificación (firmada)
Route::get('/email/verify/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['auth','signed'])
    ->name('verification.verify');

// Reenvío del email de verificación (throttle)
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth','throttle:6,1'])->name('verification.send');

/*
Rutas que requieren autenticación
*/
Route::middleware('auth')->group(function () {
    // Perfil: ver/editar/actualizar/eliminar
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Resources: citas (CRUD)
    Route::resource('appointments', AppointmentController::class);

    // Slots de turnos (CRUD)
    Route::resource('appointment_slots', AppointmentSlotController::class);

    // Documentos de citas (CRUD parcial: index, create, store, show, destroy)
    Route::resource('appointment_documents', AppointmentDocumentController::class)
        ->only(['index','create','store','show','destroy']);

        // Rutas de pacientes
    Route::middleware('auth')->group(function () {
        Route::resource('patients', \App\Http\Controllers\PatientController::class)->except(['create','edit']);
        // usamos resource por REST; si ya tenés rutas individuales, añade:
        Route::get('patients', [\App\Http\Controllers\PatientController::class, 'index'])->name('patients.index');
        Route::get('patients/create', [\App\Http\Controllers\PatientController::class, 'create'])->name('patients.create');
        Route::post('patients', [\App\Http\Controllers\PatientController::class, 'store'])->name('patients.store');
        Route::get('patients/{patient}', [\App\Http\Controllers\PatientController::class, 'show'])->name('patients.show');
        Route::get('patients/{patient}/edit', [\App\Http\Controllers\PatientController::class, 'edit'])->name('patients.edit');
        Route::put('patients/{patient}', [\App\Http\Controllers\PatientController::class, 'update'])->name('patients.update');
        Route::delete('patients/{patient}', [\App\Http\Controllers\PatientController::class, 'destroy'])->name('patients.destroy');
});


    // Doctores (web) y acción adicional review
    Route::resource('doctors', DoctorController::class);
    Route::post('doctors/{doctor}/review', [DoctorController::class, 'review'])->name('doctors.review');

    // Centers (web CRUD)
    Route::resource('centers', CenterController::class);
}); // fin group auth

/*
Rutas que requieren autenticación y verificación de email
*/
// Dashboard protegido por auth + verified
Route::get('/dashboard', [ProfileController::class, 'dashboard'])
    ->middleware(['auth','verified'])
    ->name('dashboard');

// Setup de perfil (auth + verified)
Route::middleware(['auth','verified'])->group(function(){
    Route::get('/profile/setup', [ProfileSetupController::class, 'show'])->name('profile.setup.show');
    Route::post('/profile/setup', [ProfileSetupController::class, 'store'])->name('profile.setup.store');
});

Route::resource('admin', App\Http\Controllers\AdminController::class);

// routes/web.php
Route::get('/mapa', function () {
    return view('static.mapa');
})->name('mapa');

/*
Carga de rutas de auth adicionales
*/
require __DIR__.'/auth.php';