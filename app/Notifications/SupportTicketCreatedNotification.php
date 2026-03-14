<?php

namespace App\Notifications;

use App\Models\Support\SupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SupportTicketCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly SupportTicket $ticket,
    ) {}

    public function via(mixed $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouveau ticket de support — ' . $this->ticket->ticket_number)
            ->greeting('Bonjour,')
            ->line('Un nouveau ticket de support a été créé.')
            ->line('N° Ticket : ' . $this->ticket->ticket_number)
            ->line('Sujet : ' . $this->ticket->subject)
            ->line('Priorité : ' . $this->ticket->priority_label)
            ->line('Catégorie : ' . $this->ticket->category_label)
            ->salutation('Cordialement');
    }

    public function toArray(mixed $notifiable): array
    {
        return [
            'title' => 'Nouveau ticket de support',
            'message' => $this->ticket->ticket_number . ' — ' . $this->ticket->subject,
            'color' => 'warning',
            'icon' => 'message-question',
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
        ];
    }
}
