<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VehicleEditStatusUpdated extends Notification
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
        $status = ucfirst($this->edit->status);
        $color = $this->edit->status === 'approved' ? 'success' : 'error';

        return (new MailMessage)
            ->subject("Vehicle Edit Request {$status}")
            ->line("Your edit request for vehicle {$this->edit->vehicle->plate_number} has been {$this->edit->status}.")
            ->line('Admin Comment: ' . ($this->edit->admin_comment ?? 'None'))
            ->action('View Vehicle', route('vehicle.show', $this->edit->vehicle->uuid));
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
            'status' => $this->edit->status
        ];
    }
}
