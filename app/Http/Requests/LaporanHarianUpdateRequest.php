<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LaporanHarianUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Nama parameter route harus sama dengan di routes: {laporan_harian}
        $laporan = $this->route('laporan_harian');

        $id = is_object($laporan) ? $laporan->id : $laporan;

        return [
            'tanggal' => [
                'required',
                'date',
                Rule::unique('laporan_harian', 'tanggal')->ignore($id),
            ],

            'status' => ['required', 'in:Draft,Final'],

            'catatan' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'tanggal' => 'tanggal laporan',
            'status'  => 'status',
            'catatan' => 'catatan',
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal.required' => 'Tanggal laporan wajib dipilih.',
            'tanggal.date'     => 'Format tanggal laporan tidak valid.',
            'tanggal.unique'   => 'Tanggal tersebut sudah digunakan oleh laporan harian lain.',

            'status.required' => 'Status laporan wajib dipilih.',
            'status.in'       => 'Status laporan harus Draft atau Final.',

            'catatan.string' => 'Catatan harus berupa teks.',
        ];
    }
}