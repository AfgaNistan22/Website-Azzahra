<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PelangganStoreRequest extends FormRequest
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
                'max:255',
            ],

            'tgl_lahir' => [
                'required',
                'date',
            ],

            'no_telepon' => [
                'required',
                'string',
                'max:20',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'alamat' => [
                'required',
                'string',
            ],

            'foto' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],

            // Status member pelanggan
            'member' => [
                'required',
                'in:Ya,Tidak',
            ],

            // Jumlah kedatangan pelanggan
            'jumlah_kedatangan' => [
                'required',
                'integer',
                'min:0',
            ],

            // Tanggal terakhir pelanggan datang
            'terakhir_datang' => [
                'nullable',
                'date',
            ],

        ];
    }

    public function messages(): array
    {
        return [

            'nama.required' =>
                'Nama pelanggan wajib diisi.',

            'nama.string' =>
                'Nama pelanggan harus berupa teks.',

            'nama.max' =>
                'Nama pelanggan maksimal 255 karakter.',

            'tgl_lahir.required' =>
                'Tanggal lahir wajib diisi.',

            'tgl_lahir.date' =>
                'Format tanggal lahir tidak valid.',

            'no_telepon.required' =>
                'Nomor telepon wajib diisi.',

            'no_telepon.max' =>
                'Nomor telepon maksimal 20 karakter.',

            'email.email' =>
                'Format email tidak valid.',

            'email.max' =>
                'Email maksimal 255 karakter.',

            'alamat.required' =>
                'Alamat wajib diisi.',

            'foto.image' =>
                'File yang diupload harus berupa gambar.',

            'foto.mimes' =>
                'Foto harus berformat JPG, JPEG, atau PNG.',

            'foto.max' =>
                'Ukuran foto maksimal 2 MB.',

            'member.required' =>
                'Status member wajib dipilih.',

            'member.in' =>
                'Status member harus berupa Ya atau Tidak.',

            'jumlah_kedatangan.required' =>
                'Jumlah kedatangan wajib diisi.',

            'jumlah_kedatangan.integer' =>
                'Jumlah kedatangan harus berupa angka.',

            'jumlah_kedatangan.min' =>
                'Jumlah kedatangan tidak boleh kurang dari 0.',

            'terakhir_datang.date' =>
                'Format tanggal terakhir datang tidak valid.',

        ];
    }
}