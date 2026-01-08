<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones para crear la tabla system_settings.
     * 
     * Tabla: system_settings
     * Descripción: Configuraciones generales del sistema
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, integer, boolean, json
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Revierte las migraciones eliminando la tabla system_settings.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};