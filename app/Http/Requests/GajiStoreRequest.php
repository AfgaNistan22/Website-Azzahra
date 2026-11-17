<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GajiStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'pegawai_id' => [
                'required',
                'exists:pegawai,id',
            ],

            'bulan' => [
                'required',
                'integer',
                'between:1,12',
            ],

            'tahun' => [
                'required',
                'integer',
                'min:2020',
                'max:2100',
            ],

            'gaji_pokok' => [
                'required',
                'integer',
                'min:0',
            ],

            'tunjangan' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'potongan' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'status' => [
                'required',
                'in:Belum Dibayar,Dibayar',
            ],

            'tanggal_bayar' => [
                'nullable',
                'date',
            ],

            'catatan' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'pegawai_id' => 'pegawai',
            'bulan' => 'bulan',
            'tahun' => 'tahun',
            'gaji_pokok' => 'gaji pokok',
            'tunjangan' => 'tunjangan',
            'potongan' => 'potongan',
            'status' => 'status pembayaran',
            'tanggal_bayar' => 'tanggal bayar',
            'catatan' => 'catatan',
        ];
    }
}