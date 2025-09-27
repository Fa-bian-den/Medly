<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('center_user', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('center_id')->index();

            // Rol del usuario dentro del centro (p. ej. doctor, admin, staff)
            $table->string('role_at_center')->nullable();

            // Periodo de actividad del vínculo
            $table->date('active_from')->nullable();
            $table->date('active_to')->nullable();

            // Indicador si es centro principal para ese usuario
            $table->boolean('is_primary')->default(false);

            $table->text('notes')->nullable();

            // Evitar duplicados (un usuario no debe tener dos filas activas iguales por centro)
            $table->unique(['user_id', 'center_id'], 'center_user_user_center_unique');

            // Claves foráneas y política de borrado/actualización
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');

            $table->foreign('center_id')->references('id')->on('centers')->onDelete('restrict')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('center_user', function (Blueprint $table) {
            // eliminar constraints antes de dropear la tabla
            $table->dropForeign(['user_id']);
            $table->dropForeign(['center_id']);
            $table->dropUnique('center_user_user_center_unique');
        });

        Schema::dropIfExists('center_user');
    }
};