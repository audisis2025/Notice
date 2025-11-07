<?php

namespace Database\Seeders;

// ¡SE ME OLVIDÓ ESTE IMPORT EN EL PASO ANTERIOR!
use Illuminate\Database\Console\Seeds\WithoutModelEvents; // <-- Esta línea ya la tienes
use Illuminate\Database\Seeder;

// --- 1. IMPORTAMOS EL MODELO DE NEGOCIO ---
// Le dice a Laravel qué es un "Business"
use App\Models\Business;

// --- 2. IMPORTAMOS LA CLASE 'DB' (Base de Datos) ---
// La usaremos para borrar datos antiguos
use Illuminate\Support\Facades\DB;


class BusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // --- 3. BORRAMOS DATOS ANTIGUOS ---
        // Esto es para que si corres el 'seeder' 10 veces,
        // no tengas 30 negocios, sino que siempre tengas solo 3.
        DB::table('businesses')->delete();

        // --- 4. INSERTAMOS LOS 3 NEGOCIOS DE PRUEBA ---
        Business::create([
            'name' => 'Tintorería "El Sol"',
            'description' => 'Expertos en cuidado de prendas delicadas. Tu ropa lista en 24 horas.',
            'imageUrl' => 'https://picsum.photos/seed/tintoreria/400/200'
        ]);

        Business::create([
            'name' => 'Reparación de Calzado "Zapatero Veloz"',
            'description' => 'Reparamos cualquier tipo de calzado, bolsas y cinturones.',
            'imageUrl' => 'https://picsum.photos/seed/calzado/400/200'
        ]);

        Business::create([
            'name' => 'Lavandería "Burbujas"',
            'description' => 'Servicio de lavado y secado por kilo. Ahorra tiempo y dinero.',
            'imageUrl' => 'https://picsum.photos/seed/lavanderia/400/200'
        ]);
    }
}
