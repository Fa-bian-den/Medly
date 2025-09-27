<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointment_documents', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('appointment_id')->index();
            $table->unsignedBigInteger('uploaded_by')->nullable()->index(); // FK -> users.id

            $table->string('type')->nullable();
            $table->string('path');
            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('restrict')->onUpdate('cascade');

            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('appointment_documents', function (Blueprint $table) {
            $table->dropForeign(['appointment_id']);
            $table->dropForeign(['uploaded_by']);
        });

        Schema::dropIfExists('appointment_documents');
    }
};