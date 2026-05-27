<x-layouts.app :title="'Login'" :heading="'Welcome back'">
    <div class="mx-auto max-w-md">
        <div class="shell-card p-8">
            <h2 class="text-3xl font-bold">Sign in</h2>
            <p class="mt-2 text-sm text-slate-500">Access your meeting history, summaries, and exports.</p>
            <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-4">
                @csrf
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="field-input" required>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Password</label>
                    <input type="password" name="password" class="field-input" required>
                </div>
                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300">
                    Remember me
                </label>
                <button class="btn-primary w-full">Sign in</button>
            </form>
            <p class="mt-6 text-sm text-slate-500">Need an account? <a href="{{ route('register') }}" class="font-semibold text-teal-700">Create workspace</a></p>
        </div>
    </div>
</x-layouts.app>
