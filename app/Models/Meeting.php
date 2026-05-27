<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Meeting extends Model
{
    protected $fillable = [
        'user_id',
        'team_id',
        'title',
        'slug',
        'category',
        'language',
        'scheduled_for',
        'status',
        'media_disk',
        'media_path',
        'media_name',
        'media_mime_type',
        'media_size',
        'duration_seconds',
        'transcript_text',
        'summary_markdown',
        'key_points',
        'speaker_overview',
        'metadata',
        'processing_started_at',
        'processed_at',
        'failed_at',
        'failure_reason',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_for' => 'datetime',
            'key_points' => 'array',
            'speaker_overview' => 'array',
            'metadata' => 'array',
            'processing_started_at' => 'datetime',
            'processed_at' => 'datetime',
            'failed_at' => 'datetime',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function transcriptSegments(): HasMany
    {
        return $this->hasMany(MeetingTranscriptSegment::class);
    }

    public function actionItems(): HasMany
    {
        return $this->hasMany(MeetingActionItem::class);
    }

    public function decisions(): HasMany
    {
        return $this->hasMany(MeetingDecision::class);
    }

    public function highlights(): HasMany
    {
        return $this->hasMany(MeetingHighlight::class);
    }

    public function collaborators(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('role')->withTimestamps();
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();
    }

    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->isAdmin()) {
            return $query;
        }

        return $query->where(function (Builder $builder) use ($user) {
            $builder->where('user_id', $user->id)
                ->orWhereHas('collaborators', fn (Builder $relation) => $relation->where('users.id', $user->id))
                ->orWhereHas('team.members', fn (Builder $relation) => $relation->where('users.id', $user->id));
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
