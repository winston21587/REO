<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Research_title;
use App\Models\Appointment;

class SubmissionAppointed extends Notification
{
    use Queueable;

    protected $submission;
    protected $appointment;

    public function __construct(Research_title $submission, Appointment $appointment)
    {
        $this->submission = $submission;
        $this->appointment = $appointment;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Research Title Has Been Appointed for Initial Review')
            ->greeting('Hello ' . $notifiable->first_name . ',')
            ->line('Your research titled "' . $this->submission->Study_Protocol_title . '" has been set for Initial Review.')
            ->line('Appointed Date: ' . $this->appointment->appointed_date->format('F j, Y'))
            ->line('Please prepare accordingly and wait for further instructions.')
            ->salutation('Thank you, REO Team');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Research Title Appointed for Initial Review',
            'message' => 'Your research titled "' . $this->submission->Study_Protocol_title . '" has been scheduled for Initial Review on ' . $this->appointment->appointed_date->format('F j, Y') . '.',
            'appointed_date' => $this->appointment->appointed_date
        ];
    }
}
