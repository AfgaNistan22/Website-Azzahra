<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PajakUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $pajak = $this->route('pajak');

        $id = is_object($pajak)
            ? $pajak->id
            : $pajak;

        return [
            'pegawai_id' => [
                'required',
                'integer',
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

            'penghasilan_bruto' => [
                'required',
                'integer',
                'min:0',
            ],

            'ptkp' => [
                'required',
                'integer',
                'min:0',
            ],

            'pkp' => [
                'required',
                'integer',
                'min:0',
            ],

            'tarif_pajak' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'jumlah_pajak' => [
                'required',
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
            'penghasilan_bruto' => 'penghasilan bruto',
            'ptkp' => 'PTKP',
            'pkp' => 'PKP',
            'tarif_pajak' => 'tarif pajak',
            'jumlah_pajak' => 'jumlah pajak',
            'status' => 'status',
            'tanggal_bayar' => 'tanggal bayar',
            'catatan' => 'catatan',
        ];
    }
}