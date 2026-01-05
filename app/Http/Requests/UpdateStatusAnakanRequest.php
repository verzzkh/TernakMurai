<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusAnakanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'status_pertumbuhan' => 'required|in:trotol,pastol,lomba',
            'harga' => 'required|integer|min:0',
            'catatan_perubahan' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'status_pertumbuhan.required' => 'Status pertumbuhan harus dipilih.',
            'status_pertumbuhan.in' => 'Status pertumbuhan tidak valid.',
            'harga.required' => 'Harga harus diisi.',
            'harga.integer' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga tidak boleh negatif.',
            'catatan_perubahan.max' => 'Catatan perubahan maksimal 500 karakter.',
        ];
    }
}
