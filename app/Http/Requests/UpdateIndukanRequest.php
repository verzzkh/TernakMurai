<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateIndukanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $indukanId = $this->route('indukan');

        return [
            'nomor_ring' => 'required|string|max:50|unique:indukan,nomor_ring,'.$indukanId.',id,peternak_id,'.Auth::user()->peternak->id,
            'nama' => 'nullable|string|max:100',
            'jenis_kelamin' => 'required|in:jantan,betina',
            'tanggal_lahir' => 'nullable|date|before_or_equal:today',
            'catatan' => 'nullable|string',
            'prestasi' => 'nullable|string',
            'karakteristik' => 'nullable|string',
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
}
