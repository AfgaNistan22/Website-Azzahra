<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class ChangePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'old_password' => 'required|min:8',
            'new_password' => 'required|min:8|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'old_password.required' => 'Password lama wajib diisi.',
            'old_password.min' => 'Password lama minimal 8 karakter.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ];
    }

    /**
     * Validasi tambahan setelah validasi utama.
     * Digunakan untuk memastikan password baru tidak sama dengan password lama.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $user = auth()->user();

            // ✔ Password lama harus benar
            if (!Hash::check($this->old_password, $user->password)) {
                $validator->errors()->add('old_password', 'Password lama tidak sesuai.');
            }

            // ✔ Password baru tidak boleh sama dengan password lama
            if (Hash::check($this->new_password, $user->password)) {
                $validator->errors()->add('new_password', 'Password baru tidak boleh sama dengan password lama.');
            }
        });
    }
}
