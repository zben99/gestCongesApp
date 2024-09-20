<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
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
            // Génère le PDF et récupère le chemin du fichier PDF
            $pdfFilePath = $this->generatePDF($user);
    
            // Chemin complet du fichier PDF
            $pdfFullPath = storage_path('app/public/' . $pdfFilePath);
    
            // Vérifier si le fichier PDF existe
            if (!file_exists($pdfFullPath)) {
                \Log::error('Le fichier PDF n\'existe pas pour l\'utilisateur ' . $user->email);
                return false;
            }
    
            // Envoi de l'email avec le fichier PDF attaché
            Mail::send($template, ['employee' => $user], function ($mail) use ($user, $subject, $pdfFullPath) {
                $mail->to($user->email)
                     ->subject($subject)
                     ->attach($pdfFullPath, [
                         'as' => 'document_conge.pdf', // Nom du fichier joint
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
    try {
        // Crée une instance de Mpdf
        $mpdf = new Mpdf();

        // Crée le contenu HTML à partir de la vue Blade
        $htmlContent = view('emails.lettredejouissance', ['employee' => $user])->render();

        // Génère le PDF avec le contenu HTML
        $mpdf->WriteHTML($htmlContent);

        // Spécifie le nom du fichier PDF avec un nom unique
        $pdfFileName = 'lettredejouissance_' . $user->id . '_' . time() . '.pdf';
        
        // Spécifie le chemin de stockage dans 'storage/app/public/pdfs'
        $pdfFilePath = 'pdfs/' . $pdfFileName;

        // Sauvegarde le PDF dans le répertoire 'storage/app/public/pdfs' 
        Storage::put('public/' . $pdfFilePath, $mpdf->Output('', 'S'));

        // Vérifie si le fichier a bien été créé
        if (Storage::exists('public/' . $pdfFilePath)) {
            // Met à jour le champ 'jouissance_pdf' de l'utilisateur avec le chemin du PDF
            $user->update(['jouissance_pdf' => $pdfFilePath]);

            return $pdfFilePath;  // Retourne le chemin d'accès au fichier PDF
        } else {
            return response()->json(['error' => 'Erreur lors de la génération du fichier PDF.'], 500);
        }
    } catch (\Exception $e) {
        // Gérer les exceptions et afficher un message d'erreur en cas de problème
        return response()->json(['error' => 'Erreur : ' . $e->getMessage()], 500);
    }
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
