<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnakanDariKandangRequest extends FormRequest
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
        $rules = [
            'sumber_anakan' => 'required|in:peternakan',
            'kandang_id' => 'required|exists:kandang,id',
            'jumlah_anakan' => 'required|integer|min:1|max:5',
            'catatan' => 'nullable|string|max:1000',
        ];

        // Single anakan rules
        if ($this->input('jumlah_anakan') == 1) {
            $rules['jenis_kelamin'] = 'required|in:jantan,betina,tidak_diketahui';
            $rules['foto_anakan'] = 'nullable|image|mimes:jpeg,jpg,png|max:2048';
        } else {
            // Multiple anakan rules
            $jumlahAnakan = (int) $this->input('jumlah_anakan', 1);
            for ($i = 1; $i <= $jumlahAnakan; $i++) {
                $rules["jenis_kelamin_{$i}"] = 'required|in:jantan,betina,tidak_diketahui';
                $rules["foto_anakan_{$i}"] = 'nullable|image|mimes:jpeg,jpg,png|max:2048';
            }
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'sumber_anakan.required' => 'Sumber anakan harus dipilih.',
            'sumber_anakan.in' => 'Sumber anakan tidak valid.',
            'kandang_id.required' => 'Kandang harus dipilih.',
            'kandang_id.exists' => 'Kandang yang dipilih tidak ditemukan.',
            'jumlah_anakan.required' => 'Jumlah anakan harus diisi.',
            'jumlah_anakan.integer' => 'Jumlah anakan harus berupa angka.',
            'jumlah_anakan.min' => 'Jumlah anakan minimal 1 ekor.',
            'jumlah_anakan.max' => 'Jumlah anakan maksimal 5 ekor.',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid.',
            'foto_anakan.image' => 'File harus berupa gambar.',
            'foto_anakan.mimes' => 'Format gambar harus jpeg, jpg, atau png.',
            'foto_anakan.max' => 'Ukuran gambar maksimal 2MB.',
            'catatan.max' => 'Catatan maksimal 1000 karakter.',
        ];
    }
}
