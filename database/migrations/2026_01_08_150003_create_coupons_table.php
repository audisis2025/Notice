<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones para crear la tabla coupons.
     * 
     * Tabla: coupons
     * Descripción: Cupones de descuento generados por el SuperAdministrador
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->decimal('discount_percentage', 5, 2); // Porcentaje de descuento (0-100)
            $table->date('expiration_date');
            $table->boolean('is_used')->default(false);
            $table->foreignId('used_by_business_id')->nullable()->constrained('businesses')->onDelete('set null');
            $table->timestamp('used_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Revierte las migraciones eliminando la tabla coupons.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};