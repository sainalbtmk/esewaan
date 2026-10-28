@php
    use App\Support\HqDraftAcknowledgements;

    $savedAcknowledgements = is_array($contract->hq_draft_acknowledgements)
        ? $contract->hq_draft_acknowledgements
        : [];
    $acknowledgementsCompleted = $acknowledgementsCompleted ?? false;
@endphp

<div class="space-y-3">
    @foreach(HqDraftAcknowledgements::labels() as $acknowledgementKey => $acknowledgementLabel)
        @php
            $isChecked = array_key_exists($acknowledgementKey, $savedAcknowledgements)
                ? filter_var($savedAcknowledgements[$acknowledgementKey], FILTER_VALIDATE_BOOLEAN)
                : $acknowledgementsCompleted;
            $acknowledgementDate = HqDraftAcknowledgements::dateFor($savedAcknowledgements, $acknowledgementKey);
        @endphp
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <label class="flex min-w-0 items-start gap-3 text-sm text-slate-700">
                <input
                    type="checkbox"
                    disabled
                    @checked($isChecked)
                    class="mt-0.5 h-4 w-4 shrink-0 cursor-default rounded border-slate-300 text-emerald-600"
                >
                <span @class([
                    'text-slate-800' => $isChecked,
                    'text-slate-500' => ! $isChecked,
                ])>{{ $acknowledgementLabel }}</span>
            </label>
            @if($isChecked)
                <span @class([
                    'ml-7 shrink-0 text-sm sm:ml-0',
                    'font-medium text-slate-800' => filled($acknowledgementDate),
                    'text-slate-500' => ! filled($acknowledgementDate),
                ])>
                    {{ filled($acknowledgementDate) ? \Carbon\Carbon::parse($acknowledgementDate)->format('d/m/Y') : 'Tiada tarikh' }}
                </span>
            @endif
        </div>
    @endforeach
</div>
