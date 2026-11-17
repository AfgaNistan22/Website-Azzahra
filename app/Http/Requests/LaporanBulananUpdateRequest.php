<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LaporanBulananUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $periode = $this->input('periode');

        if (is_string($periode) && preg_match('/^\d{4}-\d{2}$/', $periode)) {
            $this->merge(['periode' => $periode . '-01']);
        }
    }

    public function rules(): array
    {
        // Nama parameter route harus sama: {laporan_bulanan}
        $laporan = $this->route('laporan_bulanan');
        $id      = is_object($laporan) ? $laporan->id : $laporan;

        return [
            'periode' => [
                'required',
                'date_format:Y-m-d',
                'before_or_equal:today',
                Rule::unique('laporan_bulanan', 'periode')->ignore($id),
            ],
            'status'  => ['required', 'in:Draft,Final'],
            'catatan' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'periode' => 'periode laporan',
            'status'  => 'status',
            'catatan' => 'catatan',
        ];
    }

    public function messages(): array
    {
        return [
            'periode.required'         => 'Periode laporan wajib dipilih.',
            'periode.date_format'      => 'Format periode laporan tidak valid.',
            'periode.before_or_equal'  => 'Periode laporan tidak boleh melewati bulan ini.',
            'periode.unique'           => 'Periode tersebut sudah digunakan oleh laporan bulanan lain.',

            'status.required' => 'Status laporan wajib dipilih.',
            'status.in'       => 'Status laporan harus Draft atau Final.',

            'catatan.string' => 'Catatan harus berupa teks.',
        ];
    }
}