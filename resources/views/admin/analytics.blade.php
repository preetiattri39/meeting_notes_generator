<x-layouts.app :title="'Admin Analytics'" :heading="'Platform analytics'">
    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        @foreach ($totals as $label => $value)
            <div class="metric-card">
                <p class="text-sm text-slate-500">{{ str($label)->headline() }}</p>
                <p class="mt-3 text-3xl font-bold">{{ $value }}</p>
            </div>
        @endforeach
    </section>

    <section class="mt-6 grid gap-6 xl:grid-cols-2">
        <div class="shell-card p-6">
            <h2 class="text-2xl font-bold">Category breakdown</h2>
            <div class="mt-4 space-y-3">
                @forelse ($categoryBreakdown as $item)
                    <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                        <span>{{ $item->category ?: 'Uncategorized' }}</span>
                        <span class="font-semibold">{{ $item->aggregate }}</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No category data yet.</p>
                @endforelse
            </div>
        </div>

        <div class="shell-card p-6">
            <h2 class="text-2xl font-bold">Recent failures</h2>
            <div class="mt-4 space-y-3">
                @forelse ($recentFailures as $meeting)
                    <div class="rounded-2xl bg-rose-50 px-4 py-3">
                        <p class="font-semibold">{{ $meeting->title }}</p>
                        <p class="mt-1 text-sm text-rose-700">{{ $meeting->failure_reason ?: 'Unknown failure' }}</p>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No failed jobs.</p>
                @endforelse
            </div>
        </div>
    </section>
</x-layouts.app>
