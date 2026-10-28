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
            'daerah' => [
                'nullable',
                'string',
                'max:100',
                Rule::exists('aadk_daerah', 'daerah')->where(
                    fn ($query) => $query->where('negeri', (string) $this->input('negeri')),
                ),
            ],
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
            'daerah.exists' => 'Daerah tidak sah bagi negeri yang dipilih.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $negeri = filled($this->input('negeri')) ? $this->input('negeri') : null;
        $daerah = $this->input('daerah');
        $jenis = $this->input('jenis');

        $this->merge([
            'negeri' => $negeri,
            'daerah' => filled($negeri) && filled($daerah) ? $daerah : null,
            'jenis' => filled($jenis) ? $jenis : HqReportService::TYPE_RINGKASAN,
        ]);
    }

    /**
     * @return array{jenis: string, negeri: string|null, daerah: string|null}
     */
    public function filters(): array
    {
        $validated = $this->validated();

        return [
            'jenis' => (string) ($validated['jenis'] ?? HqReportService::TYPE_RINGKASAN),
            'negeri' => filled($validated['negeri'] ?? null) ? (string) $validated['negeri'] : null,
            'daerah' => filled($validated['daerah'] ?? null) ? (string) $validated['daerah'] : null,
        ];
    }
}
