<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'token' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:255', 'confirmed', Password::defaults()],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Emel diperlukan.',
            'email.email' => 'Sila masukkan emel yang sah.',
            'password.required' => 'Kata laluan baharu diperlukan.',
            'password.min' => 'Kata laluan mestilah sekurang-kurangnya 8 aksara.',
            'password.mixed' => 'Kata laluan mesti mengandungi huruf besar dan kecil.',
            'password.numbers' => 'Kata laluan mesti mengandungi sekurang-kurangnya satu nombor.',
            'password.confirmed' => 'Pengesahan kata laluan tidak sepadan.',
        ];
    }
}
