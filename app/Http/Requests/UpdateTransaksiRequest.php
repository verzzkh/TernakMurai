<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransaksiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

   public function rules(): array
{
    return [
        'tanggal'   => 'required|date',
        'kategori'  => 'required|string|max:100',
        'nama_item' => 'required|string|max:255',
        'deskripsi' => 'nullable|string|max:500',
        'jumlah'    => 'required|numeric|min:0',
        // Tipe tidak wajib, biarkan tetap nilai lama
        'tipe'      => 'sometimes|in:pemasukan,pengeluaran',
    ];
}

}
