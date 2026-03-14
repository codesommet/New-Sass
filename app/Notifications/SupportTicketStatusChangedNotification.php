<?php

namespace App\Notifications;

use App\Models\Support\SupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SupportTicketStatusChangedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly SupportTicket $ticket,
        public readonly string $newStatus,
    ) {}

    public function via(mixed $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        $statusLabels = [
            'open' => 'Ouvert',
            'in_progress' => 'En cours',
            'on_hold' => 'En attente',
            'resolved' => 'Résolu',
            'closed' => 'Fermé',
        ];

        $label = $statusLabels[$this->newStatus] ?? $this->newStatus;

        return (new MailMessage)
            ->subject('Ticket ' . $this->ticket->ticket_number . ' — Statut mis à jour')
            ->greeting('Bonjour,')
            ->line('Le statut de votre ticket a été mis à jour.')
            ->line('N° Ticket : ' . $this->ticket->ticket_number)
            ->line('Sujet : ' . $this->ticket->subject)
            ->line('Nouveau statut : ' . $label)
            ->salutation('Cordialement');
    }

    public function toArray(mixed $notifiable): array
    {
        $statusLabels = [
            'open' => 'Ouvert',
            'in_progress' => 'En cours',
            'on_hold' => 'En attente',
            'resolved' => 'Résolu',
            'closed' => 'Fermé',
        ];

        $colorMap = [
            'open' => 'info',
            'in_progress' => 'primary',
            'on_hold' => 'warning',
            'resolved' => 'success',
            'closed' => 'secondary',
        ];

        return [
            'title' => 'Statut du ticket mis à jour',
            'message' => $this->ticket->ticket_number . ' — ' . ($statusLabels[$this->newStatus] ?? $this->newStatus),
            'color' => $colorMap[$this->newStatus] ?? 'info',
            'icon' => 'refresh-circle',
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'status' => $this->newStatus,
        ];
    }
}
