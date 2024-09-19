<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Mpdf\Mpdf;

class CongeAlertService
{
    public function sendAlerts()
    {
        // Récupère les utilisateurs ayant potentiellement des congés à surveiller.
        $users = User::all(); // Filtrez les utilisateurs en fonction des conditions métier si nécessaire.

        foreach ($users as $user) {
            // Calcul des jours de congé restants
            $congeRestant = $this->calculateCongeRestant($user);

            // Vérifie et envoie les alertes si les conditions sont remplies
            $this->checkAndSendAlert($user, $congeRestant);
        }
    }

    /**
     * Vérifie si une alerte doit être envoyée en fonction du nombre de jours restants.
     */
    private function checkAndSendAlert(User $user, $congeRestant)
    {
        if ($congeRestant >= 30 && $congeRestant < 60) {
            $this->sendEmailAlert($user, 'emails.conge_30jours', "Alerte de congé");
        } elseif ($congeRestant >= 60) {
            $this->sendEmailAlert($user, 'emails.conge_60jours', "Alerte importante de congé");
        }
    }

    /**
     * Envoie un email d'alerte avec un fichier PDF en pièce jointe.
     */
    private function sendEmailAlert(User $user, $template, $subject)
    {
        try {
            // Génère le PDF à partir d'une vue Blade
            $pdfContent = $this->generatePDF($user);

            Mail::send($template, ['employee' => $user], function ($mail) use ($user, $subject, $pdfContent) {
                $mail->to($user->email)
                     ->subject($subject)
                     ->attachData($pdfContent, 'document_conge.pdf', [
                         'mime' => 'application/pdf',
                     ]);
            });

            return true; // Envoi réussi
        } catch (\Exception $e) {
            // Enregistre l'erreur dans les logs
            \Log::error('Erreur lors de l\'envoi de l\'email à ' . $user->email . ': ' . $e->getMessage());
            return false; // Envoi échoué
        }
    }

    /**
     * Génère un fichier PDF basé sur une vue Blade.
     */
    private function generatePDF(User $user)
    {
        // Crée une instance de Mpdf
        $mpdf = new Mpdf();

        // Crée le contenu HTML à partir de la vue Blade
        $htmlContent = view('emails.lettredejouissance', ['employee' => $user])->render();

        // Génère le PDF avec le contenu HTML
        $mpdf->WriteHTML($htmlContent);

        // Retourne le PDF sous forme de chaîne
        return $mpdf->Output('', 'S');
    }

    /**
     * Calcule les jours de congé restants pour un utilisateur.
     */
    private function calculateCongeRestant(User $user)
    {
        // Calcule la différence entre aujourd'hui et la date d'initialisation des congés de l'utilisateur
        $days = (new \DateTime(now()))->diff(new \DateTime($user->initialization_date))->days + 1;

        // Calcule les congés acquis en fonction des règles (2.5 jours par mois)
        $nbreConge = ($days * 2.5) / 30;

        // Retourne le nombre de congés restants
        return floor($nbreConge + $user->initial + $user->joursBonus - $user->pris);
    }
}
