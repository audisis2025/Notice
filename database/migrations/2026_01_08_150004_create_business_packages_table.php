<?php
/**
 * Nombre de la clase           : CreateBusinessPackagesTable
 * Descripción de la clase      : Migración que crea la tabla business_packages para registrar
 *                                las contrataciones de paquetes por parte de los negocios
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
     * Ejecuta las migraciones para crear la tabla business_packages.
     * 
     * Tabla: business_packages
     * Descripción: Suscripciones de negocios a paquetes contratados
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('business_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->onDelete('cascade');
            $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('price_paid', 10, 2);
            $table->decimal('discount_applied', 10, 2)->default(0);
            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->onDelete('set null');
            $table->enum('status', ['active', 'expired', 'cancelled'])->default('active');
            $table->boolean('notification_sent')->default(false); // Notificación de próximo vencimiento
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Revierte las migraciones eliminando la tabla business_packages.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('business_packages');
    }
};