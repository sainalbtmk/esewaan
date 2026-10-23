<?php

namespace App\Http\Requests;

use App\Models\RentalContract;
use App\Support\UploadLimits;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Validator;

class RejectPuuReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        /** @var RentalContract|null $contract */
        $contract = $this->route('contract');

        return $user?->isAdminHq() === true
            && $contract?->isSemakanPuu() === true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'document' => [
                'required',
                File::types(['pdf'])
                    ->max(UploadLimits::effectiveDraftMaxKilobytes()),
            ],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            /** @var UploadedFile|null $file */
            $file = $this->file('document');

            if (! $file instanceof UploadedFile || $file->isValid()) {
                return;
            }

            $validator->errors()->forget('document');

            $validator->errors()->add(
                'document',
                UploadLimits::uploadFailureMessage($file->getError(), UploadLimits::DRAFT_MAX_KILOBYTES),
            );
        });
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        $limit = UploadLimits::humanDraftLimit();

        return [
            'document.required' => 'Sila muat naik dokumen pindaan PUU dalam format PDF.',
            'document.max' => "Saiz fail tidak boleh melebihi {$limit}.",
            'document.mimes' => 'Hanya fail PDF dibenarkan.',
            'document.file' => "Fail gagal dimuat naik. Sila pastikan fail adalah PDF dan tidak melebihi {$limit}.",
            'document.uploaded' => "Fail gagal dimuat naik. Sila pastikan fail adalah PDF dan tidak melebihi {$limit}.",
        ];
    }
}
