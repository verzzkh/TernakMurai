<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SellAnakanRequest extends FormRequest
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
            'harga_jual' => 'required|integer|min:0',
            'tanggal_jual' => 'required|date|before_or_equal:today',
            'catatan_penjualan' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'harga_jual.required' => 'Harga jual harus diisi.',
            'harga_jual.integer' => 'Harga jual harus berupa angka.',
            'harga_jual.min' => 'Harga jual tidak boleh negatif.',
            'tanggal_jual.required' => 'Tanggal jual harus diisi.',
            'tanggal_jual.date' => 'Format tanggal jual tidak valid.',
            'tanggal_jual.before_or_equal' => 'Tanggal jual tidak boleh lebih dari hari ini.',
            'catatan_penjualan.max' => 'Catatan penjualan maksimal 1000 karakter.',
        ];
    }
}
