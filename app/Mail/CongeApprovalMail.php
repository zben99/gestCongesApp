<?php

namespace App\Mail;

use App\Models\Conges;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CongeApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public $conge;
    public $pdfPath;

    /**
     * Create a new message instance.
     *
     * @param Conges $conge
     * @param string $pdfPath
     */
    public function __construct(Conges $conge, $pdfPath)
    {
        $this->conge = $conge;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Validation de votre congé')
                    ->view('emails.conge_approval')  // Vue de l'email
                    ->attach($this->pdfPath, [
                        'as' => 'document_conge.pdf',  // Nom du fichier attaché
                        'mime' => 'application/pdf',
                    ]);
    }
}
