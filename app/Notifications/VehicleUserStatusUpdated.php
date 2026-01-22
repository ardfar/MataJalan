<?php

namespace App\Notifications;

use App\Models\VehicleUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VehicleUserStatusUpdated extends Notification
{
    use Queueable;

    protected $vehicleUser;

    /**
     * Create a new notification instance.
     */
    public function __construct(VehicleUser $vehicleUser)
    {
        $this->vehicleUser = $vehicleUser;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database']; // Using database as well for in-app notifications if needed
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $status = strtoupper($this->vehicleUser->status);
        $plate = $this->vehicleUser->vehicle->plate_number;
        
        $mail = (new MailMessage)
                    ->subject("Vehicle User Request {$status}: {$plate}")
                    ->line("Your request to be registered as a {$this->vehicleUser->role_type} user for vehicle {$plate} has been {$this->vehicleUser->status}.");

        if ($this->vehicleUser->status === 'rejected' && $this->vehicleUser->rejection_reason) {
            $mail->line("Reason: {$this->vehicleUser->rejection_reason}");
        }

        $mail->action('View Vehicle', route('vehicle.show', $this->vehicleUser->vehicle->uuid));

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'vehicle_user_id' => $this->vehicleUser->id,
            'status' => $this->vehicleUser->status,
            'vehicle_plate' => $this->vehicleUser->vehicle->plate_number,
            'message' => "Your request for {$this->vehicleUser->vehicle->plate_number} was {$this->vehicleUser->status}.",
        ];
    }
}
