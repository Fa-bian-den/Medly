<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\GoogleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\ProfileSetupController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AppointmentSlotController;
use App\Http\Controllers\AppointmentDocumentController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;

use Illuminate\Http\Request;


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

    // Recursos de citas (CRUD)
    Route::resource('appointments', AppointmentController::class);

    // Endpoint JSON paginado para citas (SPA / mobile)
    Route::get('api/appointments', [AppointmentController::class, 'apiIndex'])->name('api.appointments.index');

        // Slots de turnos (CRUD)
    Route::resource('appointment_slots', AppointmentSlotController::class);

    // Endpoint JSON (opcional)
    Route::get('api/appointment_slots', [AppointmentSlotController::class, 'apiIndex'])->name('api.appointment_slots.index');
    });

        // Documentos de citas (CRUD parcial: index, create, store, show, destroy)
    Route::resource('appointment_documents', AppointmentDocumentController::class)
        ->only(['index','create','store','show','destroy']);

    // Endpoint JSON para documentos
    Route::get('api/appointment_documents', [AppointmentDocumentController::class, 'apiIndex'])->name('api.appointment_documents.index');

    // Pacientes (web + api)
    Route::middleware('auth')->group(function () {
        Route::resource('patients', PatientController::class);
        Route::get('api/patients', [PatientController::class, 'apiIndex'])->name('api.patients.index');
        Route::get('api/patients/{patient}', [PatientController::class, 'apiShow'])->name('api.patients.show');
    });

    Route::middleware('auth')->group(function () {
    Route::resource('doctors', DoctorController::class);
    Route::post('doctors/{doctor}/review', [DoctorController::class, 'review'])->name('doctors.review');
    Route::get('api/doctors', [DoctorController::class, 'apiIndex'])->name('api.doctors.index');
});


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

/*
Carga de rutas de auth adicionales
*/
require __DIR__.'/auth.php';