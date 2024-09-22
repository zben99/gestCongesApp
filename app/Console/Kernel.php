<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Enregistre la commande pour l'envoi de la lettre de jouissance
        Commands\sendEmailAlertWithConge::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     */
    protected function schedule(Schedule $schedule)
    {
        // Planifie l'exécution de la commande tous les jours à minuit
        $schedule->command('send:jouissance-letter')->daily();
    }

    /**
     * Register the commands for the application.
     */

}
