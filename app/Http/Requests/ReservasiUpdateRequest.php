<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReservasiUpdateRequest extends FormRequest
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
            | Format:
            |
            | perawatan_ids[1] = 1
            | perawatan_ids[2] = 1
            |
            */
            'perawatan_ids' => [
                'required',
                'array',
                'min:1',
            ],

            'perawatan_ids.*' => [
                'required',
                'integer',
                'min:1',
            ],


            /*
            |--------------------------------------------------------------------------
            | Jumlah Perawatan
            |--------------------------------------------------------------------------
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
            |
            | Untuk UPDATE tidak menggunakan after_or_equal:today
            | agar data reservasi lama tetap dapat diedit.
            |
            */
            'tanggal' => [
                'required',
                'date',
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
            | Status
            |--------------------------------------------------------------------------
            */
            'status' => [
                'required',
                Rule::in([
                    'Menunggu',
                    'Dikonfirmasi',
                    'Selesai',
                    'Dibatalkan',
                ]),
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
     * Pesan error Bahasa Indonesia.
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


            'jam_mulai.required' =>
                'Jam mulai wajib diisi.',

            'jam_mulai.date_format' =>
                'Format jam mulai harus HH:MM.',


            'status.required' =>
                'Status reservasi wajib dipilih.',

            'status.in' =>
                'Status reservasi tidak valid.',


            'catatan.string' =>
                'Catatan harus berupa teks.',

            'catatan.max' =>
                'Catatan maksimal 1000 karakter.',
        ];
    }


    /**
     * Nama atribut.
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

            'status' =>
                'status reservasi',

            'catatan' =>
                'catatan',
        ];
    }
}