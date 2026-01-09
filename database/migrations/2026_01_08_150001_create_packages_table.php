<?php
/**
 * Nombre de la clase           : CreatePackagesTable
 * Descripción de la clase      : Migración que crea la tabla packages para almacenar
 *                                los paquetes comerciales disponibles en el sistema
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
     * Ejecuta las migraciones para crear la tabla packages.
     * 
     * Tabla: packages
     * Descripción: Define los paquetes/planes disponibles para los negocios
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('duration_days'); // Duración del paquete en días
            $table->boolean('has_reports')->default(false);
            $table->boolean('has_statistics')->default(false);
            $table->boolean('has_filters')->default(false);
            $table->integer('data_retention_days')->default(30); // Tiempo de retención de datos
            $table->integer('max_orders')->nullable(); // Límite de órdenes (null = ilimitado)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Revierte las migraciones eliminando la tabla packages.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
