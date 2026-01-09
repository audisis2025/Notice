<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Verificar paquetes por vencer (diariamente a las 9:00 AM)
Schedule::command('packages:check-expiring')
    ->dailyAt('09:00')
    ->description('Verifica paquetes por vencer');

// Enviar recordatorios de órdenes (cada hora)
Schedule::command('orders:send-reminders')
    ->hourly()
    ->description('Envía recordatorios de órdenes');