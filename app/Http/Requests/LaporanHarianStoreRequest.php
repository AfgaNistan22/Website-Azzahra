<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LaporanHarianStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tanggal' => [
                'required',
                'date',
                // Satu tanggal hanya boleh punya satu laporan (selaras dengan UpdateRequest)
                Rule::unique('laporan_harian', 'tanggal'),
            ],

            'status' => ['required', 'in:Draft,Final'],

            'catatan' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'tanggal' => 'tanggal laporan',
            'status'  => 'status laporan',
            'catatan' => 'catatan',
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal.required' => 'Tanggal laporan wajib diisi.',
            'tanggal.date'     => 'Format tanggal laporan tidak valid.',
            'tanggal.unique'   => 'Laporan untuk tanggal tersebut sudah ada. Silakan edit laporan yang lama.',

            'status.required' => 'Status laporan wajib dipilih.',
            'status.in'       => 'Status laporan harus Draft atau Final.',

            'catatan.string' => 'Catatan harus berupa teks.',
        ];
    }
}