<?php

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
