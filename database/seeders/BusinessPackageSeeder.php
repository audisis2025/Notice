<?php

/**
 * Nombre de la clase           : BusinessPackageSeeder
 * Descripción de la clase      : Seeder que asocia negocios con paquetes activos
 *                                y genera pagos correspondientes
 * Fecha de creación            : 13/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 13/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 1.0
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Business;
use App\Models\Package;
use App\Models\BusinessPackage;
use App\Models\Payment;
use Illuminate\Support\Str;

class BusinessPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los negocios
        $businesses = Business::all();

        if ($businesses->isEmpty()) {
            $this->command->warn('No hay negocios registrados. Ejecuta BusinessSeeder primero.');
            return;
        }

        // Obtener todos los paquetes activos
        $packages = Package::where('is_active', true)->get();

        if ($packages->isEmpty()) {
            $this->command->warn('No hay paquetes activos. Ejecuta PackageSeeder primero.');
            return;
        }

        foreach ($businesses as $business) {
            $this->command->info("Asignando paquete a: {$business->business_name}");

            // Seleccionar un paquete aleatorio
            $package = $packages->random();

            // Crear la suscripción al paquete
            $businessPackage = BusinessPackage::create([
                'business_id' => $business->id,
                'package_id' => $package->id,
                'start_date' => now()->subDays(rand(1, 15)), // Iniciado hace algunos días
                'end_date' => now()->addDays($package->duration_days - rand(1, 15)), // Vigente
                'price_paid' => $package->price,
                'status' => 'active',
                'created_at' => now()->subDays(rand(1, 15)),
            ]);

            $this->command->info("✓ Paquete '{$package->name}' asignado");

            // Crear el pago correspondiente
            $payment = Payment::create([
                'business_id' => $business->id,
                'business_package_id' => $businessPackage->id,
                'payment_method' => $this->getRandomPaymentMethod(),
                'card_last_four' => rand(1000, 9999),
                'card_brand' => $this->getRandomCardBrand(),
                'amount' => $package->price,
                'status' => 'completed',
                'transaction_id' => 'TXN-' . strtoupper(Str::random(16)),
                'created_at' => $businessPackage->created_at,
            ]);

            $this->command->info("✓ Pago registrado: $" . number_format($payment->amount, 2));

            // Opcional: Crear historial de paquetes anteriores (expirados)
            if (rand(0, 1)) {
                $oldPackage = $packages->random();
                $oldBusinessPackage = BusinessPackage::create([
                    'business_id' => $business->id,
                    'package_id' => $oldPackage->id,
                    'start_date' => now()->subDays(rand(60, 90)),
                    'end_date' => now()->subDays(rand(30, 50)), // Expirado
                    'price_paid' => $oldPackage->price,
                    'status' => 'expired',
                    'created_at' => now()->subDays(rand(60, 90)),
                ]);

                Payment::create([
                    'business_id' => $business->id,
                    'business_package_id' => $oldBusinessPackage->id,
                    'payment_method' => $this->getRandomPaymentMethod(),
                    'card_last_four' => rand(1000, 9999),
                    'card_brand' => $this->getRandomCardBrand(),
                    'amount' => $oldPackage->price,
                    'status' => 'completed',
                    'transaction_id' => 'TXN-' . strtoupper(Str::random(16)),
                    'created_at' => $oldBusinessPackage->created_at,
                ]);

                $this->command->info("✓ Paquete anterior expirado creado");
            }

            $this->command->line('');
        }

        $this->command->info('✓ BusinessPackageSeeder completado exitosamente');
        $this->command->line('');
        $this->command->table(
            ['Negocio', 'Paquete Actual', 'Estado', 'Expira'],
            BusinessPackage::with(['business', 'package'])
                ->where('status', 'active')
                ->get()
                ->map(function ($bp) {
                    return [
                        $bp->business->business_name,
                        $bp->package->name,
                        $bp->status,
                        $bp->end_date->format('d/m/Y'),
                    ];
                })
        );
    }

    /**
     * Obtiene un método de pago aleatorio.
     *
     * @return string
     */
    private function getRandomPaymentMethod(): string
    {
        $methods = ['credit_card', 'debit_card', 'paypal', 'transfer'];
        return $methods[array_rand($methods)];
    }

    /**
     * Obtiene una marca de tarjeta aleatoria.
     *
     * @return string
     */
    private function getRandomCardBrand(): string
    {
        $brands = ['Visa', 'Mastercard', 'American Express', 'Discover'];
        return $brands[array_rand($brands)];
    }
}
