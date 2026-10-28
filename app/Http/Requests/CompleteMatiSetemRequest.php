<?php

namespace App\Http\Requests;

use App\Models\RentalContract;
use App\Support\NegeriMatiSetemAcknowledgements;
use Illuminate\Foundation\Http\FormRequest;

class CompleteMatiSetemRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        /** @var RentalContract|null $contract */
        $contract = $this->route('contract');

        if ($user?->isAdminNegeri() !== true || ! filled($user->negeri)) {
            return false;
        }

        $contract?->loadMissing('premise');

        return $contract?->isMatiSetem() === true
            && $contract->premise?->negeri === $user->negeri;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            NegeriMatiSetemAcknowledgements::MATI_SETEM_LHDN => ['accepted'],
            NegeriMatiSetemAcknowledgements::EDARAN_PEMILIK => ['accepted'],
            NegeriMatiSetemAcknowledgements::SALINAN_DIHANTAR_CP => ['accepted'],
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
            NegeriMatiSetemAcknowledgements::MATI_SETEM_LHDN.'.accepted' => 'Sila sahkan 3 salinan dokumen telah dimatikan setem di LHDN sebelum menekan Selesai.',
            NegeriMatiSetemAcknowledgements::EDARAN_PEMILIK.'.accepted' => 'Sila sahkan 1 salinan dokumen telah diedar kepada Pemilik Premis sebelum menekan Selesai.',
            NegeriMatiSetemAcknowledgements::SALINAN_DIHANTAR_CP.'.accepted' => 'Sila sahkan salinan telah dihantar ke Cawangan Pembangunan melalui Pos/Kourier sebelum menekan Selesai.',
            'dates.*.date_format' => 'Tarikh tidak sah.',
        ];
    }
}
