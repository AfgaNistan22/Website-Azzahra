<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KategoriPerawatanStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => [
                'required',
                'string',
                'max:100',
                'unique:kategori_perawatan,nama',
            ],

            'deskripsi' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:Aktif,Tidak Aktif',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'nama' => 'nama kategori',
            'deskripsi' => 'deskripsi',
            'status' => 'status',
        ];
    }
}