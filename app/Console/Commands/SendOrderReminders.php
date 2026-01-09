<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\OrderReminderService;

class SendOrderReminders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'orders:send-reminders';

    /**
     * The console command description.
     */
    protected $description = 'Envía recordatorios de órdenes programados';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Enviando recordatorios de órdenes...');

        $orderReminderService = app(OrderReminderService::class);
        $sentReminders = $orderReminderService->sendPendingReminders();

        $this->info("Se enviaron {$sentReminders} recordatorios.");

        return Command::SUCCESS;
    }
}