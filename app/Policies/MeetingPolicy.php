<?php

namespace App\Policies;

use App\Models\Meeting;
use App\Models\User;

class MeetingPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Meeting $meeting): bool
    {
        return $this->touchesMeeting($user, $meeting);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Meeting $meeting): bool
    {
        return $user->isAdmin()
            || $meeting->user_id === $user->id
            || $meeting->collaborators()->where('users.id', $user->id)->wherePivot('role', 'editor')->exists();
    }

    public function delete(User $user, Meeting $meeting): bool
    {
        return $user->isAdmin() || $meeting->user_id === $user->id;
    }

    public function restore(User $user, Meeting $meeting): bool
    {
        return $this->delete($user, $meeting);
    }

    public function forceDelete(User $user, Meeting $meeting): bool
    {
        return $this->delete($user, $meeting);
    }

    protected function touchesMeeting(User $user, Meeting $meeting): bool
    {
        return $user->isAdmin()
            || $meeting->user_id === $user->id
            || $meeting->collaborators()->where('users.id', $user->id)->exists()
            || ($meeting->team && $meeting->team->members()->where('users.id', $user->id)->exists());
    }
}
