<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CongesAlert extends Mailable
{
    use Queueable, SerializesModels;

    protected $employe;

    public function __construct($employe)
    {
        $this->employe = $employe;
    }

    public function build()
    {
        return $this->view('emails.conges_alert')
                    ->with('employe', $this->employe);
    }
}
