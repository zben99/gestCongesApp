<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Mail;

class CongeAlertService
{
    public function sendAlerts()
    {
        $users = User::all(); // Obtenez tous les utilisateurs concernés

        foreach ($users as $user) {
            $congeRestant = $this->calculateCongeRestant($user); // Méthode fictive pour calculer les jours restants

            $this->checkAndSendAlert($user, $congeRestant);
        }
    }

    private function checkAndSendAlert(User $user, $congeRestant)
    {
        if ($congeRestant >= 30 && $congeRestant < 60) {
            $this->sendEmailAlert($user, 'emails.conge_30jours', "Alerte de congé");
        } elseif ($congeRestant >= 60) {
            $this->sendEmailAlert($user, 'emails.conge_60jours', "Alerte importante de congé");
        }
    }

    private function sendEmailAlert(User $user, $template, $subject)
    {
        try {
            Mail::send($template, ['employee' => $user], function ($mail) use ($user, $subject) {
                $mail->to($user->email)
                     ->subject($subject);
            });

            return true; // Envoi réussi
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi de l\'email à ' . $user->email . ': ' . $e->getMessage());
            return false; // Envoi échoué
        }
    }

    private function calculateCongeRestant(User $user)
    {
        // Ajoutez ici la logique pour calculer les jours restants de congé
        // Exemple :
        $days = (new \DateTime(now()))->diff(new \DateTime($user->initialization_date))->days + 1;
        $nbreConge = ($days * 2.5) / 30;
        return floor($nbreConge + $user->initial - $user->pris);
    }
}
