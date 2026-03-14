<?php

namespace App\Notifications;

use App\Models\Support\SupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SupportTicketReplyNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly SupportTicket $ticket,
        public readonly bool $isAdminReply = false,
    ) {}

    public function via(mixed $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        $from = $this->isAdminReply ? 'l\'équipe support' : 'le client';

        return (new MailMessage)
            ->subject('Nouvelle réponse sur le ticket ' . $this->ticket->ticket_number)
            ->greeting('Bonjour,')
            ->line('Une nouvelle réponse a été ajoutée par ' . $from . ' sur le ticket :')
            ->line('N° Ticket : ' . $this->ticket->ticket_number)
            ->line('Sujet : ' . $this->ticket->subject)
            ->salutation('Cordialement');
    }

    public function toArray(mixed $notifiable): array
    {
        return [
            'title' => $this->isAdminReply
                ? 'Réponse du support'
                : 'Nouvelle réponse client',
            'message' => $this->ticket->ticket_number . ' — ' . $this->ticket->subject,
            'color' => $this->isAdminReply ? 'info' : 'primary',
            'icon' => 'message-text',
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'is_admin_reply' => $this->isAdminReply,
        ];
    }
}
