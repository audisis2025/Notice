<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    /**
     * Ejecuta el seeder para crear cupones de prueba.
     * 
     * Crea cupones de descuento para testing del sistema de promociones.
     *
     * @return void
     */
    public function run(): void
    {
        // Cupón de bienvenida
        Coupon::create([
            'code' => 'BIENVENIDO',
            'discount_percentage' => 20.00,
            'expiration_date' => Carbon::now()->addDays(30),
            'is_used' => false,
            'is_active' => true,
        ]);

        // Cupón de descuento alto
        Coupon::create([
            'code' => 'PROMO50',
            'discount_percentage' => 50.00,
            'expiration_date' => Carbon::now()->addDays(15),
            'is_used' => false,
            'is_active' => true,
        ]);

        // Cupón vencido (para testing)
        Coupon::create([
            'code' => 'VENCIDO',
            'discount_percentage' => 30.00,
            'expiration_date' => Carbon::now()->subDays(10),
            'is_used' => false,
            'is_active' => false,
        ]);

        // Cupón ya usado (para testing)
        Coupon::create([
            'code' => 'USADO2024',
            'discount_percentage' => 25.00,
            'expiration_date' => Carbon::now()->addDays(20),
            'is_used' => true,
            'used_at' => Carbon::now()->subDays(5),
            'is_active' => true,
        ]);
    }
}
