<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointment_slots', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('center_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('service_id')->index();

            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');

            $table->unsignedSmallInteger('capacity')->default(1);

            $table->unique(['center_id', 'user_id', 'service_id', 'date', 'start_time'], 'appointment_slots_unique');

            $table->foreign('center_id')->references('id')->on('centers')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('restrict')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('appointment_slots', function (Blueprint $table) {
            $table->dropForeign(['center_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['service_id']);
            $table->dropUnique('appointment_slots_unique');
        });

        Schema::dropIfExists('appointment_slots');
    }
};