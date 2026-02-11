<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreAnakanDariKandangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $peternakId = Auth::user()?->peternak?->id;

        return [
            'indukan_jantan_id' => ['nullable', 'exists:indukan,id'],
'indukan_betina_id' => ['nullable', 'exists:indukan,id'],


            // Nomor ring opsional, tapi harus unik dalam satu peternak
            'nomor_ring' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('anakan', 'nomor_ring')
                    ->where(fn($q) => $q->where('peternak_id', $peternakId))
                    ->whereNull('deleted_at'),
            ],

           'tanggal_lahir' => ['required', 'date', 'before_or_equal:today'],

          'jenis_kelamin' => ['required'],
'jenis_kelamin.*' => ['in:jantan,betina,tidak_diketahui'],

'foto_anakan' => ['nullable'],
'foto_anakan.*' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:5020'],


            // Status pertumbuhan opsional (default trotöl)
            'status_pertumbuhan' => ['nullable', 'in:trotol,pastol,lomba'],

            // Deskripsi karakteristik opsional
            'deskripsi_karakteristik' => ['nullable', 'string', 'max:5000'],

            // Catatan tambahan opsional
            'catatan' => ['nullable', 'string', 'max:1000'],

            // Jumlah anakan untuk form dinamis
            'jumlah_anakan' => ['required', 'integer', 'min:1', 'max:10'],

            // Sumber anakan selalu internal
            'sumber_anakan' => ['in:internal'],

            'perkawinan_id' => ['nullable', 'exists:perkawinan,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'nomor_ring.unique' => 'Nomor ring sudah digunakan untuk anakan lain.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date' => 'Tanggal lahir tidak valid.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid.',
            'status_pertumbuhan.in' => 'Status pertumbuhan tidak valid.',
            'deskripsi_karakteristik.max' => 'Deskripsi karakteristik maksimal 5000 karakter.',
            'catatan.max' => 'Catatan maksimal 1000 karakter.',
            'foto_anakan.image' => 'File foto harus berupa gambar.',
            'foto_anakan.mimes' => 'Format gambar harus JPEG, JPG, atau PNG.',
            'foto_anakan.max' => 'Ukuran gambar maksimal 2MB.',
            'jumlah_anakan.required' => 'Jumlah anakan wajib diisi.',
            'jumlah_anakan.integer' => 'Jumlah anakan harus berupa angka.',
            'jumlah_anakan.min' => 'Minimal 1 anakan.',
            'jumlah_anakan.max' => 'Maksimal 10 anakan.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $peternakId = Auth::user()?->peternak?->id;

        $this->merge([
            'peternak_id' => $peternakId,
            'sumber_anakan' => 'internal',
        ]);
    }

    public function validated($key = null, $default = null)
{
    $validated = parent::validated($key, $default);

    // pastikan kedua id indukan ikut dibawa
    $extra = $this->only([
        'indukan_jantan_id',
        'indukan_betina_id',
    ]);

    return array_merge($validated, $extra);
}

}
