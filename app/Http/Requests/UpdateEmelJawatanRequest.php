<?php

namespace App\Http\Requests;

use App\Models\EmelJawatan;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEmelJawatanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdminHq() ?? false;
    }

    protected function prepareForValidation(): void
    {
        $emails = $this->input('emails');

        if (! is_array($emails)) {
            return;
        }

        $this->merge([
            'emails' => collect($emails)
                ->map(fn (mixed $email): mixed => is_string($email) ? trim($email) : $email)
                ->all(),
        ]);
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        $rules = [
            'emails' => ['required', 'array'],
        ];

        foreach (EmelJawatan::query()->orderBy('susunan')->pluck('kod') as $kod) {
            $rules['emails.'.$kod] = ['required', 'string', 'email', 'max:255'];
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'emails.required' => 'Senarai emel jawatan diperlukan.',
            'emails.array' => 'Senarai emel jawatan tidak sah.',
            'emails.*.required' => 'Emel diperlukan.',
            'emails.*.email' => 'Emel tidak sah.',
            'emails.*.max' => 'Emel tidak boleh melebihi 255 aksara.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return EmelJawatan::query()
            ->pluck('nama_jawatan', 'kod')
            ->mapWithKeys(fn (string $nama, string $kod): array => ['emails.'.$kod => 'emel '.$nama])
            ->all();
    }
}
