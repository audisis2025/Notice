<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones para crear la tabla payments.
     * 
     * Tabla: payments
     * Descripción: Pagos simulados de paquetes por parte de negocios
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->onDelete('cascade');
            $table->foreignId('business_package_id')->constrained('business_packages')->onDelete('cascade');
            $table->string('payment_method'); // 'credit_card', 'debit_card', etc.
            $table->string('card_last_four', 4)->nullable(); // Últimos 4 dígitos (simulado)
            $table->string('card_brand', 50)->nullable(); // Visa, Mastercard, etc.
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->string('transaction_id', 100)->unique();
            $table->timestamps();
        });
    }

    /**
     * Revierte las migraciones eliminando la tabla payments.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
