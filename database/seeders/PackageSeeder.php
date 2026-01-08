<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;

class PackageSeeder extends Seeder
{
    /**
     * Ejecuta el seeder para crear paquetes iniciales.
     * 
     * Crea diferentes planes comerciales con características variadas:
     * - Básico
     * - Profesional
     * - Empresarial
     *
     * @return void
     */
    public function run(): void
    {
        // Paquete Básico
        Package::create([
            'name' => 'Básico',
            'description' => 'Plan inicial para negocios pequeños. Incluye funcionalidades esenciales para gestionar órdenes.',
            'price' => 299.00,
            'duration_days' => 30,
            'has_reports' => false,
            'has_statistics' => false,
            'has_filters' => false,
            'data_retention_days' => 30,
            'max_orders' => 50,
            'is_active' => true,
        ]);

        // Paquete Profesional
        Package::create([
            'name' => 'Profesional',
            'description' => 'Plan recomendado para negocios en crecimiento. Incluye reportes, estadísticas y filtros avanzados.',
            'price' => 599.00,
            'duration_days' => 30,
            'has_reports' => true,
            'has_statistics' => true,
            'has_filters' => true,
            'data_retention_days' => 90,
            'max_orders' => 200,
            'is_active' => true,
        ]);

        // Paquete Empresarial
        Package::create([
            'name' => 'Empresarial',
            'description' => 'Plan completo para negocios grandes. Sin límite de órdenes y retención de datos extendida.',
            'price' => 1299.00,
            'duration_days' => 30,
            'has_reports' => true,
            'has_statistics' => true,
            'has_filters' => true,
            'data_retention_days' => 365,
            'max_orders' => null, // Ilimitado
            'is_active' => true,
        ]);

        // Paquete Trimestral Profesional
        Package::create([
            'name' => 'Profesional Trimestral',
            'description' => 'Plan profesional con 10% de descuento por pago trimestral anticipado.',
            'price' => 1617.00, // 599 * 3 = 1797, con 10% descuento = 1617
            'duration_days' => 90,
            'has_reports' => true,
            'has_statistics' => true,
            'has_filters' => true,
            'data_retention_days' => 90,
            'max_orders' => 200,
            'is_active' => true,
        ]);

        // Paquete Anual Empresarial
        Package::create([
            'name' => 'Empresarial Anual',
            'description' => 'Plan empresarial con 15% de descuento por pago anual anticipado.',
            'price' => 13243.00, // 1299 * 12 = 15588, con 15% descuento = 13243
            'duration_days' => 365,
            'has_reports' => true,
            'has_statistics' => true,
            'has_filters' => true,
            'data_retention_days' => 365,
            'max_orders' => null, // Ilimitado
            'is_active' => true,
        ]);
    }
}