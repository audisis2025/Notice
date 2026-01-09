<?php
/**
 * Nombre de la clase           : CreateBusinessesTable
 * Descripción de la clase      : Migración que crea la tabla businesses para almacenar
 *                                la información de los negocios registrados
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
     * Ejecuta las migraciones para crear la tabla businesses.
     * 
     * Tabla: businesses
     * Descripción: Almacena información de los negocios registrados en el sistema
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('business_name');
            $table->string('legal_name');
            $table->string('tax_id', 50)->unique(); // RFC o identificador fiscal
            $table->string('address');
            $table->string('city', 100);
            $table->string('state', 100);
            $table->string('country', 100)->default('México');
            $table->string('postal_code', 10);
            $table->string('phone', 20);
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('can_be_rated')->default(true);
            $table->integer('delivery_period_minutes')->default(30); // Periodo de entrega en minutos
            $table->boolean('is_active')->default(true);
            $table->timestamp('service_suspended_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Revierte las migraciones eliminando la tabla businesses.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
