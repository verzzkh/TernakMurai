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
    'nomor_handphone' => ['required', 'regex:/^08[0-9]{8,12}$/'],
    'foto_profil' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
], [
    'name.required' => 'Nama pengguna wajib diisi.',
    'name.unique' => 'Nama pengguna sudah digunakan.',

    'password.required' => 'Password wajib diisi.',
    'password.min' => 'Password minimal 6 karakter.',
    'password.confirmed' => 'Konfirmasi password tidak sesuai.',

    'nomor_handphone.required' => 'Nomor handphone wajib diisi.',
    'nomor_handphone.regex' => 'Nomor handphone harus diawali 08 dan hanya berisi angka.',

    'nama_peternakan.required' => 'Nama peternakan wajib diisi.',
]);

    // 1️⃣ BUAT USER DULU
    $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'] ?? null,
        'password' => Hash::make($data['password']),
    ]);

    // 2️⃣ BARU BUAT PETERNAK
    $peternak = Peternak::create([
        'user_id' => $user->id,
        'nama_peternakan' => $data['nama_peternakan'],
        'alamat' => $data['alamat'] ?? null,
        'nomor_handphone' => $data['nomor_handphone'] ?? null,
    ]);

    // 3️⃣ FOTO PROFIL (OPSIONAL)
    if ($request->hasFile('foto_profil')) {
        $file = $request->file('foto_profil');
        $filename = \Illuminate\Support\Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = "peternak/{$peternak->id}/{$filename}";

        $file->storeAs("peternak/{$peternak->id}", $filename, 'public');

        $peternak->foto_profil = $path;
        $peternak->save();
    }

    return redirect()->route('login')->with('success', 'Registrasi berhasil. Silakan login.');
}
}
