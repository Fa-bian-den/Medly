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
        Schema::create('schedule_shifts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('schedule_id')->index();

            $table->time('start_time');
            $table->time('end_time');

            $table->unsignedSmallInteger('slot_duration_minutes')->default(15);
            $table->unsignedSmallInteger('capacity')->default(1);
            $table->unsignedSmallInteger('break_minutes')->default(0);

            $table->boolean('active')->default(true);

            $table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('restrict')->onUpdate('cascade');

            $table->timestamps();

            $table->unique(['schedule_id', 'start_time', 'end_time'], 'schedule_shifts_schedule_time_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedule_shifts', function (Blueprint $table) {
            $table->dropForeign(['schedule_id']);
            $table->dropUnique('schedule_shifts_schedule_time_unique');
        });

        Schema::dropIfExists('schedule_shifts');
    }
};