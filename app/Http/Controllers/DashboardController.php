<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        $query = Meeting::query()
            ->with(['team', 'tags'])
            ->visibleTo($user)
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');

            $query->where(function ($builder) use ($search) {
                $builder->where('title', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('transcript_text', 'like', "%{$search}%");
            });
        }

        return view('dashboard', [
            'meetings' => $query->paginate(9)->withQueryString(),
            'stats' => [
                'total' => Meeting::visibleTo($user)->count(),
                'processing' => Meeting::visibleTo($user)->whereIn('status', ['queued', 'processing'])->count(),
                'completed' => Meeting::visibleTo($user)->where('status', 'completed')->count(),
                'action_items' => Meeting::visibleTo($user)->withCount('actionItems')->get()->sum('action_items_count'),
            ],
            'teams' => $user->teams()->orderBy('name')->get(),
            'notifications' => $user->unreadNotifications()->limit(5)->get(),
        ]);
    }
}
