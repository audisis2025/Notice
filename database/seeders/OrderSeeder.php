<?php
/**
 * Nombre de la clase           : OrderSeeder
 * Descripción de la clase      : Seeder que crea órdenes de prueba con todos
 *                                los estados posibles (pending, paid, ready, delivered, cancelled)
 * Fecha de creación            : 13/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 13/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 1.0
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Business;
use App\Models\User;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los negocios
        $businesses = Business::all();

        if ($businesses->isEmpty()) {
            $this->command->warn('No hay negocios registrados. Crea negocios primero.');
            return;
        }

        // Obtener usuarios MobileUser para asociar a las órdenes
        $mobileUsers = User::where('role', 'MobileUser')->get();

        if ($mobileUsers->isEmpty()) {
            $this->command->warn('No hay usuarios móviles. Creando usuarios de prueba...');
            $mobileUsers = collect([
                User::create([
                    'name' => 'Usuario Móvil 1',
                    'email' => 'movil1@test.com',
                    'phone' => '5551234567',
                    'password' => bcrypt('password'),
                    'role' => 'MobileUser',
                    'is_active' => true,
                ]),
                User::create([
                    'name' => 'Usuario Móvil 2',
                    'email' => 'movil2@test.com',
                    'phone' => '5557654321',
                    'password' => bcrypt('password'),
                    'role' => 'MobileUser',
                    'is_active' => true,
                ]),
            ]);
        }

        foreach ($businesses as $business) {
            $this->command->info("Creando órdenes para: {$business->business_name}");

            // 1. ORDEN PENDIENTE (pending)
            Order::create([
                'order_number' => $this->generateOrderNumber(),
                'business_id' => $business->id,
                'user_id' => null, // Aún no asociada
                'description' => 'Orden de prueba en estado pendiente - Lavado de ropa',
                'amount' => 150.00,
                'status' => 'pending',
                'qr_code' => 'qr_codes/' . Str::random(20) . '.png',
                'qr_delivery_code' => null,
                'paid_at' => null,
                'ready_at' => null,
                'delivered_at' => null,
                'cancelled_at' => null,
                'cancellation_reason' => null,
                'associated_at' => null,
                'chat_enabled' => false,
                'created_at' => now()->subDays(2),
            ]);

            // 2. ORDEN PAGADA (paid)
            Order::create([
                'order_number' => $this->generateOrderNumber(),
                'business_id' => $business->id,
                'user_id' => $mobileUsers->random()->id,
                'description' => 'Orden de prueba pagada - Tintorería express',
                'amount' => 280.50,
                'status' => 'paid',
                'qr_code' => 'qr_codes/' . Str::random(20) . '.png',
                'qr_delivery_code' => null,
                'paid_at' => now()->subHours(12),
                'ready_at' => null,
                'delivered_at' => null,
                'cancelled_at' => null,
                'cancellation_reason' => null,
                'associated_at' => now()->subDays(1),
                'chat_enabled' => false,
                'created_at' => now()->subDays(1),
            ]);

            // 3. ORDEN LISTA (ready)
            Order::create([
                'order_number' => $this->generateOrderNumber(),
                'business_id' => $business->id,
                'user_id' => $mobileUsers->random()->id,
                'description' => 'Orden de prueba lista para entrega - Planchado y doblado',
                'amount' => 95.00,
                'status' => 'ready',
                'qr_code' => 'qr_codes/' . Str::random(20) . '.png',
                'qr_delivery_code' => 'qr_delivery/' . Str::random(20) . '.png',
                'paid_at' => now()->subHours(24),
                'ready_at' => now()->subHours(2),
                'delivered_at' => null,
                'cancelled_at' => null,
                'cancellation_reason' => null,
                'associated_at' => now()->subDays(2),
                'chat_enabled' => false,
                'created_at' => now()->subDays(2),
            ]);

            // 4. ORDEN ENTREGADA (delivered)
            Order::create([
                'order_number' => $this->generateOrderNumber(),
                'business_id' => $business->id,
                'user_id' => $mobileUsers->random()->id,
                'description' => 'Orden de prueba entregada - Limpieza de edredones',
                'amount' => 450.00,
                'status' => 'delivered',
                'qr_code' => 'qr_codes/' . Str::random(20) . '.png',
                'qr_delivery_code' => 'qr_delivery/' . Str::random(20) . '.png',
                'paid_at' => now()->subDays(5),
                'ready_at' => now()->subDays(3),
                'delivered_at' => now()->subDays(2),
                'cancelled_at' => null,
                'cancellation_reason' => null,
                'associated_at' => now()->subDays(6),
                'chat_enabled' => false,
                'created_at' => now()->subDays(6),
            ]);

            // 5. ORDEN CANCELADA (cancelled)
            Order::create([
                'order_number' => $this->generateOrderNumber(),
                'business_id' => $business->id,
                'user_id' => $mobileUsers->random()->id,
                'description' => 'Orden de prueba cancelada - Lavado de cobijas',
                'amount' => 200.00,
                'status' => 'cancelled',
                'qr_code' => 'qr_codes/' . Str::random(20) . '.png',
                'qr_delivery_code' => null,
                'paid_at' => null,
                'ready_at' => null,
                'delivered_at' => null,
                'cancelled_at' => now()->subHours(6),
                'cancellation_reason' => 'Cliente solicitó cancelación por cambio de planes',
                'associated_at' => now()->subHours(12),
                'chat_enabled' => false,
                'created_at' => now()->subHours(12),
            ]);

            // 6-10. ÓRDENES ADICIONALES CON DIFERENTES ESTADOS
            // Más órdenes entregadas (para estadísticas)
            for ($i = 1; $i <= 5; $i++) {
                Order::create([
                    'order_number' => $this->generateOrderNumber(),
                    'business_id' => $business->id,
                    'user_id' => $mobileUsers->random()->id,
                    'description' => "Orden entregada #{$i} - Servicio completo de lavandería",
                    'amount' => rand(100, 500),
                    'status' => 'delivered',
                    'qr_code' => 'qr_codes/' . Str::random(20) . '.png',
                    'qr_delivery_code' => 'qr_delivery/' . Str::random(20) . '.png',
                    'paid_at' => now()->subDays(rand(7, 30)),
                    'ready_at' => now()->subDays(rand(5, 28)),
                    'delivered_at' => now()->subDays(rand(3, 25)),
                    'cancelled_at' => null,
                    'cancellation_reason' => null,
                    'associated_at' => now()->subDays(rand(8, 35)),
                    'chat_enabled' => false,
                    'created_at' => now()->subDays(rand(8, 35)),
                ]);
            }

            // Órdenes pendientes adicionales
            for ($i = 1; $i <= 3; $i++) {
                Order::create([
                    'order_number' => $this->generateOrderNumber(),
                    'business_id' => $business->id,
                    'user_id' => null,
                    'description' => "Orden pendiente #{$i} - En espera de pago",
                    'amount' => rand(50, 300),
                    'status' => 'pending',
                    'qr_code' => 'qr_codes/' . Str::random(20) . '.png',
                    'qr_delivery_code' => null,
                    'paid_at' => null,
                    'ready_at' => null,
                    'delivered_at' => null,
                    'cancelled_at' => null,
                    'cancellation_reason' => null,
                    'associated_at' => null,
                    'chat_enabled' => false,
                    'created_at' => now()->subHours(rand(1, 48)),
                ]);
            }

            $this->command->info("✓ Creadas 13 órdenes para {$business->business_name}");
        }

        $this->command->info('✓ OrderSeeder completado exitosamente');
    }

    /**
     * Genera un número de orden único.
     *
     * @return string
     */
    private function generateOrderNumber(): string
    {
        return 'ORD-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4));
    }
}