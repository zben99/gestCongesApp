<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Absence;
use Carbon\Carbon;

class AbsenceStatusNotification extends Notification
{
    use Queueable;

    protected $absence;
    protected $status;

    /**
     * Crée une nouvelle notification instance.
     *
     * @return void
     */
    public function __construct(Absence $absence, $status)
    {
        $this->absence = $absence;
        $this->status = $status;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail message.
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Mise à jour de votre demande d\'absence')
                    ->line('Votre demande d\'absence du ' . Carbon::parse($this->absence->dateDebut)->format('d/m/Y') . ' au ' . Carbon::parse($this->absence->dateFin)->format('d/m/Y') . ' a été ' . $this->status .' par votre manager' . '.')
                    ->action('Voir les détails', url('/absences/' . $this->absence->id))
                    ->line('Merci d\'utiliser notre application!');
    }
}
