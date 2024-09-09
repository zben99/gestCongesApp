<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CongeAlertService;

class SendCongeAlerts extends Command
{
    protected $signature = 'send:conge-alerts';
    protected $description = 'Envoyer des alertes de congé aux utilisateurs';

    protected $congeAlertService;

    public function __construct(CongeAlertService $congeAlertService)
    {
        parent::__construct();
        $this->congeAlertService = $congeAlertService;
    }

    public function handle()
    {
        $this->congeAlertService->sendAlerts();
        $this->info('Alertes de congé envoyées avec succès!');
    }
}
