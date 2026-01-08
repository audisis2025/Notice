<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Business;
use App\Models\User;

class BusinessSeeder extends Seeder
{
    /**
     * Ejecuta el seeder para crear negocios de prueba.
     * 
     * Crea negocios de ejemplo asociados a usuarios administradores
     * para facilitar el desarrollo y testing.
     *
     * @return void
     */
    public function run(): void
    {
        // Obtener usuario administrador de negocio
        $businessUser = User::where('role', 'BusinessAdministrator')->first();

        if ($businessUser) {
            // Negocio principal de prueba
            Business::create([
                'user_id' => $businessUser->id,
                'business_name' => 'Lavandería Express',
                'legal_name' => 'Lavandería Express S.A. de C.V.',
                'tax_id' => 'LEX850614HF3',
                'address' => 'Av. Insurgentes Sur 1234',
                'city' => 'Ciudad de México',
                'state' => 'Ciudad de México',
                'country' => 'México',
                'postal_code' => '03100',
                'phone' => '+5215587654321',
                'email' => 'contacto@lavanderiaexpress.com',
                'website' => 'https://lavanderiaexpress.com',
                'description' => 'Servicio de lavandería profesional con entrega a domicilio',
                'latitude' => 19.3689,
                'longitude' => -99.1764,
                'can_be_rated' => true,
                'delivery_period_minutes' => 120,
                'is_active' => true,
            ]);

            // Segundo negocio de prueba
            Business::create([
                'user_id' => $businessUser->id,
                'business_name' => 'Zapatería El Buen Paso',
                'legal_name' => 'Zapatería El Buen Paso S. de R.L.',
                'tax_id' => 'ZBP920815MX8',
                'address' => 'Calle Reforma 456',
                'city' => 'Toluca',
                'state' => 'Estado de México',
                'country' => 'México',
                'postal_code' => '50000',
                'phone' => '+5217221234567',
                'email' => 'info@elbuenpaso.com',
                'website' => null,
                'description' => 'Reparación y cuidado de calzado con más de 30 años de experiencia',
                'latitude' => 19.2827,
                'longitude' => -99.6557,
                'can_be_rated' => true,
                'delivery_period_minutes' => 1440, // 24 horas
                'is_active' => true,
            ]);
        }
    }
}