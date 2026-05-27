<?php

namespace App\Jobs;

use App\Events\MeetingProcessingUpdated;
use App\Models\Meeting;
use App\Notifications\MeetingSummaryReadyNotification;
use App\Services\AuditLogger;
use App\Services\MeetingAiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class ProcessMeetingMedia implements ShouldQueue
{
    use Queueable;

    public int $timeout = 1800;

    public function __construct(
        public int $meetingId,
    ) {
    }

    public function handle(MeetingAiService $meetingAiService, AuditLogger $auditLogger): void
    {
        $meeting = Meeting::with('owner', 'collaborators')->findOrFail($this->meetingId);

        $meeting->update([
            'status' => 'processing',
            'processing_started_at' => now(),
            'failure_reason' => null,
            'failed_at' => null,
        ]);

        broadcast(new MeetingProcessingUpdated($meeting->fresh(), 'Transcribing media'));

        $result = $meetingAiService->process($meeting);

        $meeting->refresh()->update([
            'status' => 'completed',
            'processed_at' => now(),
        ]);

        broadcast(new MeetingProcessingUpdated($meeting->fresh(), 'Summary ready'));

        $recipients = $meeting->collaborators
            ->push($meeting->owner)
            ->unique('id')
            ->filter();

        foreach ($recipients as $recipient) {
            $recipient->notify(new MeetingSummaryReadyNotification($meeting));
        }

        $auditLogger->logForModel($meeting, 'meeting.processed', null, [
            'transcript_segments' => count($result['segments']),
            'action_items' => count($result['action_items']),
            'decisions' => count($result['decisions']),
        ]);
    }

    public function failed(?Throwable $exception): void
    {
        $meeting = Meeting::find($this->meetingId);

        if (! $meeting) {
            return;
        }

        $meeting->update([
            'status' => 'failed',
            'failed_at' => now(),
            'failure_reason' => $exception?->getMessage(),
        ]);

        broadcast(new MeetingProcessingUpdated($meeting->fresh(), 'Processing failed'));
    }
}
