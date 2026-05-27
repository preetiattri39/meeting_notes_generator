<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\User;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function __invoke(): View
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        return view('admin.analytics', [
            'totals' => [
                'users' => User::count(),
                'meetings' => Meeting::count(),
                'completed' => Meeting::where('status', 'completed')->count(),
                'failed' => Meeting::where('status', 'failed')->count(),
            ],
            'recentFailures' => Meeting::where('status', 'failed')->latest('failed_at')->limit(5)->get(),
            'categoryBreakdown' => Meeting::selectRaw('category, count(*) as aggregate')
                ->groupBy('category')
                ->orderByDesc('aggregate')
                ->get(),
        ]);
    }
}
