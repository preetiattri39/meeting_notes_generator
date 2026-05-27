<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeetingHighlight extends Model
{
    protected $fillable = [
        'meeting_id',
        'speaker_name',
        'type',
        'score',
        'content',
    ];

    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }
}
