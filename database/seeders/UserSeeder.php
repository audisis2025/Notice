<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Ejecuta el seeder para crear usuarios iniciales.
     * 
     * Crea:
     * - SuperAdministrador del sistema
     * - Usuarios de prueba para desarrollo
     *
     * @return void
     */
    public function run(): void
    {
        // SuperAdministrador principal
        User::create([
            'name' => 'Super Administrador',
            'email' => 'admin@sisnotice.com',
            'phone' => '+5215512345678',
            'email_verified_at' => now(),
            'password' => Hash::make('Admin123!'),
            'role' => 'SuperAdministrator',
            'is_active' => true,
        ]);

        // Usuario administrador de negocio de prueba
        User::create([
            'name' => 'Juan Pérez',
            'email' => 'juan.perez@negocio.com',
            'phone' => '+5215587654321',
            'email_verified_at' => now(),
            'password' => Hash::make('Business123!'),
            'role' => 'BusinessAdministrator',
            'is_active' => true,
        ]);

        // Usuario móvil de prueba
        User::create([
            'name' => 'María González',
            'email' => 'maria.gonzalez@email.com',
            'phone' => '+5215598765432',
            'email_verified_at' => now(),
            'password' => Hash::make('Mobile123!'),
            'birth_date' => '1995-05-15',
            'role' => 'MobileUser',
            'is_active' => true,
        ]);

        // Usuario móvil sin email (caso real)
        User::create([
            'name' => 'Carlos Ramírez',
            'email' => 'carlosrz@email.com',
            'phone' => '+5215523456789',
            'password' => Hash::make('Mobile123!'),
            'birth_date' => null,
            'role' => 'MobileUser',
            'is_active' => true,
        ]);
    }
}