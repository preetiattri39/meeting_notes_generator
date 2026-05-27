<div wire:poll.8s="refreshMeeting" class="rounded-3xl border border-slate-200 bg-slate-50 p-5" id="meeting-status-panel" data-meeting-id="{{ $meeting->id }}">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-sm font-medium uppercase tracking-[0.25em] text-slate-500">Processing status</p>
            <div class="mt-2 flex items-center gap-3">
                <span id="meeting-status-badge" class="status-pill {{ $status === 'completed' ? 'bg-teal-100 text-teal-800' : ($status === 'failed' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">{{ $status }}</span>
                <span id="meeting-status-message" class="text-sm text-slate-500">
                    @if ($status === 'completed')
                        Summary completed and exports ready.
                    @elseif ($status === 'failed')
                        {{ $failureReason ?: 'Processing failed.' }}
                    @else
                        Meeting is being transcribed and summarized.
                    @endif
                </span>
            </div>
        </div>
        <div class="text-sm text-slate-500">
            <p>Queued {{ $meeting->created_at->diffForHumans() }}</p>
            @if ($meeting->processed_at)
                <p class="mt-1">Completed {{ $meeting->processed_at->diffForHumans() }}</p>
            @endif
        </div>
    </div>
</div>
