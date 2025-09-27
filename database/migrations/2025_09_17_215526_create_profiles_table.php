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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();

            $table->date('birthdate')->nullable();
            $table->text('address')->nullable();
            $table->text('idcard')->nullable();
            $table->string('phone')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();

            $table->json('documents_metadata')->nullable();
            $table->json('professional_details')->nullable();

            // Campos clínicos añadidos
            $table->json('clinical_history')->nullable();
            $table->json('allergies')->nullable();
            $table->json('medications')->nullable();
            $table->json('emergency_contact')->nullable();
            $table->text('clinical_notes')->nullable();

            $table->unsignedBigInteger('user_id')->unique()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('profiles');
    }
};