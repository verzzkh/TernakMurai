<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreIndukanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $peternakId = Auth::user()?->peternak?->id;

        return [
            'nomor_ring' => [
                'required',
                'string',
                'max:50',
                Rule::unique('indukan', 'nomor_ring')
                    ->where(fn ($query) => $query->where('peternak_id', $peternakId)),
            ],
            'nama' => 'nullable|string|max:100',
            'jenis_kelamin' => 'required|in:jantan,betina',
            'tanggal_lahir' => 'nullable|date|before_or_equal:today',
            'catatan' => 'nullable|string',
            'prestasi' => 'nullable|string',
            'karakteristik' => 'nullable|string',
            'foto_indukan' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:5020'],
'aktif_kicau' => ['nullable', 'boolean'],
'mendekati_betina' => ['nullable', 'boolean'],
'nafsu_makan_meningkat' => ['nullable', 'boolean'],
'aktif_buat_sarang' => ['nullable', 'boolean'],
'temperamen' => ['nullable', Rule::in(['jinak','sedang','fighter'])],

        ];
    }

    public function messages(): array
    {
        return [
            'nomor_ring.required' => 'Nomor ring harus diisi.',
            'nomor_ring.unique' => 'Nomor ring sudah digunakan.',
            'nomor_ring.max' => 'Nomor ring maksimal 50 karakter.',
            'nama.max' => 'Nama maksimal 100 karakter.',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin harus jantan atau betina.',
            'tanggal_lahir.date' => 'Tanggal lahir harus berupa tanggal yang valid.',
            'tanggal_lahir.before_or_equal' => 'Tanggal lahir tidak boleh lebih dari hari ini.',
        ];
    }
    public function prepareForValidation()
{
    $this->merge([
        'aktif_kicau' => $this->boolean('aktif_kicau'),
        'mendekati_betina' => $this->boolean('mendekati_betina'),
        'nafsu_makan_meningkat' => $this->boolean('nafsu_makan_meningkat'),
        'aktif_buat_sarang' => $this->boolean('aktif_buat_sarang'),
    ]);
}

}
