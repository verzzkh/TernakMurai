<?php

declare(strict_types=1);

namespace App\Http\Controllers\Peternak;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $peternak = $user->peternak;

        return view('peternak.profile.show', compact('user', 'peternak'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        $peternak = $user->peternak;

        $data = $request->validated();

        // Update user fields
        $user->name = $data['name']; // name memang required

if ($request->filled('email')) {
    $user->email = $data['email'];
}

if ($request->filled('password')) {
    $user->password = Hash::make($data['password']);
}

$user->save();
        // Update peternak fields (create if missing)
        if (! $peternak) {
            $peternak = $user->peternak()->create([
                'nama_peternakan' => $data['nama_peternakan'] ?? '',
                'alamat' => $data['alamat'] ?? null,
                'nomor_handphone' => $data['nomor_handphone'] ?? null,
            ]);
        }

        if ($peternak) {
            $peternak->nama_peternakan = $data['nama_peternakan'] ?? $peternak->nama_peternakan;
            $peternak->alamat = $data['alamat'] ?? $peternak->alamat;
            $peternak->nomor_handphone = $data['nomor_handphone'] ?? $peternak->nomor_handphone;

            if ($request->hasFile('foto_profil')) {
                $file = $request->file('foto_profil');
                $filename = \Illuminate\Support\Str::uuid() . '.' . $file->getClientOriginalExtension();
                $path = "peternak/{$peternak->id}/{$filename}";
                $file->storeAs("peternak/{$peternak->id}", $filename, 'public');

                // delete old if exists
                if ($peternak->foto_profil) {
                    Storage::disk('public')->delete($peternak->foto_profil);
                }
                $peternak->foto_profil = $path;
            }

            $peternak->save();
        }

        return redirect()->route('peternak.profile.show')->with('success', 'Profil berhasil diperbarui.');
    }
}
