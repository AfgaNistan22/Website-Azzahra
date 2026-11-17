<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PembayaranUpdateRequest extends FormRequest
{
    /**
     * Izinkan request
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Rules validasi
     */
    public function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | TANGGAL BAYAR
            |--------------------------------------------------------------------------
            */

            'tanggal_bayar' => [
                'required',
                'date',
            ],


            /*
            |--------------------------------------------------------------------------
            | METODE PEMBAYARAN
            |--------------------------------------------------------------------------
            */

            'metode_pembayaran' => [
                'required',
                Rule::in([
                    'Cash',
                    'Transfer',
                    'E-Wallet',
                ]),
            ],


            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            'status' => [
                'required',
                Rule::in([
                    'Menunggu',
                    'Lunas',
                    'Dibatalkan',
                ]),
            ],


            /*
            |--------------------------------------------------------------------------
            | PROMO
            |--------------------------------------------------------------------------
            */

            'promo_id' => [
                'nullable',
                'integer',
                'exists:promo,id',
            ],


            /*
            |--------------------------------------------------------------------------
            | NOMINAL
            |--------------------------------------------------------------------------
            |
            | Nilai ini hanya sebagai data pendukung dari form.
            | Controller tetap menghitung ulang.
            |
            */

            'jumlah_sebelum_diskon' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'jumlah_diskon' => [
                'nullable',
                'numeric',
                'min:0',
            ],


            /*
            |--------------------------------------------------------------------------
            | CATATAN
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
     * Pesan validasi
     */
    public function messages(): array
    {
        return [

            'tanggal_bayar.required' =>
                'Tanggal pembayaran wajib diisi.',

            'tanggal_bayar.date' =>
                'Format tanggal pembayaran tidak valid.',

            'metode_pembayaran.required' =>
                'Metode pembayaran wajib dipilih.',

            'metode_pembayaran.in' =>
                'Metode pembayaran tidak valid.',

            'status.required' =>
                'Status pembayaran wajib dipilih.',

            'status.in' =>
                'Status pembayaran tidak valid.',

            'promo_id.exists' =>
                'Promo yang dipilih tidak ditemukan.',

            'jumlah_sebelum_diskon.numeric' =>
                'Jumlah sebelum diskon harus berupa angka.',

            'jumlah_diskon.numeric' =>
                'Jumlah diskon harus berupa angka.',

            'catatan.max' =>
                'Catatan maksimal 1000 karakter.',
        ];
    }
}