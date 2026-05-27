<?php

namespace App\Events;

use App\Models\Meeting;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MeetingProcessingUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Meeting $meeting,
        public string $message,
    ) {
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('meeting.'.$this->meeting->id);
    }

    public function broadcastAs(): string
    {
        return 'meeting.processing.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->meeting->id,
            'status' => $this->meeting->status,
            'message' => $this->message,
            'processed_at' => optional($this->meeting->processed_at)?->toIso8601String(),
            'failed_at' => optional($this->meeting->failed_at)?->toIso8601String(),
        ];
    }
}
