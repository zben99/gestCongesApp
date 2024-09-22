<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;
use App\Mail\JouissanceLetterMail;
use Illuminate\Support\Facades\Mail;

class SendJouissanceLetter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:jouissance-letter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoie la lettre de jouissance aux utilisateurs dont l\'anniversaire de la date d\'initialisation est aujourd\'hui';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Obtenir la date actuelle
        $today = Carbon::today();

        // Récupérer tous les utilisateurs pour qui aujourd'hui est l'anniversaire de la date d'initialisation
        $users = User::whereNotNull('arrival_date')
                    ->whereMonth('arrival_date', $today->month)
                    ->whereDay('arrival_date', $today->day)
                    ->get();

        foreach ($users as $user) {
            // Générer et envoyer la lettre de jouissance
            $this->sendEmailAlertWithConge($user);
        }

        $this->info('Lettres de jouissance envoyées avec succès.');
    }

    /**
     * Génère et envoie la lettre de jouissance à l'utilisateur.
     */
/**
 * Envoie un email avec le fichier PDF et un modèle d'email spécifique en fonction du nombre de jours de congé.
 */
private function sendEmailAlertWithConge(User $user)
{
    // Calcule les jours de congé restants
    $congeRestant = $this->calculateCongeRestant($user);

    // Choisit le bon template d'email en fonction des jours de congé restants
    if ($congeRestant >= 60) {
        $template = 'emails.conge_60jours'; // Email spécifique pour >= 60 jours de congé
        $subject = 'Félicitations ! Vous avez accumulé 60 jours de congé';
    } elseif ($congeRestant >= 30) {
        $template = 'emails.conge_30jours'; // Email spécifique pour >= 30 jours de congé
        $subject = 'Vous avez accumulé 30 jours de congé';
    } else {
        $template = 'emails.default_conge'; // Email par défaut si < 30 jours
        $subject = 'Votre situation de congé';
    }

    // Génère le PDF et l'attache à l'email
    $pdfFilePath = $this->generatePDF($user);

    // Envoi de l'email avec le PDF en pièce jointe
    try {
        Mail::send($template, ['employee' => $user], function ($mail) use ($user, $subject, $pdfFilePath) {
            $mail->to($user->email)
                 ->subject($subject)
                 ->attach(storage_path('app/public/' . $pdfFilePath), [
                     'as' => 'lettre_jouissance.pdf',
                     'mime' => 'application/pdf',
                 ]);
        });

        return true; // L'email a été envoyé avec succès
    } catch (\Exception $e) {
        // Gérer l'erreur d'envoi
        \Log::error('Erreur lors de l\'envoi de l\'email à ' . $user->email . ': ' . $e->getMessage());
        return false; // Envoi échoué
    }
}

    /**
     * Génère un fichier PDF pour la lettre de jouissance.
     */
private function generatePDF(User $user)
{
    try {
        // Tester le contenu HTML avant de générer le PDF
        $htmlContent = view('emails.lettredejouissance', ['employee' => $user])->render();

        // Crée une instance de Mpdf
        $mpdf = new \Mpdf\Mpdf();

        // Génère le PDF avec le contenu HTML
        $mpdf->WriteHTML($htmlContent);

        // Nom et chemin du fichier PDF
        $pdfFileName = 'lettredejouissance_' . $user->id . '_' . time() . '.pdf';
        $pdfFilePath = 'pdfs/' . $pdfFileName;

        // Sauvegarde le PDF dans le répertoire 'storage/app/public/pdfs'
        \Storage::put('public/' . $pdfFilePath, $mpdf->Output('', 'S'));

        if (\Storage::exists('public/' . $pdfFilePath)) {
            // Met à jour l'utilisateur avec le chemin du PDF
            $user->update(['jouissance_pdf' => $pdfFilePath]);

            return $pdfFilePath;  // Retourne le chemin d'accès au fichier PDF
        } else {
            return response()->json(['error' => 'Erreur lors de la génération du fichier PDF.'], 500);
        }
    } catch (\Exception $e) {
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
