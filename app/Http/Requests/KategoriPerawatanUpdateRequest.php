<?php

namespace App\Http\Requests;

use App\Models\KategoriPerawatan;
use Illuminate\Foundation\Http\FormRequest;

class KategoriPerawatanUpdateRequest extends FormRequest
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

                function ($attribute, $value, $fail) {
                    $kategori = $this->route('kategori');

                    $id = is_object($kategori)
                        ? $kategori->id
                        : $kategori;

                    $namaSudahDigunakan =
                        KategoriPerawatan::whereRaw(
                            'LOWER(nama) = ?',
                            [strtolower(trim($value))]
                        )
                        ->where('id', '!=', $id)
                        ->exists();

                    if ($namaSudahDigunakan) {
                        $fail(
                            'Nama kategori sudah digunakan oleh kategori lain.'
                        );
                    }
                },
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