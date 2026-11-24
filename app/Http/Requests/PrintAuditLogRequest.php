<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Validator;

class PrintAuditLogRequest extends FormRequest
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
            'tarikh_mula' => ['required', 'date'],
            'tarikh_akhir' => ['required', 'date', 'after_or_equal:tarikh_mula'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'tarikh_mula.required' => 'Tarikh mula diperlukan.',
            'tarikh_mula.date' => 'Tarikh mula tidak sah.',
            'tarikh_akhir.required' => 'Tarikh akhir diperlukan.',
            'tarikh_akhir.date' => 'Tarikh akhir tidak sah.',
            'tarikh_akhir.after_or_equal' => 'Tarikh akhir mesti sama atau selepas tarikh mula.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $start = Carbon::parse((string) $this->input('tarikh_mula'), 'Asia/Kuala_Lumpur')->startOfDay();
            $end = Carbon::parse((string) $this->input('tarikh_akhir'), 'Asia/Kuala_Lumpur')->startOfDay();

            if ($start->copy()->addDays(366)->lt($end)) {
                $validator->errors()->add('tarikh_akhir', 'Julat tarikh tidak boleh melebihi 366 hari.');
            }
        });
    }

    public function startsAt(): Carbon
    {
        return Carbon::parse((string) $this->validated('tarikh_mula'), 'Asia/Kuala_Lumpur')->startOfDay();
    }

    public function endsAt(): Carbon
    {
        return Carbon::parse((string) $this->validated('tarikh_akhir'), 'Asia/Kuala_Lumpur')->endOfDay();
    }
}
