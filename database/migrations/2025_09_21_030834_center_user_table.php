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
            $table->date('active_from')->nullable();
            $table->date('active_to')->nullable();
            $table->boolean('primary')->default(false);
            $table->text('notes')->nullable();
            $table->unique(['user_id','center_id']);
            $table->index('center_id');
            $table->index('user_id');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('center_id');
            $table->foreign('center_id')->references('id')->on('centers')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
            
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('center_user');
    }
};
