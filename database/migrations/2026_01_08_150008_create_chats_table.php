<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones para crear la tabla chats.
     * 
     * Tabla: chats
     * Descripción: Conversaciones entre usuario móvil y negocio
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('business_id')->constrained('businesses')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['order_id', 'user_id']); // Un chat por orden y usuario
        });
    }

    /**
     * Revierte las migraciones eliminando la tabla chats.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
