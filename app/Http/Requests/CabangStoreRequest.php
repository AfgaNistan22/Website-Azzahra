<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CabangStoreRequest extends FormRequest
{
    /**
     * Menentukan apakah user boleh melakukan request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi data cabang.
     */
    public function rules(): array
    {
        return [
            'kode_cabang' => [
                'required',
                'string',
                'max:20',
                'unique:cabang,kode_cabang',
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
                'Kode cabang sudah digunakan.',

            'kode_cabang.max' =>
                'Kode cabang maksimal 20 karakter.',

            'nama_cabang.required' =>
                'Nama cabang wajib diisi.',

            'nama_cabang.max' =>
                'Nama cabang maksimal 100 karakter.',

            'alamat_cabang.required' =>
                'Alamat cabang wajib diisi.',

            'alamat_cabang.max' =>
                'Alamat cabang maksimal 255 karakter.',

            'no_telepon.required' =>
                'Nomor telepon wajib diisi.',

            'no_telepon.max' =>
                'Nomor telepon maksimal 20 karakter.',

            'email.email' =>
                'Format email tidak valid.',

            'email.max' =>
                'Email maksimal 100 karakter.',
        ];
    }

    /**
     * Nama atribut agar pesan lebih mudah dibaca.
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