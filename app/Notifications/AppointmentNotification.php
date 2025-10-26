<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentNotification extends Notification
{
    use Queueable;

    protected $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable)
    {
        return ['database']; // Store in notifications table
    }

    public function toArray($notifiable)
    {
        $research = $this->appointment->researchTitle;

        return [
            'message' => 'Your research "' . $research->Study_Protocol_title . '" has been scheduled for initial review on ' .
                         \Carbon\Carbon::parse($this->appointment->appointment_date)->format('F j, Y, g:i A') . '.',
            'research_id' => $research->id,
            'appointment_date' => $this->appointment->appointment_date,
        ];
    }
}
