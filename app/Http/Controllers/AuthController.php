<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Default Categories
        $defaults = [
            ['name' => 'Gaji', 'type' => 'IN'],
            ['name' => 'Bonus', 'type' => 'IN'],
            ['name' => 'Makan & Minum', 'type' => 'OUT'],
            ['name' => 'Transportasi', 'type' => 'OUT'],
            ['name' => 'Belanja', 'type' => 'OUT'],
            ['name' => 'Kesehatan', 'type' => 'OUT'],
        ];

        foreach ($defaults as $category) {
            \App\Models\Category::create([
                'user_id' => $user->id,
                'name' => $category['name'],
                'type' => $category['type']
            ]);
        }

        // Default Wallet
        \App\Models\Wallet::create([
            'user_id' => $user->id,
            'name' => 'Dompet Utama',
            'balance' => 0
        ]);

        Auth::login($user);

        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}