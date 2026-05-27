<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphedByMany;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'color',
    ];

    public function meetings(): MorphedByMany
    {
        return $this->morphedByMany(Meeting::class, 'taggable');
    }
}
