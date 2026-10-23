@php
    use App\Support\HqJrpChecklist;
    use Illuminate\Support\Carbon;

    $checklist = is_array($contract->hq_jrp_checklist) ? $contract->hq_jrp_checklist : [];
    $dates = is_array($checklist['dates'] ?? null) ? $checklist['dates'] : [];
    $notes = is_array($checklist['notes'] ?? null) ? $checklist['notes'] : [];
    $definitions = HqJrpChecklist::definitions();
    $compact = $compact ?? false;
@endphp

@if($checklist !== [])
<section @class([
    'glass-subtle rounded-xl border border-slate-200/60',
    'p-4' => ! $compact,
    'p-3' => $compact,
])>
    <div @class(['space-y-2' => ! $compact, 'space-y-1.5' => $compact])>
        @foreach(HqJrpChecklist::allKeys() as $key)
            @php
                $isChecked = filter_var($checklist[$key] ?? false, FILTER_VALIDATE_BOOLEAN);
                $requiresDate = HqJrpChecklist::requiresDate($key);
                $requiresNote = HqJrpChecklist::requiresNote($key);
                $itemDate = $dates[$key] ?? null;
                $itemNote = $notes[$key] ?? null;
            @endphp
            <div class="flex flex-col gap-2 rounded-lg border border-slate-300 bg-white/50 px-3 py-2">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <label class="flex min-w-0 items-start gap-2.5">
                        <input
                            type="checkbox"
                            disabled
                            @checked($isChecked)
                            class="mt-0.5 h-4 w-4 shrink-0 rounded border-slate-300 text-slate-800"
                        >
                        <span @class([
                            'leading-relaxed',
                            'text-xs' => ! $compact,
                            'text-[11px]' => $compact,
                            'text-slate-800' => $isChecked,
                            'text-slate-500' => ! $isChecked,
                        ])>
                            {{ $definitions[$key] }}
                        </span>
                    </label>
                    @if($requiresDate && $isChecked)
                        <span @class([
                            'ml-6 sm:ml-0',
                            'text-xs' => ! $compact,
                            'text-[11px]' => $compact,
                            'font-medium text-slate-800' => filled($itemDate),
                            'text-slate-500' => ! filled($itemDate),
                        ])>
                            {{ filled($itemDate) ? Carbon::parse($itemDate)->format('d/m/Y') : 'Tiada tarikh' }}
                        </span>
                    @endif
                </div>
                @if($requiresNote && $isChecked && filled($itemNote))
                    <p @class([
                        'ml-6 whitespace-pre-line leading-relaxed',
                        'text-xs text-slate-700' => ! $compact,
                        'text-[11px] text-slate-700' => $compact,
                    ])>{{ $itemNote }}</p>
                @endif
            </div>
        @endforeach
    </div>
</section>
@endif
