<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    /**
     * Proses registrasi user baru.
     * Semua pendaftar otomatis mendapat peran 'user'.
     * Akun admin dibuat melalui seeder, bukan lewat form register.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi input dari form register
        $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Buat user baru di database
        // Field 'nama' sesuai kolom di tabel users
        // Peran otomatis 'user' (bukan admin)
        $user = User::create([
            'nama'     => $request->name,     // simpan ke kolom 'nama'
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'peran'    => 'user',             // peran default = user
        ]);

        event(new Registered($user));

        // Langsung login setelah register
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
