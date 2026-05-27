<x-layouts.app :title="'Register'" :heading="'Create your workspace'">
    <div class="mx-auto max-w-xl">
        <div class="shell-card p-8">
            <h2 class="text-3xl font-bold">Start with your first team</h2>
            <p class="mt-2 text-sm text-slate-500">The first account becomes workspace admin and can access platform analytics.</p>
            <form method="POST" action="{{ route('register.store') }}" class="mt-8 grid gap-4 sm:grid-cols-2">
                @csrf
                <div class="sm:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="field-input" required>
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="field-input" required>
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Job title</label>
                    <input type="text" name="job_title" value="{{ old('job_title') }}" class="field-input">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Password</label>
                    <input type="password" name="password" class="field-input" required>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Confirm password</label>
                    <input type="password" name="password_confirmation" class="field-input" required>
                </div>
                <div class="sm:col-span-2">
                    <button class="btn-primary w-full">Create account</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
