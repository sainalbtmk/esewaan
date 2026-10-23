@extends('layouts.app')

@section('title', 'Kod pemulihan')
@section('header_title', 'Kod pemulihan')

@section('content')
<div class="mx-auto max-w-2xl">
    <div class="glass-card p-6">
        <h1 class="text-xl font-bold text-slate-800">Simpan kod pemulihan</h1>
        <p class="mt-2 text-sm text-slate-600">Kod ini dipaparkan sekali. Gunakan kod pemulihan jika aplikasi authenticator tidak tersedia.</p>
        <ul class="mt-4 grid gap-2 sm:grid-cols-2">
            @foreach($recoveryCodes as $recoveryCode)
                <li class="rounded-lg bg-slate-50 px-3 py-2 font-mono text-sm text-slate-800">{{ $recoveryCode }}</li>
            @endforeach
        </ul>
        <a href="{{ route('dashboard') }}" class="glass-btn-primary mt-6 inline-flex rounded-xl px-4 py-2.5 text-sm font-medium">Teruskan ke papan pemuka</a>
    </div>
</div>
@endsection
