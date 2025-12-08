<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransaksiRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'tanggal'   => 'required|date',
            'tipe'      => 'required|in:pemasukan,pengeluaran',
            'kategori'  => 'required|string|max:100',
            'nama_item' => 'required|string|max:255',
            'jumlah'    => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string|max:500',
            'anakan_id' => 'nullable|exists:anakan,id',
            'indukan_id'=> 'nullable|exists:indukan,id',
        ];
    }
}
