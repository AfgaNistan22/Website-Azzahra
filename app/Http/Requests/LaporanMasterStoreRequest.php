<?php

namespace App\Http\Requests;

use App\Services\LaporanMasterService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LaporanMasterStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $service  = app(LaporanMasterService::class);
        $kategori = array_keys($service->daftarKategori());

        return [
            'kategori' => [
                'bail',
                'required',
                Rule::in($kategori),
                // Satu kategori + rentang tanggal hanya boleh punya satu laporan
                function ($attribute, $value, $fail) use ($service) {
                    if ($service->sudahAda(
                        $value,
                        $this->tanggalAtauNull('tanggal_mulai'),
                        $this->tanggalAtauNull('tanggal_selesai')
                    )) {
                        $fail('Laporan untuk kategori dan rentang tanggal tersebut sudah ada. Silakan edit laporan yang lama.');
                    }
                },
            ],

            'tanggal_mulai' => ['nullable', 'date'],

            'tanggal_selesai' => [
                'nullable',
                'date',
                Rule::when($this->filled('tanggal_mulai'), ['after_or_equal:tanggal_mulai']),
            ],

            'status' => ['required', 'in:Draft,Final'],

            'catatan' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'kategori'        => 'kategori laporan',
            'tanggal_mulai'   => 'tanggal mulai',
            'tanggal_selesai' => 'tanggal selesai',
            'status'          => 'status laporan',
            'catatan'         => 'catatan',
        ];
    }

    public function messages(): array
    {
        return [
            'kategori.required' => 'Kategori laporan wajib dipilih.',
            'kategori.in'       => 'Kategori laporan tidak tersedia.',

            'tanggal_mulai.date'   => 'Format tanggal mulai tidak valid.',
            'tanggal_selesai.date' => 'Format tanggal selesai tidak valid.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',

            'status.required' => 'Status laporan wajib dipilih.',
            'status.in'       => 'Status laporan harus Draft atau Final.',

            'catatan.string' => 'Catatan harus berupa teks.',
        ];
    }

    private function tanggalAtauNull(string $kunci): ?string
    {
        $nilai = $this->input($kunci);

        return is_string($nilai) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $nilai) === 1 ? $nilai : null;
    }
}