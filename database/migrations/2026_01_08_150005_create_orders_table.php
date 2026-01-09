<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones para crear la tabla orders.
     * 
     * Tabla: orders
     * Descripción: Órdenes creadas por los negocios y asociadas a usuarios móviles
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique();
            $table->foreignId('business_id')->constrained('businesses')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('description');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'paid', 'ready', 'delivered', 'cancelled'])->default('pending');
            $table->string('qr_code')->nullable(); // Path del QR para asociar orden
            $table->string('qr_delivery_code')->nullable(); // QR para confirmar entrega
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('ready_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('associated_at')->nullable(); // Cuando el usuario escanea el QR
            $table->boolean('chat_enabled')->default(false); // Se habilita si se excede delivery_period
            $table->timestamps();
            $table->softDeletes();

            $table->index(['business_id', 'status']);
            $table->index('user_id');
        });
    }

    /**
     * Revierte las migraciones eliminando la tabla orders.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
