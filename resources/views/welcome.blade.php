<x-layouts.app :title="'WorkPulse AI Meeting Notes'" :heading="'AI Meeting Notes for modern teams'">
    <section class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
        <div class="shell-card overflow-hidden">
            <div class="bg-[radial-gradient(circle_at_top_left,_rgba(20,184,166,0.24),_transparent_20rem),linear-gradient(135deg,#08111f_0%,#10203f_55%,#0f766e_100%)] px-8 py-12 text-white">
                <p class="text-sm font-semibold uppercase tracking-[0.4em] text-teal-100">Meeting intelligence SaaS</p>
                <h2 class="mt-4 max-w-2xl text-4xl font-bold tracking-tight sm:text-5xl">Upload audio or video. Get searchable notes, action items, decisions, and speaker-level highlights.</h2>
                <p class="mt-5 max-w-2xl text-base text-slate-200 sm:text-lg">WorkPulse combines Laravel 12, OpenAI transcription, structured summarization, background queues, exports, and realtime status updates in one production-ready workspace.</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('register') }}" class="btn-primary bg-white text-slate-900 hover:bg-teal-50">Start free workspace</a>
                    <a href="{{ route('login') }}" class="btn-secondary border-white/15 bg-white/10 text-white hover:bg-white/20">Sign in</a>
                </div>
            </div>
        </div>

        <div class="grid gap-4">
            <div class="metric-card bg-slate-900 text-white">
                <p class="text-sm uppercase tracking-[0.3em] text-teal-200">Included</p>
                <ul class="mt-4 space-y-3 text-sm text-slate-200">
                    <li>Secure authentication and team sharing</li>
                    <li>Queued transcription and AI summarization</li>
                    <li>Speaker diarization and decision tracking</li>
                    <li>PDF and DOCX exports</li>
                    <li>Realtime progress via Reverb</li>
                    <li>Audit logs and admin analytics</li>
                </ul>
            </div>
            <div class="metric-card">
                <p class="text-sm uppercase tracking-[0.3em] text-slate-500">Built for</p>
                <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                    <div class="rounded-2xl bg-slate-100 p-4">Sales calls</div>
                    <div class="rounded-2xl bg-slate-100 p-4">Board reviews</div>
                    <div class="rounded-2xl bg-slate-100 p-4">Standups</div>
                    <div class="rounded-2xl bg-slate-100 p-4">Hiring loops</div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
