<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreAnakanDariLuarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $peternakId = Auth::user()?->peternak?->id;

        return [
            // Nomor ring unik per peternak (boleh kosong jika belum punya ring)
            'nomor_ring' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('anakan', 'nomor_ring')
                    ->where(fn($q) => $q->where('peternak_id', $peternakId))
                    ->whereNull('deleted_at'),
            ],

            // Jenis kelamin wajib (jantan, betina, atau tidak diketahui)
            'jenis_kelamin_luar' => ['required', 'in:jantan,betina,tidak_diketahui'],

            // Foto wajib
            'foto_anakan_luar' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:5020'],

            // Tanggal lahir / perkiraan wajib
            'tanggal_lahir' => ['required', 'date', 'before_or_equal:today'],

            // Informasi asal / penjual wajib
            'asal_penjual' => ['required', 'string', 'max:255'],

            // Info indukan opsional
            'indukan_jantan_info' => ['nullable', 'string', 'max:255'],
            'indukan_betina_info' => ['nullable', 'string', 'max:255'],

            // Status pertumbuhan wajib
            'status' => ['required', 'in:trotol,pastol,lomba,dewasa'],

            // Harga jual wajib
            'harga' => ['required', 'integer', 'min:0'],

            // Karakteristik (gabungan dari karakteristik & catatan)
            'deskripsi_karakteristik' => ['nullable', 'string', 'max:5000'],

            // Kolom sumber anakan otomatis
            'sumber_anakan' => ['in:eksternal'],
        ];
    }

    public function messages(): array
    {
        return [
            'nomor_ring.unique' => 'Nomor ring sudah digunakan untuk anakan lain.',
            'jenis_kelamin_luar.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin_luar.in' => 'Jenis kelamin tidak valid.',
          
            'foto_anakan_luar.image' => 'File foto harus berupa gambar.',
            'foto_anakan_luar.mimes' => 'Format gambar harus JPEG, JPG, atau PNG.',
            'foto_anakan_luar.max' => 'Ukuran gambar maksimal 2MB.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date' => 'Tanggal lahir tidak valid.',
            'tanggal_lahir.before_or_equal' => 'Tanggal lahir tidak boleh lebih dari hari ini.',
            'asal_penjual.required' => 'Asal atau penjual wajib diisi.',
            'asal_penjual.max' => 'Nama asal/penjual maksimal 255 karakter.',
            'status.required' => 'Status pertumbuhan wajib dipilih.',
            'status.in' => 'Status pertumbuhan tidak valid.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.integer' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga tidak boleh negatif.',
          
            'deskripsi_karakteristik.max' => 'Karakteristik maksimal 5000 karakter.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $peternakId = Auth::user()?->peternak?->id;

        $this->merge([
            'peternak_id' => $peternakId,
        ]);
    }
}
