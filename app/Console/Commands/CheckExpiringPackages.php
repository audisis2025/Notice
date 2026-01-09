<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BusinessPackageService;

class CheckExpiringPackages extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'packages:check-expiring';

    /**
     * The console command description.
     */
    protected $description = 'Verifica y notifica paquetes por vencer';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Verificando paquetes por vencer...');

        $businessPackageService = app(BusinessPackageService::class);
        $expiringPackages = $businessPackageService->checkExpiringPackages();

        $this->info("Se encontraron {$expiringPackages->count()} paquetes por vencer.");
        $this->info('Notificaciones enviadas exitosamente.');

        return Command::SUCCESS;
    }
}