<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AppointmentSlotController;
use App\Http\Controllers\AppointmentDocumentController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\CenterController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MedicalHistoryController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ScheduleExceptionController;
use App\Http\Controllers\ScheduleShiftController;

Route::middleware(['auth:sanctum'])->group(function () {
    // Appointments API
    Route::get('appointments', [AppointmentController::class, 'apiIndex'])->name('api.appointments.index');
    Route::get('appointments/{appointment}', [AppointmentController::class, 'apiShow'])->name('api.appointments.show');
    Route::post('appointments', [AppointmentController::class, 'store'])->name('api.appointments.store');
    Route::put('appointments/{appointment}', [AppointmentController::class, 'update'])->name('api.appointments.update');
    Route::delete('appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('api.appointments.destroy');

    // Appointment slots API
    Route::get('appointment_slots', [AppointmentSlotController::class, 'apiIndex'])->name('api.appointment_slots.index');
    Route::get('appointment_slots/{slot}', [AppointmentSlotController::class, 'apiShow'])->name('api.appointment_slots.show');
    Route::post('appointment_slots', [AppointmentSlotController::class, 'store'])->name('api.appointment_slots.store');
    Route::put('appointment_slots/{slot}', [AppointmentSlotController::class, 'update'])->name('api.appointment_slots.update');
    Route::delete('appointment_slots/{slot}', [AppointmentSlotController::class, 'destroy'])->name('api.appointment_slots.destroy');

    // Appointment documents API
    Route::get('appointment_documents', [AppointmentDocumentController::class, 'apiIndex'])->name('api.appointment_documents.index');
    Route::get('appointment_documents/{document}', [AppointmentDocumentController::class, 'apiShow'])->name('api.appointment_documents.show');
    Route::post('appointment_documents', [AppointmentDocumentController::class, 'store'])->name('api.appointment_documents.store');
    Route::delete('appointment_documents/{document}', [AppointmentDocumentController::class, 'destroy'])->name('api.appointment_documents.destroy');

    // Patients API
    Route::get('patients', [PatientController::class, 'apiIndex'])->name('api.patients.index');
    Route::get('patients/{patient}', [PatientController::class, 'apiShow'])->name('api.patients.show');
    Route::post('patients', [PatientController::class, 'store'])->name('api.patients.store');
    Route::put('patients/{patient}', [PatientController::class, 'update'])->name('api.patients.update');
    Route::delete('patients/{patient}', [PatientController::class, 'destroy'])->name('api.patients.destroy');

    // Doctors API
    Route::get('doctors', [DoctorController::class, 'apiIndex'])->name('api.doctors.index');
    Route::get('doctors/{doctor}', [DoctorController::class, 'apiShow'])->name('api.doctors.show');
    Route::post('doctors', [DoctorController::class, 'store'])->name('api.doctors.store');
    Route::put('doctors/{doctor}', [DoctorController::class, 'update'])->name('api.doctors.update');
    Route::delete('doctors/{doctor}', [DoctorController::class, 'destroy'])->name('api.doctors.destroy');

    // Centers API
    Route::get('centers', [CenterController::class, 'apiIndex'])->name('api.centers.index');
    Route::get('centers/{center}', [CenterController::class, 'apiShow'])->name('api.centers.show');
    Route::post('centers', [CenterController::class, 'store'])->name('api.centers.store');
    Route::put('centers/{center}', [CenterController::class, 'update'])->name('api.centers.update');
    Route::delete('centers/{center}', [CenterController::class, 'destroy'])->name('api.centers.destroy');

    // Notifications API
    Route::get('notifications', [NotificationController::class, 'apiIndex'])->name('api.notifications.index');
    Route::get('notifications/{notification}', [NotificationController::class, 'apiShow'])->name('api.notifications.show');
    Route::post('notifications', [NotificationController::class, 'store'])->name('api.notifications.store');
    Route::put('notifications/{notification}', [NotificationController::class, 'update'])->name('api.notifications.update');
    Route::delete('notifications/{notification}', [NotificationController::class, 'destroy'])->name('api.notifications.destroy');
    Route::post('notifications/mark-all-read', [NotificationController::class, 'apiMarkAllRead'])->name('api.notifications.mark_all_read');

    // Admins API
    Route::get('admin', [AdminController::class, 'apiIndex'])->name('api.admin.index');
    Route::get('admin/{admin}', [AdminController::class, 'apiShow'])->name('api.admin.show');
    Route::post('admin', [AdminController::class, 'store'])->name('api.admin.store');
    Route::put('admin/{admin}', [AdminController::class, 'update'])->name('api.admin.update');
    Route::delete('admin/{admin}', [AdminController::class, 'destroy'])->name('api.admin.destroy');

    // Medical history API
    Route::get('medical_histories', [MedicalHistoryController::class, 'apiIndex'])->name('api.medical_histories.index');
    Route::get('medical_histories/{history}', [MedicalHistoryController::class, 'apiShow'])->name('api.medical_histories.show');
    Route::post('medical_histories', [MedicalHistoryController::class, 'store'])->name('api.medical_histories.store');
    Route::put('medical_histories/{history}', [MedicalHistoryController::class, 'update'])->name('api.medical_histories.update');
    Route::delete('medical_histories/{history}', [MedicalHistoryController::class, 'destroy'])->name('api.medical_histories.destroy');

    // Schedule endpoints
    Route::get('schedule/setup', [ScheduleController::class, 'apiIndex'])->name('api.schedule.setup.index');
    Route::post('schedule/setup', [ScheduleController::class, 'store'])->name('api.schedule.setup.store');
    Route::put('schedule/setup/{id}', [ScheduleController::class, 'update'])->name('api.schedule.setup.update');
    Route::delete('schedule/setup/{id}', [ScheduleController::class, 'destroy'])->name('api.schedule.setup.destroy');

    Route::get('schedule/exceptions', [ScheduleExceptionController::class, 'apiIndex'])->name('api.schedule.exceptions.index');
    Route::post('schedule/exceptions', [ScheduleExceptionController::class, 'store'])->name('api.schedule.exceptions.store');
    Route::put('schedule/exceptions/{id}', [ScheduleExceptionController::class, 'update'])->name('api.schedule.exceptions.update');
    Route::delete('schedule/exceptions/{id}', [ScheduleExceptionController::class, 'destroy'])->name('api.schedule.exceptions.destroy');

    Route::get('schedule/shifts', [ScheduleShiftController::class, 'apiIndex'])->name('api.schedule.shifts.index');
    Route::post('schedule/shifts', [ScheduleShiftController::class, 'store'])->name('api.schedule.shifts.store');
    Route::put('schedule/shifts/{id}', [ScheduleShiftController::class, 'update'])->name('api.schedule.shifts.update');
    Route::delete('schedule/shifts/{id}', [ScheduleShiftController::class, 'destroy'])->name('api.schedule.shifts.destroy');
});