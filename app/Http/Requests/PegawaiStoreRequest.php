<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PegawaiStoreRequest extends FormRequest
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
                'max:255'
            ],

            'no_telepon' => [
                'nullable',
                'string',
                'max:20'
            ],

            'email' => [
                'nullable',
                'email',
                'max:255'
            ],

            'alamat' => [
                'nullable',
                'string'
            ],

            'foto' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048'
            ],

            'status' => [
                'required',
                'in:Aktif,Tidak Aktif'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' =>
                'Nama pegawai wajib diisi.',

            'nama.string' =>
                'Nama pegawai harus berupa teks.',

            'nama.max' =>
                'Nama pegawai maksimal 255 karakter.',

            'email.email' =>
                'Format email tidak valid.',

            'email.max' =>
                'Email maksimal 255 karakter.',

            'foto.image' =>
                'File harus berupa gambar.',

            'foto.mimes' =>
                'Foto harus berformat JPG, JPEG, atau PNG.',

            'foto.max' =>
                'Ukuran foto maksimal 2 MB.',

            'status.required' =>
                'Status pegawai wajib dipilih.',

            'status.in' =>
                'Status pegawai harus Aktif atau Tidak Aktif.',
        ];
    }
}