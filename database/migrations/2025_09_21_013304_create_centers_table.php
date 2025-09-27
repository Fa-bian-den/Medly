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
        Schema::create('centers', function (Blueprint $table) {
            $table->id();

            // FK a municipio (convención: usar *_id para FKs)
            $table->unsignedBigInteger('municipality_id')->index();

            $table->string('name');
            $table->string('logo')->nullable();      // ruta o URL al logo
            $table->text('address')->nullable();
            $table->string('url')->nullable();       // URL de maps o sitio
            $table->string('phone')->nullable();
            $table->string('ruc')->nullable();
            $table->boolean('is_public')->default(false);

            $table->foreign('municipality_id')->references('id')->on('municipalities')->onDelete('restrict')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('centers', function (Blueprint $table) {
            $table->dropForeign(['municipality_id']);
        });
        
        Schema::dropIfExists('centers');
    }
};