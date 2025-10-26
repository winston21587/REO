<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TitleStatusUpdated extends Notification
{
    use Queueable;

    protected $title;
    protected $status;
    protected $appointedAt;
    protected $reason;

    public function __construct($title, $status, $appointedAt = null, $reason = null)
    {
        $this->title = $title;
        $this->status = $status;
        $this->appointedAt = $appointedAt;
        $this->reason = $reason;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        // we'll use DB and mail channels
        return ['database', 'mail'];
    }
    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Research Title Status Update')
            ->greeting('Hello ' . ($notifiable->first_name ?? ''))
            ->line("Your submission \"{$this->title->Study_Protocol_title}\" status has changed to: {$this->status}.");

        if ($this->appointedAt) {
            $mail->line('Appointed Date: ' . $this->appointedAt->format('F j, Y, g:i A'));
        }

        if ($this->reason) {
            $mail->line('Reason: ' . $this->reason);
        }

        $mail->action('View submission', url('/user/submissions/' . $this->title->id));

        return $mail;
    }

        public function toDatabase($notifiable)
    {
        return [
            'title_id' => $this->title->id,
            'title' => $this->title->Study_Protocol_title,
            'status' => $this->status,
            'appointed_at' => $this->appointedAt ? $this->appointedAt->toDateTimeString() : null,
            'reason' => $this->reason,
        ];
    }
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */

}
