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
        Schema::create('schedule_exceptions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('schedule_id')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedBigInteger('center_id')->nullable()->index();

            $table->date('date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            $table->enum('type', ['block', 'extra', 'vacation', 'other'])->default('block');
            $table->text('reason')->nullable();

            $table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('center_id')->references('id')->on('centers')->onDelete('set null')->onUpdate('cascade');

            $table->timestamps();

            $table->unique(['schedule_id', 'date', 'start_time', 'end_time'], 'schedule_exceptions_unique_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedule_exceptions', function (Blueprint $table) {
            $table->dropForeign(['schedule_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['center_id']);
            $table->dropUnique('schedule_exceptions_unique_time');
        });

        Schema::dropIfExists('schedule_exceptions');
    }
};