@props([
    'title' => config('app.name', 'WorkPulse'),
    'heading' => 'Meeting Intelligence',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700|space-grotesk:500,700" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    @livewireStyles
</head>
<body class="min-h-screen">
    <div class="mx-auto flex min-h-screen max-w-7xl flex-col px-4 py-6 sm:px-6 lg:px-8">
        @auth
            <header class="shell-card mb-6 overflow-hidden">
                <div class="flex flex-col gap-4 bg-[linear-gradient(135deg,#0f172a_0%,#0f766e_100%)] px-6 py-5 text-white lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <a href="{{ route('dashboard') }}" class="text-xs font-semibold uppercase tracking-[0.3em] text-teal-100">WorkPulse AI Notes</a>
                        <div class="mt-2 flex flex-wrap items-center gap-3">
                            <h1 class="text-2xl font-bold">{{ $heading }}</h1>
                            <span class="rounded-full bg-white/12 px-3 py-1 text-xs font-medium text-teal-50">{{ auth()->user()->role }}</span>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('meetings.create') }}" class="btn-primary bg-white text-slate-900 hover:bg-teal-50">Upload meeting</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn-secondary border-white/20 bg-white/10 text-white hover:bg-white/20">Logout</button>
                        </form>
                    </div>
                </div>
                <nav class="flex flex-wrap items-center gap-2 border-t border-slate-200/80 px-4 py-3 text-sm font-medium text-slate-600">
                    <a href="{{ route('dashboard') }}" class="rounded-full px-3 py-2 hover:bg-slate-100 {{ request()->routeIs('dashboard') ? 'bg-slate-900 text-white' : '' }}">Dashboard</a>
                    <a href="{{ route('meetings.index') }}" class="rounded-full px-3 py-2 hover:bg-slate-100 {{ request()->routeIs('meetings.*') ? 'bg-slate-900 text-white' : '' }}">Meetings</a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.analytics') }}" class="rounded-full px-3 py-2 hover:bg-slate-100 {{ request()->routeIs('admin.analytics') ? 'bg-slate-900 text-white' : '' }}">Admin analytics</a>
                    @endif
                </nav>
            </header>
        @endauth

        @if (session('status'))
            <div class="mb-6 rounded-2xl border border-teal-200 bg-teal-50 px-4 py-3 text-sm text-teal-900">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-900">
                <ul class="space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <main class="flex-1">
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
</body>
</html>
