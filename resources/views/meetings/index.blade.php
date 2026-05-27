<x-layouts.app :title="'Meetings'" :heading="'Meeting library'">
    <div class="shell-card p-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h2 class="text-2xl font-bold">Search and filter meetings</h2>
                <p class="text-sm text-slate-500">Browse transcripts, decisions, action items, and categories.</p>
            </div>
            <form class="grid gap-3 sm:grid-cols-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search transcript text" class="field-input sm:col-span-2">
                <select name="category" class="field-input">
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
                    @endforeach
                </select>
                <select name="status" class="field-input">
                    <option value="">All statuses</option>
                    @foreach (['queued', 'processing', 'completed', 'failed'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="mt-6 grid gap-4">
            @foreach ($meetings as $meeting)
                <a href="{{ route('meetings.show', $meeting) }}" class="rounded-3xl border border-slate-200 bg-slate-50/70 p-5 transition hover:border-teal-300 hover:bg-white">
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <div class="flex flex-wrap items-center gap-3">
                                <h3 class="text-xl font-bold">{{ $meeting->title }}</h3>
                                <span class="status-pill {{ $meeting->status === 'completed' ? 'bg-teal-100 text-teal-800' : ($meeting->status === 'failed' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">{{ $meeting->status }}</span>
                            </div>
                            <p class="mt-2 text-sm text-slate-500">{{ $meeting->category }} · {{ $meeting->media_name }}</p>
                        </div>
                        <p class="text-sm text-slate-500">{{ $meeting->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-6">{{ $meetings->links() }}</div>
    </div>
</x-layouts.app>
