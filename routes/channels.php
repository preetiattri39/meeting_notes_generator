<?php

use App\Models\Meeting;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('meeting.{meetingId}', function ($user, $meetingId) {
    $meeting = Meeting::query()->with('team.members')->find($meetingId);

    if (! $meeting) {
        return false;
    }

    return $user->isAdmin()
        || $meeting->user_id === $user->id
        || $meeting->collaborators()->where('users.id', $user->id)->exists()
        || ($meeting->team && $meeting->team->members->contains('id', $user->id));
});
