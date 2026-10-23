<?php

namespace App\Http\Requests;

use App\Models\RentalContract;
use App\Support\UploadLimits;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StoreContractDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        /** @var RentalContract|null $contract */
        $contract = $this->route('contract');

        if ($user === null || $contract === null) {
            return false;
        }

        if ($user->can('accessDocuments', $contract)) {
            return true;
        }

        abort(404);
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'document' => [
                'required',
                File::types(['pdf', 'jpg', 'jpeg', 'png'])
                    ->max(UploadLimits::effectiveMaxKilobytes()),
            ],
            'jenis' => ['required', 'in:surat_tawaran,gambar_premis,lain'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        $limit = UploadLimits::humanEffectiveLimit();

        return [
            'document.required' => 'Sila pilih fail untuk dimuat naik.',
            'document.max' => "Saiz fail tidak boleh melebihi {$limit}.",
            'document.mimes' => 'Hanya fail PDF, JPG atau PNG dibenarkan.',
        ];
    }
}
