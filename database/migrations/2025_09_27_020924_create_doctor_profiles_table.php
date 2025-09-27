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
        Schema::create('doctor_profiles', function (Blueprint $table) {
            $table->id();

            // FK al usuario (convención: user_id)
            $table->unsignedBigInteger('user_id')->unique()->comment('FK -> users.id; un perfil por usuario');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');

            // Centro propuesto por el médico privado (opcional)
            $table->unsignedBigInteger('center_id_proposed')->nullable()->index()->comment('FK -> centers.id; centro propuesto en solicitud');
            $table->foreign('center_id_proposed')->references('id')->on('centers')->onDelete('set null')->onUpdate('cascade');

            // Datos médicos y administrativos
            $table->string('carnet_minsa')->nullable();
            $table->json('documents')->nullable()->comment('Metadatos/rutas de documentos subidos (carnet, título, id)');
            $table->string('ruc')->nullable();
            $table->json('specialties')->nullable()->comment('Lista de especialidades, ej ["pediatria","odontologia"]');

            // Flujo de validación
            $table->enum('status_validation', ['pendiente','en_revision','aprobado','rechazado'])->default('pendiente')->comment('Estado del proceso de validación');
            $table->unsignedBigInteger('reviewed_by')->nullable()->index()->comment('FK -> users.id del admin revisor');
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->timestamp('validated_at')->nullable()->comment('Fecha en que se aprobó/rechazó');

            $table->text('comments')->nullable()->comment('Comentarios del revisor');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_profiles', function (Blueprint $table) {
            // eliminar constraints antes de dropear la tabla
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            // verificar existencia de FK y soltarlas (compatibilidad prudente)
            if (Schema::hasColumn('doctor_profiles', 'reviewed_by')) {
                $table->dropForeign(['reviewed_by']);
            }
            if (Schema::hasColumn('doctor_profiles', 'center_id_proposed')) {
                $table->dropForeign(['center_id_proposed']);
            }
            if (Schema::hasColumn('doctor_profiles', 'user_id')) {
                $table->dropForeign(['user_id']);
            }
        });

        Schema::dropIfExists('doctor_profiles');
    }
};