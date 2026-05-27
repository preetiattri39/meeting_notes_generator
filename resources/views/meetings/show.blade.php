<x-layouts.app :title="$meeting->title" :heading="$meeting->title">
    <section class="grid gap-6 xl:grid-cols-[1.5fr_0.9fr]">
        <div class="space-y-6">
            <livewire:meeting-status-panel :meeting="$meeting" :key="'meeting-status-'.$meeting->id" />

            <div class="shell-card p-6">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.3em] text-slate-500">{{ $meeting->category ?? 'General' }}</p>
                        <h2 class="mt-2 text-3xl font-bold">AI summary</h2>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach ($meeting->tags as $tag)
                                <span class="rounded-full bg-slate-900 px-3 py-1 text-xs font-medium text-white">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('meetings.edit', $meeting) }}" class="btn-secondary">Edit</a>
                        <a href="{{ route('meetings.export.pdf', $meeting) }}" class="btn-secondary">Export PDF</a>
                        <a href="{{ route('meetings.export.docx', $meeting) }}" class="btn-secondary">Export DOCX</a>
                        @if ($meeting->status === 'failed')
                            <form method="POST" action="{{ route('meetings.retry', $meeting) }}">
                                @csrf
                                <button class="btn-primary">Retry processing</button>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="prose prose-slate mt-6 max-w-none">
                    {!! nl2br(e($meeting->summary_markdown ?: 'Summary will appear here once processing completes.')) !!}
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="shell-card p-6">
                    <h3 class="text-2xl font-bold">Key points</h3>
                    <ul class="mt-4 space-y-3 text-sm text-slate-600">
                        @forelse ($meeting->key_points ?? [] as $point)
                            <li class="rounded-2xl bg-slate-50 px-4 py-3">{{ $point }}</li>
                        @empty
                            <li class="text-slate-500">No key points extracted yet.</li>
                        @endforelse
                    </ul>
                </div>
                <div class="shell-card p-6">
                    <h3 class="text-2xl font-bold">Speaker highlights</h3>
                    <div class="mt-4 space-y-3">
                        @forelse ($meeting->speaker_overview ?? [] as $speaker)
                            <div class="rounded-2xl bg-slate-50 px-4 py-3">
                                <p class="font-semibold">{{ $speaker['speaker_name'] }}</p>
                                <p class="mt-1 text-sm text-slate-600">{{ $speaker['summary'] }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">Speaker summaries pending.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="shell-card p-6">
                <h3 class="text-2xl font-bold">Transcript</h3>
                <div class="mt-4 space-y-3">
                    @forelse ($meeting->transcriptSegments as $segment)
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="flex flex-wrap items-center gap-3 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                                <span>{{ $segment->speaker_name ?? 'Speaker' }}</span>
                                @if ($segment->start_second !== null)
                                    <span>{{ number_format($segment->start_second, 1) }}s - {{ number_format($segment->end_second ?? $segment->start_second, 1) }}s</span>
                                @endif
                            </div>
                            <p class="mt-2 text-sm leading-7 text-slate-700">{{ $segment->text }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Transcript not available yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="shell-card p-6">
                <h3 class="text-2xl font-bold">Action items</h3>
                <div class="mt-4 space-y-3">
                    @forelse ($meeting->actionItems as $item)
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="font-semibold text-slate-900">{{ $item->description }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $item->assignee_name ?: 'Unassigned' }} · {{ $item->priority }} priority</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No action items extracted yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="shell-card p-6">
                <h3 class="text-2xl font-bold">Decisions</h3>
                <div class="mt-4 space-y-3">
                    @forelse ($meeting->decisions as $decision)
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="font-semibold">{{ $decision->decision }}</p>
                            @if ($decision->rationale)
                                <p class="mt-1 text-sm text-slate-500">{{ $decision->rationale }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No explicit decisions extracted yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="shell-card p-6">
                <h3 class="text-2xl font-bold">Highlights</h3>
                <div class="mt-4 space-y-3">
                    @forelse ($meeting->highlights as $highlight)
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">{{ $highlight->type }}</p>
                            <p class="mt-2 text-sm text-slate-700">{{ $highlight->content }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Highlights pending.</p>
                    @endforelse
                </div>
            </div>

            <div class="shell-card p-6">
                <h3 class="text-2xl font-bold">Collaboration</h3>
                <div class="mt-4 space-y-3 text-sm text-slate-600">
                    <p><span class="font-semibold text-slate-900">Owner:</span> {{ $meeting->owner->name }} ({{ $meeting->owner->email }})</p>
                    @if ($meeting->team)
                        <p><span class="font-semibold text-slate-900">Team:</span> {{ $meeting->team->name }}</p>
                    @endif
                    <div>
                        <p class="font-semibold text-slate-900">Shared with</p>
                        <div class="mt-2 space-y-2">
                            @forelse ($meeting->collaborators as $collaborator)
                                <p>{{ $collaborator->name }} · {{ $collaborator->email }}</p>
                            @empty
                                <p class="text-slate-500">No direct collaborators assigned.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const panel = document.getElementById('meeting-status-panel');
            if (!panel || !window.meetingRealtime) return;

            const meetingId = panel.dataset.meetingId;
            const badge = document.getElementById('meeting-status-badge');
            const message = document.getElementById('meeting-status-message');

            window.meetingRealtime.subscribe(meetingId, (payload) => {
                if (!badge || !message) return;

                badge.textContent = payload.status;
                badge.className = 'status-pill ' + (
                    payload.status === 'completed'
                        ? 'bg-teal-100 text-teal-800'
                        : payload.status === 'failed'
                            ? 'bg-rose-100 text-rose-700'
                            : 'bg-amber-100 text-amber-700'
                );
                message.textContent = payload.message;

                if (payload.status === 'completed' || payload.status === 'failed') {
                    setTimeout(() => window.location.reload(), 1200);
                }
            });
        });
    </script>
</x-layouts.app>
