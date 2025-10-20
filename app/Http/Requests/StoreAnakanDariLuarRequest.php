<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnakanDariLuarRequest extends FormRequest
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
            'sumber_anakan' => 'required|in:luar',
            'jenis_kelamin_luar' => 'required|in:jantan,betina,tidak_diketahui',
            'foto_anakan_luar' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'tanggal_lahir' => 'required|date|before_or_equal:today',
            'asal_penjual' => 'required|string|max:255',
            'tanggal_pembelian' => 'required|date|before_or_equal:today',
            'indukan_jantan_info' => 'nullable|string|max:255',
            'indukan_betina_info' => 'nullable|string|max:255',
            'status' => 'required|in:trotol,pastol,lomba',
            'harga' => 'required|integer|min:0',
            'harga_beli' => 'required|integer|min:0',
            'catatan_luar' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'sumber_anakan.required' => 'Sumber anakan harus dipilih.',
            'sumber_anakan.in' => 'Sumber anakan tidak valid.',
            'jenis_kelamin_luar.required' => 'Jenis kelamin harus dipilih.',
            'jenis_kelamin_luar.in' => 'Jenis kelamin tidak valid.',
            'foto_anakan_luar.required' => 'Foto anakan harus diupload.',
            'foto_anakan_luar.image' => 'File harus berupa gambar.',
            'foto_anakan_luar.mimes' => 'Format gambar harus jpeg, jpg, atau png.',
            'foto_anakan_luar.max' => 'Ukuran gambar maksimal 2MB.',
            'tanggal_lahir.required' => 'Tanggal lahir harus diisi.',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid.',
            'tanggal_lahir.before_or_equal' => 'Tanggal lahir tidak boleh lebih dari hari ini.',
            'asal_penjual.required' => 'Asal/penjual harus diisi.',
            'asal_penjual.max' => 'Asal/penjual maksimal 255 karakter.',
            'tanggal_pembelian.required' => 'Tanggal pembelian harus diisi.',
            'tanggal_pembelian.date' => 'Format tanggal pembelian tidak valid.',
            'tanggal_pembelian.before_or_equal' => 'Tanggal pembelian tidak boleh lebih dari hari ini.',
            'indukan_jantan_info.max' => 'Info indukan jantan maksimal 255 karakter.',
            'indukan_betina_info.max' => 'Info indukan betina maksimal 255 karakter.',
            'status.required' => 'Status pertumbuhan harus dipilih.',
            'status.in' => 'Status pertumbuhan tidak valid.',
            'harga.required' => 'Harga harus diisi.',
            'harga.integer' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga tidak boleh negatif.',
            'harga_beli.required' => 'Harga beli harus diisi.',
            'harga_beli.integer' => 'Harga beli harus berupa angka.',
            'harga_beli.min' => 'Harga beli tidak boleh negatif.',
            'catatan_luar.max' => 'Catatan maksimal 1000 karakter.',
        ];
    }
}
