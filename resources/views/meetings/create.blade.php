<x-layouts.app :title="'Upload Meeting'" :heading="'Upload meeting recording'">
    <div class="mx-auto max-w-4xl">
        <div class="shell-card p-6 sm:p-8">
            <div class="mb-6">
                <h2 class="text-3xl font-bold">New meeting</h2>
                <p class="mt-2 text-sm text-slate-500">Upload a recording and queue AI transcription, summarization, action-item extraction, and speaker highlights.</p>
            </div>

            <form method="POST" action="{{ route('meetings.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @include('meetings._form', ['teams' => $teams])
                <div class="flex justify-end gap-3">
                    <a href="{{ route('dashboard') }}" class="btn-secondary">Cancel</a>
                    <button class="btn-primary">Upload and process</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
