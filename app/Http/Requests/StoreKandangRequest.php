<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreKandangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

public function rules(): array
{
    $peternakId = Auth::user()?->peternak?->id;

    return [
        'nomor_kandang' => 'required|string|max:30|unique:kandang,nomor_kandang,NULL,id,peternak_id,'.$peternakId,
        'deskripsi_kandang' => 'nullable|string',
        'status' => 'required|in:kosong,bertelur',
        'indukan_jantan_id' => [
            'nullable',
            'exists:indukan,id',
            Rule::unique('kandang', 'indukan_jantan_id')
                ->where('peternak_id', $peternakId)
                ->whereNull('deleted_at'),
        ],
        'indukan_betina_id' => [
            'nullable',
            'exists:indukan,id',
            Rule::unique('kandang', 'indukan_betina_id')
                ->where('peternak_id', $peternakId)
                ->whereNull('deleted_at'),
        ],
    ];
}

public function withValidator($validator): void
{
    $validator->after(function ($validator) {
        if ($this->input('status') !== 'bertelur') {
            return;
        }

        if (!$this->input('indukan_jantan_id') || !$this->input('indukan_betina_id')) {
            $validator->errors()->add('status', 'Status bertelur hanya dapat dipilih jika terdapat pasangan Indukan Jantan dan Betina yang lengkap.');
        }
    });
}
}
