<?php
/**
 * Nombre de la clase           : CreateActivityLogsTable
 * Descripción de la clase      : Migración que crea la tabla activity_logs para registrar
 *                                la actividad de los usuarios en el sistema
 * Fecha de creación            : 09/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 09/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 1.0
 * Fecha de mantenimiento       : 
 * Folio de mantenimiento       : 
 * Tipo de mantenimiento        :
 * Descripción del mantenimiento: 
 * Responsable                  : 
 * Revisor                      : 
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones para crear la tabla activity_logs.
     * 
     * Tabla: activity_logs
     * Descripción: Registro de actividades importantes del sistema
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('log_name')->nullable();
            $table->text('description');
            $table->nullableMorphs('subject', 'subject');
            $table->nullableMorphs('causer', 'causer');
            $table->json('properties')->nullable();
            $table->string('event')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index('log_name');
        });
    }

    /**
     * Revierte las migraciones eliminando la tabla activity_logs.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
