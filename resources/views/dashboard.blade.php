<x-layouts.app :title="'Dashboard'" :heading="'Meeting intelligence dashboard'">
    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="metric-card">
            <p class="text-sm text-slate-500">Meetings tracked</p>
            <p class="mt-3 text-3xl font-bold">{{ $stats['total'] }}</p>
        </div>
        <div class="metric-card">
            <p class="text-sm text-slate-500">Currently processing</p>
            <p class="mt-3 text-3xl font-bold">{{ $stats['processing'] }}</p>
        </div>
        <div class="metric-card">
            <p class="text-sm text-slate-500">Completed summaries</p>
            <p class="mt-3 text-3xl font-bold">{{ $stats['completed'] }}</p>
        </div>
        <div class="metric-card">
            <p class="text-sm text-slate-500">Action items extracted</p>
            <p class="mt-3 text-3xl font-bold">{{ $stats['action_items'] }}</p>
        </div>
    </section>

    <section class="mt-6 grid gap-6 xl:grid-cols-[1.5fr_0.9fr]">
        <div class="shell-card p-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h2 class="text-2xl font-bold">Recent meetings</h2>
                    <p class="text-sm text-slate-500">Search by title, category, or transcript content.</p>
                </div>
                <form class="flex flex-col gap-3 sm:flex-row">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search meetings" class="field-input sm:w-72">
                    <select name="status" class="field-input sm:w-48">
                        <option value="">All statuses</option>
                        @foreach (['queued', 'processing', 'completed', 'failed'] as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    <button class="btn-secondary">Filter</button>
                </form>
            </div>

            <div class="mt-6 space-y-4">
                @forelse ($meetings as $meeting)
                    <a href="{{ route('meetings.show', $meeting) }}" class="block rounded-3xl border border-slate-200 bg-slate-50/70 p-5 transition hover:border-teal-300 hover:bg-white">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="text-xl font-bold">{{ $meeting->title }}</h3>
                                    <span class="status-pill {{ $meeting->status === 'completed' ? 'bg-teal-100 text-teal-800' : ($meeting->status === 'failed' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">{{ $meeting->status }}</span>
                                </div>
                                <p class="mt-2 text-sm text-slate-500">{{ $meeting->category ?? 'General' }} · {{ $meeting->media_name }}</p>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @foreach ($meeting->tags as $tag)
                                        <span class="rounded-full bg-slate-900 px-3 py-1 text-xs font-medium text-white">{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="text-sm text-slate-500">
                                <p>Uploaded {{ $meeting->created_at->diffForHumans() }}</p>
                                @if ($meeting->team)
                                    <p class="mt-1">Shared with {{ $meeting->team->name }}</p>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="rounded-3xl border border-dashed border-slate-300 p-10 text-center text-sm text-slate-500">
                        No meetings yet. Upload your first recording to generate notes.
                    </div>
                @endforelse
            </div>

            <div class="mt-6">{{ $meetings->links() }}</div>
        </div>

        <div class="space-y-6">
            <div class="shell-card p-6">
                <h2 class="text-xl font-bold">Unread notifications</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($notifications as $notification)
                        <a href="{{ $notification->data['url'] ?? '#' }}" class="block rounded-2xl bg-slate-50 p-4 text-sm text-slate-600">
                            <p class="font-semibold text-slate-900">{{ $notification->data['meeting_title'] ?? 'Meeting ready' }}</p>
                            <p class="mt-1">Status: {{ $notification->data['status'] ?? 'completed' }}</p>
                        </a>
                    @empty
                        <p class="text-sm text-slate-500">No unread notifications.</p>
                    @endforelse
                </div>
            </div>

            <div class="shell-card p-6">
                <h2 class="text-xl font-bold">Teams</h2>
                <div class="mt-4 space-y-3">
                    @foreach ($teams as $team)
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="font-semibold">{{ $team->name }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $team->pivot->role ?? 'member' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
