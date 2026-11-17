<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PromoStoreRequest extends FormRequest
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

            // Kosong (null) = promo berlaku untuk SEMUA perawatan.
            // Bila terisi, id harus ada di tabel perawatan.
            'perawatan_id' => [
                'nullable',
                'integer',
                'exists:perawatan,id',
            ],

            'deskripsi' => [
                'nullable',
                'string',
            ],

            'jenis' => [
                'required',
                'in:Persentase,Nominal',
            ],

            'nilai' => [
                'required',
                'integer',
                'min:0',
            ],

            'tanggal_mulai' => [
                'required',
                'date',
            ],

            'tanggal_selesai' => [
                'required',
                'date',
                'after_or_equal:tanggal_mulai',
            ],

            'status' => [
                'required',
                'in:Aktif,Tidak Aktif',
            ],

            'foto' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            // 0 = semua pelanggan, 1 = khusus member
            'khusus_member' => [
                'required',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama promo wajib diisi.',
            'nama.string' => 'Nama promo harus berupa teks.',
            'nama.max' => 'Nama promo maksimal 255 karakter.',

            'perawatan_id.integer' => 'Perawatan yang dipilih tidak valid.',
            'perawatan_id.exists' => 'Perawatan yang dipilih tidak ditemukan.',

            'deskripsi.string' => 'Deskripsi harus berupa teks.',

            'jenis.required' => 'Jenis promo wajib dipilih.',
            'jenis.in' => 'Jenis promo tidak valid.',

            'nilai.required' => 'Nilai promo wajib diisi.',
            'nilai.integer' => 'Nilai promo harus berupa angka.',
            'nilai.min' => 'Nilai promo tidak boleh kurang dari 0.',

            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.date' => 'Tanggal mulai tidak valid.',

            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.date' => 'Tanggal selesai tidak valid.',
            'tanggal_selesai.after_or_equal' =>
                'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',

            'status.required' => 'Status promo wajib dipilih.',
            'status.in' => 'Status promo tidak valid.',

            'khusus_member.required' => 'Pilih peserta promo terlebih dahulu.',
            'khusus_member.boolean' => 'Peserta promo tidak valid.',

            'foto.image' => 'File foto harus berupa gambar.',
            'foto.mimes' => 'Format foto harus JPG, JPEG, PNG, atau WEBP.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ];
    }
}