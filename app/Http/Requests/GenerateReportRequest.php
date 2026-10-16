<?php

namespace App\Http\Requests;

use App\Services\HqReportService;
use App\Support\MalaysianStates;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GenerateReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdminHq() === true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'jenis' => ['nullable', 'string', Rule::in(HqReportService::typeValues())],
            'negeri' => ['nullable', 'string', Rule::in(MalaysianStates::values())],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'jenis.in' => 'Jenis laporan tidak sah.',
            'negeri.in' => 'Negeri tidak sah.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $negeri = $this->input('negeri');
        $jenis = $this->input('jenis');

        $this->merge([
            'negeri' => filled($negeri) ? $negeri : null,
            'jenis' => filled($jenis) ? $jenis : HqReportService::TYPE_RINGKASAN,
        ]);
    }

    /**
     * @return array{jenis: string, negeri: string|null}
     */
    public function filters(): array
    {
        $validated = $this->validated();

        return [
            'jenis' => (string) ($validated['jenis'] ?? HqReportService::TYPE_RINGKASAN),
            'negeri' => filled($validated['negeri'] ?? null) ? (string) $validated['negeri'] : null,
        ];
    }
}
