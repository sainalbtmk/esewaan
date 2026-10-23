<?php

namespace App\Http\Requests;

use App\Support\MalaysianStates;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'negeri' => ['nullable', 'string', Rule::in(MalaysianStates::values())],
            'current_password' => ['nullable', 'current_password', Rule::requiredIf(function () use ($user): bool {
                $emailChanged = strcasecmp((string) $this->input('email'), (string) $user->email) !== 0;

                return $emailChanged || $this->filled('password');
            })],
            'password' => ['nullable', 'string', 'confirmed', Password::defaults()],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama diperlukan.',
            'email.required' => 'Emel diperlukan.',
            'email.unique' => 'Emel ini telah digunakan.',
            'current_password.required' => 'Kata laluan semasa diperlukan untuk menukar emel atau kata laluan.',
            'current_password.current_password' => 'Kata laluan semasa tidak sah.',
            'negeri.in' => 'Negeri tidak sah.',
            'password.confirmed' => 'Kata laluan tidak sama.',
            'password.min' => 'Kata laluan sekurang-kurangnya 8 aksara.',
            'password.mixed' => 'Kata laluan mesti mengandungi huruf besar dan kecil.',
            'password.numbers' => 'Kata laluan mesti mengandungi sekurang-kurangnya satu nombor.',
        ];
    }
}
