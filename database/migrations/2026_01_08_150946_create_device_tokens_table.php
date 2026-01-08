<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones para crear la tabla device_tokens.
     * 
     * Tabla: device_tokens
     * Descripción: Tokens de dispositivos móviles para notificaciones push
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('device_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('token')->unique();
            $table->string('platform'); // 'ios' o 'android'
            $table->string('device_id')->nullable();
            $table->string('device_model')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_active']);
        });
    }

    /**
     * Revierte las migraciones eliminando la tabla device_tokens.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('device_tokens');
    }
};
