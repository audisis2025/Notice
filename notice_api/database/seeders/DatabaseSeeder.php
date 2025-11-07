<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- ¡ESTA ES LA PARTE IMPORTANTE! ---
        // Le decimos a Laravel que, cuando corramos 'db:seed',
        // debe ejecutar la clase 'BusinessSeeder'.
        $this->call([
            BusinessSeeder::class,
            // Aquí podrías añadir 'OrderSeeder::class', etc. en el futuro
        ]);
    }
}