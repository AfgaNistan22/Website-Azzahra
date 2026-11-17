<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PengeluaranUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tanggal' => ['required', 'date'],
            'kategori' => ['required', 'string', 'max:100'],
            'sumber' => ['nullable', 'string', 'max:255'],
            'nominal' => ['required', 'integer', 'min:1'],
            'metode_pembayaran' => ['nullable', 'in:Cash,Transfer,E-Wallet'],
            'status' => ['required', 'in:Diterima,Dibatalkan'],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'tanggal' => 'tanggal',
            'kategori' => 'kategori',
            'sumber' => 'sumber',
            'nominal' => 'nominal',
            'metode_pembayaran' => 'metode pembayaran',
            'status' => 'status',
            'keterangan' => 'keterangan',
        ];
    }
}
