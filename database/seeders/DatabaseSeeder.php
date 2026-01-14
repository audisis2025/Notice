<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ejecutar todos los seeders en orden
        $this->call([
            UserSeeder::class,
            PackageSeeder::class,
            SystemSettingSeeder::class,
            BusinessSeeder::class,
            CouponSeeder::class,
            BusinessPackageSeeder::class,
            //OrderSeeder::class,

        ]);

        // También puedes mantener el usuario de prueba como backup
        // User::firstOrCreate(
        //     ['email' => 'test@example.com'],
        //     [
        //         'name' => 'Test User',
        //         'password' => 'password',
        //         'email_verified_at' => now(),
        //     ]
        // );
    }
}
