<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        // Tentukan apakah input adalah email atau username
        $loginField = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        // Buat array credentials dengan field yang sesuai
        $authCredentials = [
            $loginField => $credentials['login'],
            'password' => $credentials['password'],
        ];

        if (Auth::attempt($authCredentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $role = $user->getRole();

            // Pastikan user memiliki role yang valid
            if ($role === 'guest') {
                Auth::logout();

                return back()->withErrors([
                    'login' => 'Akun tidak memiliki akses. Silakan hubungi administrator.',
                ])->onlyInput('login');
            }

            // Redirect ke dashboard unified
            $welcomeMessage = match ($role) {
                'admin' => "Selamat datang Admin {$user->name}!",
                'peternak' => "Selamat datang Peternak {$user->name}!",
                default => "Selamat datang {$user->name}!",
            };

            session()->flash('success', $welcomeMessage);

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'login' => 'Email/Name atau password salah.',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
