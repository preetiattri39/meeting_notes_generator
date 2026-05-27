<?php

namespace App\Livewire;

use App\Models\Meeting;
use Livewire\Component;

class MeetingStatusPanel extends Component
{
    public Meeting $meeting;

    public string $status = 'queued';

    public ?string $failureReason = null;

    public function mount(Meeting $meeting): void
    {
        $this->meeting = $meeting;
        $this->syncState();
    }

    public function refreshMeeting(): void
    {
        $this->meeting->refresh();
        $this->syncState();
    }

    protected function syncState(): void
    {
        $this->status = $this->meeting->status;
        $this->failureReason = $this->meeting->failure_reason;
    }

    public function render()
    {
        return view('livewire.meeting-status-panel');
    }
}
