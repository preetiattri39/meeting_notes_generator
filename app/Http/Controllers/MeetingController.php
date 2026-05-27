<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMeetingRequest;
use App\Http\Requests\UpdateMeetingRequest;
use App\Jobs\ProcessMeetingMedia;
use App\Models\Meeting;
use App\Models\Tag;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MeetingController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $meetings = Meeting::query()
            ->with(['team', 'tags'])
            ->visibleTo($request->user())
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');

                $query->where(fn ($builder) => $builder
                    ->where('title', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('transcript_text', 'like', "%{$search}%"));
            })
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->string('category')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->wantsJson()) {
            return response()->json($meetings);
        }

        return view('meetings.index', [
            'meetings' => $meetings,
            'categories' => Meeting::visibleTo($request->user())->select('category')->distinct()->pluck('category')->filter()->values(),
        ]);
    }

    public function create(Request $request): View
    {
        return view('meetings.create', [
            'teams' => $request->user()->teams()->orderBy('name')->get(),
        ]);
    }

    public function store(StoreMeetingRequest $request, AuditLogger $auditLogger): RedirectResponse
    {
        $validated = $request->validated();
        $file = $validated['media'];
        $path = $file->store('meetings', 'local');

        $meeting = Meeting::create([
            'user_id' => $request->user()->id,
            'team_id' => $validated['team_id'] ?? null,
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']).'-'.Str::lower(Str::random(6)),
            'category' => $validated['category'] ?? 'General',
            'language' => $validated['language'] ?? 'auto',
            'scheduled_for' => $validated['scheduled_for'] ?? null,
            'status' => 'queued',
            'media_path' => $path,
            'media_name' => $file->getClientOriginalName(),
            'media_mime_type' => $file->getMimeType(),
            'media_size' => $file->getSize(),
            'metadata' => [
                'original_extension' => $file->getClientOriginalExtension(),
            ],
        ]);

        $this->syncTags($meeting, $validated['tags'] ?? null);
        $this->syncCollaborators($meeting, $validated['collaborator_emails'] ?? null);

        $auditLogger->logForModel($meeting, 'meeting.created', null, $meeting->toArray(), $request);

        ProcessMeetingMedia::dispatch($meeting->id);

        return redirect()->route('meetings.show', $meeting)->with('status', 'Meeting uploaded and queued for AI processing.');
    }

    public function show(Request $request, Meeting $meeting): View|JsonResponse
    {
        $this->authorize('view', $meeting);

        $meeting->load(['team', 'owner', 'tags', 'collaborators', 'transcriptSegments', 'actionItems', 'decisions', 'highlights']);

        if ($request->wantsJson()) {
            return response()->json($meeting);
        }

        return view('meetings.show', compact('meeting'));
    }

    public function edit(Request $request, Meeting $meeting): View
    {
        $this->authorize('update', $meeting);

        return view('meetings.edit', [
            'meeting' => $meeting->load(['tags', 'collaborators']),
            'teams' => $request->user()->teams()->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateMeetingRequest $request, Meeting $meeting, AuditLogger $auditLogger): RedirectResponse
    {
        $this->authorize('update', $meeting);

        $old = $meeting->toArray();
        $meeting->update($request->validated());

        $this->syncTags($meeting, $request->input('tags'));
        $this->syncCollaborators($meeting, $request->input('collaborator_emails'));

        $auditLogger->logForModel($meeting, 'meeting.updated', $old, $meeting->fresh()->toArray(), $request);

        return redirect()->route('meetings.show', $meeting)->with('status', 'Meeting updated.');
    }

    public function destroy(Request $request, Meeting $meeting, AuditLogger $auditLogger): RedirectResponse
    {
        $this->authorize('delete', $meeting);

        $old = $meeting->toArray();
        Storage::disk($meeting->media_disk)->delete($meeting->media_path);
        $meeting->delete();

        $auditLogger->logForModel($meeting, 'meeting.deleted', $old, null, $request);

        return redirect()->route('meetings.index')->with('status', 'Meeting deleted.');
    }

    public function retry(Request $request, Meeting $meeting, AuditLogger $auditLogger): RedirectResponse
    {
        $this->authorize('update', $meeting);

        $meeting->update([
            'status' => 'queued',
            'failure_reason' => null,
            'failed_at' => null,
        ]);

        ProcessMeetingMedia::dispatch($meeting->id);
        $auditLogger->logForModel($meeting, 'meeting.retried', null, ['status' => 'queued'], $request);

        return redirect()->route('meetings.show', $meeting)->with('status', 'Meeting was queued for reprocessing.');
    }

    protected function syncTags(Meeting $meeting, ?string $rawTags): void
    {
        $tagIds = collect(explode(',', (string) $rawTags))
            ->map(fn (string $tag) => trim($tag))
            ->filter()
            ->map(function (string $tag) {
                $model = Tag::firstOrCreate(
                    ['slug' => Str::slug($tag)],
                    ['name' => $tag, 'color' => collect(['slate', 'teal', 'amber', 'rose'])->random()],
                );

                return $model->id;
            })
            ->all();

        $meeting->tags()->sync($tagIds);
    }

    protected function syncCollaborators(Meeting $meeting, ?string $emails): void
    {
        $users = collect(explode(',', (string) $emails))
            ->map(fn (string $email) => trim($email))
            ->filter()
            ->pipe(fn (Collection $collection) => User::whereIn('email', $collection->all())->pluck('id'))
            ->mapWithKeys(fn ($id) => [$id => ['role' => 'viewer']])
            ->all();

        $meeting->collaborators()->sync($users);
    }
}
