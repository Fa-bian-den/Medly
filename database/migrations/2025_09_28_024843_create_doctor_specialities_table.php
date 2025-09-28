<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations
     */
    public function up(): void
    {
        Schema::create('doctor_specialities', function (Blueprint $table) {
            $table->id();

            $table->foreignId('doctor_profile_id')->constrained('doctor_profiles')->onDelete('restrict');


            $table->foreignId('speciality_id')->constrained('specialities')->onDelete('restrict');

            // evitar duplicados doctor_profile <> speciality
            $table->unique(['doctor_profile_id', 'speciality_id']);

            $table->timestamps();

            // índices para búsquedas rápidas
            $table->index('doctor_profile_id');
            $table->index('speciality_id');
        });
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        Schema::table('doctor_specialities', function (Blueprint $table) {
            // eliminar FKs antes de dropear la tabla
            if (Schema::hasColumn('doctor_specialities', 'doctor_profile_id')) {
                $table->dropForeign(['doctor_profile_id']);
            }
            if (Schema::hasColumn('doctor_specialities', 'speciality_id')) {
                $table->dropForeign(['speciality_id']);
            }
        });

        Schema::dropIfExists('doctor_specialities');
    }
};