<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateKandangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $kandang = $this->route('kandang');
        $kandangId = is_object($kandang) ? $kandang->id : $kandang;
        $peternakId = Auth::user()->peternak->id;

        return [
            'nomor_kandang' => [
                'sometimes',
                'string',
                'max:30',
                'unique:kandang,nomor_kandang,' . $kandangId . ',id,peternak_id,' . $peternakId,
            ],
            'deskripsi_kandang' => ['nullable', 'string'],
            'status' => ['required', 'in:kosong,bertelur,mengeram,berhasil,gagal'],
            'indukan_jantan_id' => ['nullable', 'exists:indukan,id'],
            'indukan_betina_id' => ['nullable', 'exists:indukan,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'nomor_kandang.required' => 'Nomor kandang harus diisi.',
            'nomor_kandang.unique' => 'Nomor kandang sudah digunakan.',
            'nomor_kandang.max' => 'Nomor kandang maksimal 30 karakter.',
            'status.required' => 'Status kandang harus dipilih.',
            'status.in' => 'Status kandang tidak valid.',
            'indukan_jantan_id.exists' => 'Indukan jantan tidak ditemukan.',
            'indukan_betina_id.exists' => 'Indukan betina tidak ditemukan.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $kandang = $this->route('kandang');
            
            $status = $this->input('status');
            $breedingStatuses = ['bertelur', 'mengeram', 'berhasil', 'gagal'];
            
            if (in_array($status, $breedingStatuses)) {
                $jantanId = $this->input('indukan_jantan_id', is_object($kandang) ? $kandang->indukan_jantan_id : null);
                $betinaId = $this->input('indukan_betina_id', is_object($kandang) ? $kandang->indukan_betina_id : null);
                
                if (empty($jantanId) || empty($betinaId)) {
                    $validator->errors()->add('status', 'Status kandang hanya dapat diubah ke fase breeding (' . $status . ') jika terdapat pasangan Indukan Jantan dan Betina yang lengkap di kandang.');
                }
            }
        });
    }
}
