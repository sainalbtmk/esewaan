@php
    $proceedInitialStep = (int) ($initialStep ?? $step ?? 1);
    $proceedActiveSteps = is_array($activeSteps ?? null) ? array_values($activeSteps) : [];
    $proceedLastStep = $proceedActiveSteps === [] ? null : (int) $proceedActiveSteps[array_key_last($proceedActiveSteps)];
    $hideNextStepButton = $proceedLastStep !== null && $proceedInitialStep === $proceedLastStep;
@endphp

<button type="button" id="form-simpan-button" class="glass-btn-secondary rounded-xl px-5 py-2 text-sm font-medium disabled:opacity-60">
    Simpan
</button>
<button
    type="button"
    id="form-save-button"
    @class([
        'glass-btn-secondary rounded-xl px-5 py-2 text-sm font-medium disabled:opacity-60',
        'hidden' => $hideNextStepButton,
    ])
>
    Seterusnya
</button>
