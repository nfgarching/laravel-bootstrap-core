<?php

namespace Nfgarching\Core\Controllers\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class UserController
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth::register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard'));
    }

    // Zeigt die Profileinstellungen des angemeldeten Benutzers
    public function edit()
    {
        $user = Auth::user();

        return view('auth::settings', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($request->filled('current_password')) {
            $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user->update(['password' => Hash::make($request->password)]);

            return redirect()->back()->with('success', 'Passwort aktualisiert!');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $user->update(['name' => $request->name]);

        return redirect()->back()->with('success', 'Profil aktualisiert!');
    }
}
