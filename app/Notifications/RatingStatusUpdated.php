<?php

namespace App\Notifications;

use App\Models\Rating;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RatingStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public $rating;

    /**
     * Create a new notification instance.
     */
    public function __construct(Rating $rating)
    {
        $this->rating = $rating;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $status = ucfirst($this->rating->status);
        $message = (new MailMessage)
            ->subject("Your Vehicle Rating was {$status}")
            ->line("Your rating for vehicle {$this->rating->vehicle->plate_number} has been {$this->rating->status}.");

        if ($this->rating->status === 'rejected' && $this->rating->rejection_reason) {
            $message->line("Reason: {$this->rating->rejection_reason}");
        }

        if ($this->rating->status === 'approved') {
            $message->action('View Rating', route('vehicle.show', $this->rating->vehicle->uuid));
        }

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'rating_id' => $this->rating->id,
            'status' => $this->rating->status,
            'vehicle_plate' => $this->rating->vehicle->plate_number,
            'rejection_reason' => $this->rating->rejection_reason,
        ];
    }
}
