@php
    $editing = isset($meeting);
@endphp

<div class="grid gap-4 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label class="mb-2 block text-sm font-medium text-slate-700">Meeting title</label>
        <input type="text" name="title" value="{{ old('title', $meeting->title ?? '') }}" class="field-input" required>
    </div>
    <div>
        <label class="mb-2 block text-sm font-medium text-slate-700">Category</label>
        <input type="text" name="category" value="{{ old('category', $meeting->category ?? '') }}" class="field-input" placeholder="Board review, sales call, standup">
    </div>
    <div>
        <label class="mb-2 block text-sm font-medium text-slate-700">Language</label>
        <input type="text" name="language" value="{{ old('language', $meeting->language ?? 'auto') }}" class="field-input" placeholder="auto or en">
    </div>
    <div>
        <label class="mb-2 block text-sm font-medium text-slate-700">Scheduled for</label>
        <input type="datetime-local" name="scheduled_for" value="{{ old('scheduled_for', isset($meeting?->scheduled_for) ? $meeting->scheduled_for->format('Y-m-d\TH:i') : '') }}" class="field-input">
    </div>
    <div>
        <label class="mb-2 block text-sm font-medium text-slate-700">Team</label>
        <select name="team_id" class="field-input">
            <option value="">Private meeting</option>
            @foreach ($teams as $team)
                <option value="{{ $team->id }}" @selected(old('team_id', $meeting->team_id ?? '') == $team->id)>{{ $team->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="sm:col-span-2">
        <label class="mb-2 block text-sm font-medium text-slate-700">Tags</label>
        <input type="text" name="tags" value="{{ old('tags', isset($meeting) ? $meeting->tags->pluck('name')->implode(', ') : '') }}" class="field-input" placeholder="strategy, q2, customer-voice">
    </div>
    <div class="sm:col-span-2">
        <label class="mb-2 block text-sm font-medium text-slate-700">Collaborator emails</label>
        <input type="text" name="collaborator_emails" value="{{ old('collaborator_emails', isset($meeting) ? $meeting->collaborators->pluck('email')->implode(', ') : '') }}" class="field-input" placeholder="alex@example.com, taylor@example.com">
    </div>
    @unless($editing)
        <div class="sm:col-span-2">
            <label class="mb-2 block text-sm font-medium text-slate-700">Audio or video file</label>
            <input type="file" name="media" class="field-input" required>
            <p class="mt-2 text-xs text-slate-500">Supported: mp3, mp4, mpeg, mpga, m4a, wav, webm. Max 25 MB recommended by OpenAI speech-to-text docs.</p>
        </div>
    @endunless
</div>
