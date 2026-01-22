<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VehicleEditSubmitted extends Notification
{
    use Queueable;

    public $edit;

    /**
     * Create a new notification instance.
     */
    public function __construct($edit)
    {
        $this->edit = $edit;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Vehicle Edit Request')
            ->line('A new edit request has been submitted for vehicle ' . $this->edit->vehicle->plate_number)
            ->action('Review Request', route('admin.vehicle-edits.index'))
            ->line('Please review the pending changes in the admin dashboard.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'edit_id' => $this->edit->id,
            'vehicle_id' => $this->edit->vehicle_id,
            'status' => 'pending'
        ];
    }
}
