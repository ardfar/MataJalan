<?php

namespace App\Notifications;

use App\Models\Rating;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewRatingPending extends Notification implements ShouldQueue
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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Rating Pending Verification')
            ->line('A new rating has been submitted for vehicle ' . $this->rating->vehicle->plate_number)
            ->line('Rating: ' . $this->rating->rating)
            ->line('Comment: ' . $this->rating->comment)
            ->action('Review Rating', route('admin.ratings.index', ['status' => 'pending']));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
