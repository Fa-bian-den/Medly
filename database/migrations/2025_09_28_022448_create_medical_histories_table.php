<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_histories', function (Blueprint $table) {
            $table->id();

            // FK al paciente: no permitimos borrado en cascada -> RESTRICT
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');

            // quién registró/actualizó: nullable; si se borra ese usuario, dejamos NULL
            $table->unsignedBigInteger('recorded_by')->nullable();

            $table->string('summary')->nullable();
            $table->json('details')->nullable();
            $table->json('allergies')->nullable();
            $table->string('blood_type', 10)->nullable();
            $table->text('notes')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique('user_id');
            $table->index('user_id');
        });

        // Añadir la FK de recorded_by separada para poder usar SET NULL
        Schema::table('medical_histories', function (Blueprint $table) {
            $table->foreign('recorded_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('medical_histories', function (Blueprint $table) {
            // eliminar constraints antes de borrar la tabla
            if (Schema::hasColumn('medical_histories', 'recorded_by')) {
                $table->dropForeign(['recorded_by']);
            }
            if (Schema::hasColumn('medical_histories', 'user_id')) {
                $table->dropForeign(['user_id']);
            }
        });

        Schema::dropIfExists('medical_histories');
    }
};