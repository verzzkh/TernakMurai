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
            'jenis_kelamin' => 'required|in:jantan,betina,tidak_diketahui',
            'deskripsi_karakteristik' => 'nullable|string|max:2000',
            'foto_anakan' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
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
            'foto_anakan.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
