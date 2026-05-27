<x-layouts.app :title="'Edit Meeting'" :heading="'Edit meeting details'">
    <div class="mx-auto max-w-4xl">
        <div class="shell-card p-6 sm:p-8">
            <div class="mb-6">
                <h2 class="text-3xl font-bold">Edit {{ $meeting->title }}</h2>
                <p class="mt-2 text-sm text-slate-500">Update metadata, sharing, tags, and categorization.</p>
            </div>

            <form method="POST" action="{{ route('meetings.update', $meeting) }}" class="space-y-6">
                @csrf
                @method('PUT')
                @include('meetings._form', ['teams' => $teams, 'meeting' => $meeting])
                <div class="flex justify-end gap-3">
                    <a href="{{ route('meetings.show', $meeting) }}" class="btn-secondary">Cancel</a>
                    <button class="btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
