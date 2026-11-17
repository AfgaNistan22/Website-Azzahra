<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LaporanBulananStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** Input type="month" mengirim "2026-10"; diubah ke "2026-10-01". */
    protected function prepareForValidation(): void
    {
        $periode = $this->input('periode');

        if (is_string($periode) && preg_match('/^\d{4}-\d{2}$/', $periode)) {
            $this->merge(['periode' => $periode . '-01']);
        }
    }

    public function rules(): array
    {
        return [
            'periode' => [
                'required',
                'date_format:Y-m-d',
                'before_or_equal:today',
                Rule::unique('laporan_bulanan', 'periode'),
            ],
            'status'  => ['required', 'in:Draft,Final'],
            'catatan' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'periode' => 'periode laporan',
            'status'  => 'status laporan',
            'catatan' => 'catatan',
        ];
    }

    public function messages(): array
    {
        return [
            'periode.required'         => 'Periode laporan wajib diisi.',
            'periode.date_format'      => 'Format periode laporan tidak valid.',
            'periode.before_or_equal'  => 'Periode laporan tidak boleh melewati bulan ini.',
            'periode.unique'           => 'Laporan untuk bulan tersebut sudah ada. Silakan edit laporan yang lama.',

            'status.required' => 'Status laporan wajib dipilih.',
            'status.in'       => 'Status laporan harus Draft atau Final.',

            'catatan.string' => 'Catatan harus berupa teks.',
        ];
    }
}