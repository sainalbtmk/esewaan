<?php

namespace App\Http\Requests;

use App\Models\RentalContract;
use App\Support\HqMatiSetemAcknowledgements;
use Illuminate\Foundation\Http\FormRequest;

class CompleteHqMatiSetemRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        /** @var RentalContract|null $contract */
        $contract = $this->route('contract');

        return $user?->isAdminHq() === true
            && $contract?->isAwaitingHqMatiSetemConfirmation() === true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            HqMatiSetemAcknowledgements::SALINAN_DITERIMA => ['accepted'],
            HqMatiSetemAcknowledgements::DIMUAT_NAIK_SISTEM_PREMIS => ['accepted'],
            'dates' => ['sometimes', 'array'],
            'dates.*' => ['nullable', 'date_format:Y-m-d'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            HqMatiSetemAcknowledgements::SALINAN_DITERIMA.'.accepted' => 'Sila sahkan salinan dokumen perjanjian yang telah dimatikan setem diterima dari AADK Negeri sebelum menekan Selesai.',
            HqMatiSetemAcknowledgements::DIMUAT_NAIK_SISTEM_PREMIS.'.accepted' => 'Sila sahkan perjanjian telah dimuat naik ke dalam Sistem PROMIS sebelum menekan Selesai.',
            'dates.*.date_format' => 'Tarikh tidak sah.',
        ];
    }
}
