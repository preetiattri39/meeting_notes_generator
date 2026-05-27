<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'job_title' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $role = User::query()->exists() ? 'member' : 'admin';

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'job_title' => $validated['job_title'] ?? null,
            'role' => $role,
            'password' => Hash::make($validated['password']),
        ]);

        $team = Team::create([
            'owner_id' => $user->id,
            'name' => "{$user->name}'s Team",
            'slug' => Str::slug($user->name.'-team-'.Str::random(6)),
        ]);

        $team->members()->attach($user->id, ['role' => 'owner']);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
