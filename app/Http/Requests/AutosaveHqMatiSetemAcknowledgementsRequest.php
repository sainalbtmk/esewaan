<?php

namespace App\Http\Requests;

use App\Models\RentalContract;
use App\Support\HqMatiSetemAcknowledgements;
use Illuminate\Foundation\Http\FormRequest;

class AutosaveHqMatiSetemAcknowledgementsRequest extends FormRequest
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
        $rules = [
            'acknowledgements' => ['required', 'array'],
        ];

        foreach (HqMatiSetemAcknowledgements::keys() as $key) {
            $rules["acknowledgements.{$key}"] = ['sometimes', 'boolean'];
        }

        $rules['acknowledgements.dates'] = ['sometimes', 'array'];
        $rules['acknowledgements.dates.*'] = ['nullable', 'date_format:Y-m-d'];

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'acknowledgements.required' => 'Tiada pengesahan untuk disimpan.',
        ];
    }
}
