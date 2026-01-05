<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        $userId = Auth::id();

        return [
            'name' => ['required', 'string', 'max:50', Rule::unique('users', 'name')->ignore($userId)],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],

            'nama_peternakan' => ['required', 'string', 'max:100'],
            'alamat' => ['nullable', 'string'],
            'nomor_handphone' => ['nullable', 'string', 'max:30'],
            'foto_profil' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.unique' => 'Nama sudah digunakan.',
            'email.email' => 'Email tidak valid.',
            'password.min' => 'Password minimal 6 karakter.',
            'foto_profil.image' => 'File foto harus berupa gambar.',
        ];
    }
}
