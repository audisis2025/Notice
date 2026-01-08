<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones para crear la tabla verification_codes.
     * 
     * Tabla: verification_codes
     * Descripción: Códigos de verificación para login de usuarios móviles
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('verification_codes', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 20);
            $table->string('code', 6);
            $table->timestamp('expires_at');
            $table->boolean('is_used')->default(false);
            $table->timestamp('used_at')->nullable();
            $table->timestamps();

            $table->index(['phone', 'code', 'expires_at']);
        });
    }

    /**
     * Revierte las migraciones eliminando la tabla verification_codes.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('verification_codes');
    }
};
