<?php
/**
 * Nombre de la clase           : CreateRatingsTable
 * Descripción de la clase      : Migración que crea la tabla ratings para almacenar
 *                                las calificaciones de usuarios a negocios
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
     * Ejecuta las migraciones para crear la tabla ratings.
     * 
     * Tabla: ratings
     * Descripción: Calificaciones de usuarios móviles a negocios
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('business_id')->constrained('businesses')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('stars'); // 0 a 5
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->unique(['order_id', 'user_id']); // Un usuario solo puede calificar una vez por orden
            $table->index('business_id');
        });
    }

    /**
     * Revierte las migraciones eliminando la tabla ratings.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
