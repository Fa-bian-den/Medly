<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            // patient_id y user_id referencian usuarios en users.
            $table->unsignedBigInteger('patient_id')->index();
            $table->unsignedBigInteger('user_id')->index(); // user_id = médico responsable

            $table->unsignedBigInteger('center_id')->index();
            $table->unsignedBigInteger('service_id')->index();
            $table->unsignedBigInteger('slot_id')->nullable()->index();

            $table->dateTime('scheduled_at')->index();
            $table->enum('status', ['pendiente','confirmado','reprogramado','atendido','cancelado','no se presento'])->default('pendiente');

            $table->text('reason')->nullable();

            $table->unsignedBigInteger('created_by')->nullable()->index(); // usuario que creó la cita
            $table->unsignedBigInteger('cancelled_by')->nullable()->index(); // usuario que canceló la cita
            $table->text('cancellation_reason')->nullable();

            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');

            $table->foreign('center_id')->references('id')->on('centers')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('slot_id')->references('id')->on('appointment_slots')->onDelete('set null')->onUpdate('cascade');

            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('cancelled_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['patient_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['center_id']);
            $table->dropForeign(['service_id']);
            $table->dropForeign(['slot_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['cancelled_by']);
        });

        Schema::dropIfExists('appointments');
    }
};