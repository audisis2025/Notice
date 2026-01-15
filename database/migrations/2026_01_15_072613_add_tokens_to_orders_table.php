<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Agregar campos para tokens
            $table->string('association_token', 100)->nullable()->after('qr_delivery_code');
            $table->string('delivery_token', 100)->nullable()->after('association_token');
            
            // Índices para búsqueda rápida
            $table->index('association_token');
            $table->index('delivery_token');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['association_token']);
            $table->dropIndex(['delivery_token']);
            $table->dropColumn(['association_token', 'delivery_token']);
        });
    }
};