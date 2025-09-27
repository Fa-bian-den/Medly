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
        Schema::create('center_service', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('center_id')->index();
            $table->unsignedBigInteger('service_id')->index();

            $table->decimal('price', 10, 2)->default(0.00);
            $table->boolean('active')->default(true);

            $table->unique(['center_id', 'service_id'], 'center_service_center_service_unique');

            $table->foreign('center_id')->references('id')->on('centers')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('restrict')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('center_service', function (Blueprint $table) {
            $table->dropForeign(['center_id']);
            $table->dropForeign(['service_id']);
            $table->dropUnique('center_service_center_service_unique');
        });

        Schema::dropIfExists('center_service');
    }
};