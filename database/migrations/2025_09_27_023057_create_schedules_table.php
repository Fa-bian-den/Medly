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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('doctor_id')->index();
            $table->unsignedBigInteger('center_id')->index();

            $table->string('name')->nullable();
            $table->enum('recurrence_type', ['ninguno', 'diario', 'semanal', 'mensual'])->default('semanal');
            $table->json('recurrence_days')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('active')->default(true);

            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('center_id')->references('id')->on('centers')->onDelete('restrict')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign(['doctor_id']);
            $table->dropForeign(['center_id']);
        });

        Schema::dropIfExists('schedules');
    }
};