<?php
/**
 * Nombre de la clase           : CreateOrderQrCodesTable
 * Descripción de la clase      : Migración que crea la tabla order_qr_codes para almacenar
 *                                los códigos QR generados para asociación y entrega de órdenes
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
    public function up(): void
    {
        Schema::create('order_qr_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->enum('type', ['association', 'delivery']); // association: asociar con usuario, delivery: confirmar entrega
            $table->string('code', 100)->unique(); // Código único del QR
            $table->text('qr_image')->nullable(); // Imagen del QR en base64 o ruta
            $table->boolean('is_used')->default(false);
            $table->timestamp('used_at')->nullable();
            $table->foreignId('used_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('expires_at')->nullable(); // Expiración opcional del QR
            $table->timestamps();

            // Índices
            $table->index('code');
            $table->index(['order_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_qr_codes');
    }
};