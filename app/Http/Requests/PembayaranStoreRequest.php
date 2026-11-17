<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PembayaranStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reservasi_id' => [
                'required',
                'exists:reservasi,id',
            ],

            'tanggal_bayar' => [
                'required',
                'date',
            ],

            'metode_pembayaran' => [
                'required',
                'in:Cash,Transfer,E-Wallet',
            ],

            'catatan' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'reservasi_id.required' =>
                'Reservasi wajib dipilih.',

            'reservasi_id.exists' =>
                'Data reservasi tidak ditemukan.',

            'tanggal_bayar.required' =>
                'Tanggal pembayaran wajib diisi.',

            'tanggal_bayar.date' =>
                'Format tanggal pembayaran tidak valid.',

            'metode_pembayaran.required' =>
                'Metode pembayaran wajib dipilih.',

            'metode_pembayaran.in' =>
                'Metode pembayaran tidak valid.',

            'catatan.string' =>
                'Catatan harus berupa teks.',

            'catatan.max' =>
                'Catatan maksimal 1000 karakter.',
        ];
    }
}