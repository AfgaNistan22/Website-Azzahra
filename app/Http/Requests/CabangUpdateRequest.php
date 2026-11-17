<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CabangUpdateRequest extends FormRequest
{
    /**
     * Menentukan apakah user boleh melakukan request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi update cabang.
     */
    public function rules(): array
    {
        /*
         * Ambil ID cabang dari URL.
         */
        $id = $this->route('cabang');

        return [
            'kode_cabang' => [
                'required',
                'string',
                'max:20',
                Rule::unique('cabang', 'kode_cabang')
                    ->ignore($id),
            ],

            'nama_cabang' => [
                'required',
                'string',
                'max:100',
            ],

            'alamat_cabang' => [
                'required',
                'string',
                'max:255',
            ],

            'no_telepon' => [
                'required',
                'string',
                'max:20',
            ],

            'email' => [
                'nullable',
                'email',
                'max:100',
            ],
        ];
    }

    /**
     * Pesan validasi.
     */
    public function messages(): array
    {
        return [
            'kode_cabang.required' =>
                'Kode cabang wajib diisi.',

            'kode_cabang.unique' =>
                'Kode cabang sudah digunakan oleh cabang lain.',

            'nama_cabang.required' =>
                'Nama cabang wajib diisi.',

            'alamat_cabang.required' =>
                'Alamat cabang wajib diisi.',

            'no_telepon.required' =>
                'Nomor telepon wajib diisi.',

            'email.email' =>
                'Format email tidak valid.',
        ];
    }

    /**
     * Nama atribut.
     */
    public function attributes(): array
    {
        return [
            'kode_cabang' => 'kode cabang',
            'nama_cabang' => 'nama cabang',
            'alamat_cabang' => 'alamat cabang',
            'no_telepon' => 'nomor telepon',
            'email' => 'email',
        ];
    }
}