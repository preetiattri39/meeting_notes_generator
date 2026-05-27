<?php

namespace App\Notifications;

use App\Models\Meeting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MeetingSummaryReadyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Meeting $meeting,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Meeting summary ready')
            ->greeting('Meeting processing complete')
            ->line("Your summary for {$this->meeting->title} is ready.")
            ->action('Open meeting', route('meetings.show', $this->meeting))
            ->line('You can review transcript segments, action items, decisions, and highlights now.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'meeting_id' => $this->meeting->id,
            'meeting_title' => $this->meeting->title,
            'status' => $this->meeting->status,
            'url' => route('meetings.show', $this->meeting),
        ];
    }
}
