<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAnakanRequest extends FormRequest
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
        'jenis_kelamin' => 'sometimes|in:jantan,betina,tidak_diketahui',
        'nomor_ring' => 'sometimes|nullable|string|max:50',
        'harga' => 'sometimes|nullable|integer|min:0',
        'deskripsi_karakteristik' => 'sometimes|nullable|string|max:2000',
        'foto_anakan' => 'sometimes|nullable|image|mimes:jpeg,jpg,png|max:5020',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid.',
            'deskripsi_karakteristik.max' => 'Deskripsi karakteristik maksimal 2000 karakter.',
            'foto_anakan.image' => 'File harus berupa gambar.',
            'foto_anakan.mimes' => 'Format gambar harus jpeg, jpg, atau png.',
            'foto_anakan.max' => 'Ukuran gambar maksimal 5MB.',
        ];
    }
}
