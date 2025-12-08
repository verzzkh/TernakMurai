<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Peternak;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:users,name'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'nama_peternakan' => ['required', 'string', 'max:100'],
            'alamat' => ['nullable', 'string'],
            'nomor_handphone' => ['nullable', 'string', 'max:30'],
        ]);

        // Create user (password hashed)
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'password' => Hash::make($data['password']),
        ]);

        // Create peternak profile
        Peternak::create([
            'user_id' => $user->id,
            'nama_peternakan' => $data['nama_peternakan'],
            'alamat' => $data['alamat'] ?? null,
            'nomor_handphone' => $data['nomor_handphone'] ?? null,
        ]);

        // Redirect to login with success message
        return Redirect::route('login')->with('success', 'Registrasi berhasil. Silakan login.');
    }
}
