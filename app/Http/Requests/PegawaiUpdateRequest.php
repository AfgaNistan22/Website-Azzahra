<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PegawaiUpdateRequest extends FormRequest
{
    /**
     * Menentukan apakah user diperbolehkan melakukan request.
     */
    public function authorize(): bool
    {
        return true;
    }


    /**
     * Aturan validasi data pegawai.
     */
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


    /**
     * Pesan validasi.
     */
    public function messages(): array
    {
        return [

            // Nama
            'nama.required' =>
                'Nama pegawai wajib diisi.',

            'nama.string' =>
                'Nama pegawai harus berupa teks.',

            'nama.max' =>
                'Nama pegawai maksimal 255 karakter.',


            // Nomor Telepon
            'no_telepon.string' =>
                'Nomor telepon harus berupa teks.',

            'no_telepon.max' =>
                'Nomor telepon maksimal 20 karakter.',


            // Email
            'email.email' =>
                'Format email tidak valid.',

            'email.max' =>
                'Email maksimal 255 karakter.',


            // Alamat
            'alamat.string' =>
                'Alamat harus berupa teks.',


            // Foto
            'foto.image' =>
                'File harus berupa gambar.',

            'foto.mimes' =>
                'Foto harus berformat JPG, JPEG, atau PNG.',

            'foto.max' =>
                'Ukuran foto maksimal 2 MB.',


            // Status
            'status.required' =>
                'Status pegawai wajib dipilih.',

            'status.in' =>
                'Status pegawai harus Aktif atau Tidak Aktif.',
        ];
    }
}