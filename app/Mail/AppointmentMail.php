<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;

    /**
     * Create a new message instance.
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $research = $this->appointment->researchTitle;
        $user = $research->user;

        return $this->subject('Your Research Appointment for Initial Review')
                    ->view('emails.appointment') // Blade file we’ll make next
                    ->with([
                        'user' => $user,
                        'research' => $research,
                        'appointment' => $this->appointment,
                    ]);
    }
}
