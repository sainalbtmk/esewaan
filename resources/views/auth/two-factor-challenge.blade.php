@extends('layouts.app')

@section('title', 'Pengesahan Log Masuk')

@section('content')
<div class="glass-card p-7 sm:p-9">
    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Langkah kedua</p>
    <h1 class="mt-2 text-2xl font-semibold text-slate-900">Kod pengesahan</h1>
    <p class="mt-1.5 text-sm text-slate-500">Masukkan kod 6 digit daripada aplikasi authenticator, atau salah satu kod pemulihan.</p>

    <form method="POST" action="{{ route('two-factor.login.store') }}" class="mt-6 space-y-5" data-no-global-loader>
        @csrf
        <div>
            <label for="code" class="mb-1.5 block text-sm font-medium text-slate-700">Kod</label>
            <input
                type="text"
                name="code"
                id="code"
                inputmode="numeric"
                autocomplete="one-time-code"
                autofocus
                required
                class="glass-input w-full rounded-xl px-3.5 py-2.5 text-sm tracking-widest"
            >
            @error('code')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="glass-btn-primary w-full rounded-xl px-4 py-2.5 text-sm font-medium">
            Sahkan dan masuk
        </button>
    </form>
</div>
@endsection
