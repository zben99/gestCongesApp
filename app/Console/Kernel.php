<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        // Ajoutez ici la commande que vous avez créée
        Commands\SendCongeAlerts::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Appelle le service chaque mois pour envoyer les alertes de congé
        $schedule->call(function () {
            app(\App\Services\CongeAlertService::class)->sendAlerts();
        })->monthlyOn(28, '23:59'); // Ou choisissez une autre date et heure si nécessaire
    }
    
}
