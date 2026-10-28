<?php

namespace App\Http\Requests;

use App\Models\RentalContract;
use App\Support\HqDraftAcknowledgements;
use Illuminate\Foundation\Http\FormRequest;

class SignAgreementRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        /** @var RentalContract|null $contract */
        $contract = $this->route('contract');

        return $user?->isAdminHq() === true
            && $contract?->isDrafDikembalikanHq() === true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'dates' => ['sometimes', 'array'],
            'dates.*' => ['nullable', 'date_format:Y-m-d'],
        ];

        foreach (HqDraftAcknowledgements::keys() as $key) {
            $rules[$key] = ['accepted'];
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        $message = 'Sila tanda kesemua kotak pengesahan sebelum menghantar kepada Negeri.';
        $messages = [
            'dates.*.date_format' => 'Tarikh tidak sah.',
        ];

        foreach (HqDraftAcknowledgements::keys() as $key) {
            $messages[$key.'.accepted'] = $message;
        }

        return $messages;
    }
}
