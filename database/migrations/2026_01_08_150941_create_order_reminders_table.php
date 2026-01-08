<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones para crear la tabla order_reminders.
     * 
     * Tabla: order_reminders
     * Descripción: Alertas/recordatorios programados para órdenes
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('order_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->integer('reminder_minutes'); // Minutos después de ready_at para enviar recordatorio
            $table->timestamp('scheduled_at'); // Cuándo se debe enviar
            $table->boolean('sent')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['order_id', 'sent']);
        });
    }

    /**
     * Revierte las migraciones eliminando la tabla order_reminders.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('order_reminders');
    }
};
