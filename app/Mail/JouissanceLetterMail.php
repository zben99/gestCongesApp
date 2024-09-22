<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
class JouissanceLetterMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user; // Instance du modèle User
    public $pdfFilePath; // Chemin du fichier PDF

    /**
     * Create a new message instance.
     *
     * @param  User  $user
     * @param  string  $pdfFilePath
     */
    public function __construct(User $user, $pdfFilePath)
    {
        $this->user = $user;
        $this->pdfFilePath = $pdfFilePath;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.lettredejouissance')
                    ->with(['employee' => $this->user]) // Passer l'utilisateur à la vue
                    ->attach(storage_path('app/public/' . $this->pdfFilePath), [
                        'as' => 'lettre_de_jouissance.pdf',
                        'mime' => 'application/pdf',
                    ])
                    ->subject('Votre lettre de jouissance annuelle'); // Sujet de l'email
    }
}
