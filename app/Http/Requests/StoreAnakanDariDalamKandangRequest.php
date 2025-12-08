<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreAnakanDariDalamKandangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $peternakId = Auth::user()?->peternak?->id;

        return [
            // hubungan dasar
            'kandang_id' => ['required', 'exists:kandang,id'],
            'indukan_jantan_id' => ['nullable', 'exists:indukan,id'],
            'indukan_betina_id' => ['nullable', 'exists:indukan,id'],

            // tanggal wajib
            'tanggal_lahir' => ['required', 'date', 'before_or_equal:today'],

            // jumlah anak wajib
            'jumlah_anakan' => ['required', 'integer', 'min:1', 'max:10'],

            // jenis kelamin: bisa satu (string) atau array (multi)
            'jenis_kelamin' => ['required'],
            'jenis_kelamin.*' => ['in:jantan,betina,tidak_diketahui'],

              // Nomor ring boleh kosong, tapi bisa array
            'nomor_ring' => ['sometimes', 'array'],
            'nomor_ring.*' => ['nullable', 'string', 'max:100'],

            // foto opsional tapi valid
            'foto_anakan' => ['nullable'],
            'foto_anakan.*' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],

            // deskripsi karakteristik opsional
            'deskripsi_karakteristik' => ['nullable', 'string', 'max:5000'],

            // sumber tetap internal
            'sumber_anakan' => ['in:internal'],
        ];
    }

    public function messages(): array
    {
        return [
            'kandang_id.required' => 'Kandang asal wajib diisi.',
            'kandang_id.exists' => 'Kandang tidak ditemukan.',
            'tanggal_lahir.required' => 'Tanggal menetas wajib diisi.',
            'tanggal_lahir.before_or_equal' => 'Tanggal menetas tidak boleh lebih dari hari ini.',
            'jumlah_anakan.required' => 'Jumlah anakan wajib diisi.',
            'jumlah_anakan.integer' => 'Jumlah anakan harus berupa angka.',
            'jumlah_anakan.min' => 'Minimal 1 anakan.',
            'jumlah_anakan.max' => 'Maksimal 10 anakan.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.*.in' => 'Jenis kelamin tidak valid.',
            'foto_anakan.*.image' => 'File harus berupa gambar.',
            'foto_anakan.*.mimes' => 'Format gambar harus JPEG, JPG, atau PNG.',
            'foto_anakan.*.max' => 'Ukuran gambar maksimal 2MB.',
            'deskripsi_karakteristik.max' => 'Deskripsi maksimal 5000 karakter.',
        ];
    }

protected function prepareForValidation(): void
{
    $peternakId = Auth::user()?->peternak?->id;

    // 🧠 Pastikan nomor_ring selalu jadi array (baik 1 atau banyak)
    $rawRing = $this->input('nomor_ring');
    $rings = [];

    if (is_array($rawRing)) {
        foreach ($rawRing as $key => $value) {
            if (!is_null($value) && $value !== '') {
                $rings[(int) $key] = trim($value);
            }
        }
    } elseif (is_string($rawRing) && $rawRing !== '') {
        // kalau cuma satu ekor, ubah jadi array dengan index 1
        $rings[1] = trim($rawRing);
    }

    $this->merge([
        'peternak_id' => $peternakId,
        'sumber_anakan' => 'internal',
        'nomor_ring' => $rings, // sudah dipastikan array
    ]);
}

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        $extra = $this->only([
            'nomor_ring',
            'indukan_jantan_id',
            'indukan_betina_id',
        ]);

        // Filter null untuk mencegah array_merge gagal
        return array_merge($validated, array_filter($extra, fn($v) => $v !== null));
    }

}
