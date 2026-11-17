<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservasiStoreRequest extends FormRequest
{
    /**
     * Menentukan apakah user boleh melakukan request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi.
     */
    public function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Pelanggan
            |--------------------------------------------------------------------------
            */
            'pelanggan_id' => [
                'required',
                'integer',
                'exists:pelanggan,id',
            ],


            /*
            |--------------------------------------------------------------------------
            | Perawatan
            |--------------------------------------------------------------------------
            |
            | Format dari Blade:
            |
            | perawatan_ids[1] = 1
            | perawatan_ids[2] = 1
            |
            | Key = ID perawatan
            | Value = penanda perawatan dipilih
            |
            */
            'perawatan_ids' => [
                'required',
                'array',
                'min:1',
            ],

            /*
            | Value dari associative array.
            |
            | Contoh:
            | perawatan_ids[1] = 1
            | perawatan_ids[2] = 1
            |
            */
            'perawatan_ids.*' => [
                'required',
                'integer',
                'min:1',
            ],


            /*
            |--------------------------------------------------------------------------
            | Jumlah Perawatan
            |--------------------------------------------------------------------------
            |
            | Format:
            |
            | jumlah_perawatan[1] = 1
            | jumlah_perawatan[2] = 2
            |
            */
            'jumlah_perawatan' => [
                'nullable',
                'array',
            ],

            'jumlah_perawatan.*' => [
                'nullable',
                'integer',
                'min:1',
                'max:10',
            ],


            /*
            |--------------------------------------------------------------------------
            | Pegawai / Terapis
            |--------------------------------------------------------------------------
            */
            'pegawai_id' => [
                'required',
                'integer',
                'exists:pegawai,id',
            ],


            /*
            |--------------------------------------------------------------------------
            | Tanggal
            |--------------------------------------------------------------------------
            */
            'tanggal' => [
                'required',
                'date',
                'after_or_equal:today',
            ],


            /*
            |--------------------------------------------------------------------------
            | Jam Mulai
            |--------------------------------------------------------------------------
            */
            'jam_mulai' => [
                'required',
                'date_format:H:i',
            ],


            /*
            |--------------------------------------------------------------------------
            | Catatan
            |--------------------------------------------------------------------------
            */
            'catatan' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }


    /**
     * Pesan error dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [

            'pelanggan_id.required' =>
                'Pelanggan wajib dipilih.',

            'pelanggan_id.integer' =>
                'Data pelanggan tidak valid.',

            'pelanggan_id.exists' =>
                'Pelanggan yang dipilih tidak ditemukan.',


            'perawatan_ids.required' =>
                'Minimal pilih satu perawatan.',

            'perawatan_ids.array' =>
                'Data perawatan tidak valid.',

            'perawatan_ids.min' =>
                'Minimal pilih satu perawatan.',

            'perawatan_ids.*.required' =>
                'Data perawatan tidak valid.',

            'perawatan_ids.*.integer' =>
                'Data perawatan tidak valid.',

            'perawatan_ids.*.min' =>
                'Data perawatan tidak valid.',


            'jumlah_perawatan.array' =>
                'Data jumlah perawatan tidak valid.',

            'jumlah_perawatan.*.integer' =>
                'Jumlah perawatan harus berupa angka.',

            'jumlah_perawatan.*.min' =>
                'Jumlah perawatan minimal 1.',

            'jumlah_perawatan.*.max' =>
                'Jumlah perawatan maksimal 10.',


            'pegawai_id.required' =>
                'Pegawai/terapis wajib dipilih.',

            'pegawai_id.integer' =>
                'Data pegawai tidak valid.',

            'pegawai_id.exists' =>
                'Pegawai/terapis yang dipilih tidak ditemukan.',


            'tanggal.required' =>
                'Tanggal reservasi wajib diisi.',

            'tanggal.date' =>
                'Format tanggal tidak valid.',

            'tanggal.after_or_equal' =>
                'Tanggal reservasi tidak boleh sebelum hari ini.',


            'jam_mulai.required' =>
                'Jam mulai wajib diisi.',

            'jam_mulai.date_format' =>
                'Format jam mulai harus HH:MM.',


            'catatan.string' =>
                'Catatan harus berupa teks.',

            'catatan.max' =>
                'Catatan maksimal 1000 karakter.',
        ];
    }


    /**
     * Nama atribut agar pesan validasi lebih mudah dipahami.
     */
    public function attributes(): array
    {
        return [

            'pelanggan_id' =>
                'pelanggan',

            'perawatan_ids' =>
                'perawatan',

            'jumlah_perawatan' =>
                'jumlah perawatan',

            'pegawai_id' =>
                'pegawai/terapis',

            'tanggal' =>
                'tanggal reservasi',

            'jam_mulai' =>
                'jam mulai',

            'catatan' =>
                'catatan',
        ];
    }
}