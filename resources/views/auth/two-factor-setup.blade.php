@extends('layouts.app')

@section('title', 'Sediakan pengesahan dua faktor')
@section('header_title', 'Pengesahan dua faktor')
@section('header_subtitle', 'Wajib sebelum menggunakan sistem')

@section('content')
<div class="mx-auto max-w-2xl space-y-6">
    <div class="glass-card p-6">
        <h1 class="text-xl font-bold text-slate-800">Sediakan pengesahan dua faktor</h1>
        <p class="mt-2 text-sm text-slate-600">Tambah akaun ini dalam aplikasi authenticator menggunakan kunci di bawah, kemudian masukkan kod 6 digit untuk mengesahkan.</p>

        <p class="mt-5 text-xs font-semibold uppercase tracking-wide text-slate-500">Kunci persediaan</p>
        <p class="mt-1 break-all font-mono text-sm text-slate-900">{{ trim(chunk_split($secret, 4, ' ')) }}</p>
        <p class="mt-3 break-all text-xs text-slate-500">{{ $provisioningUri }}</p>

        @if($recoveryCodes !== [])
            <p class="mt-6 text-xs font-semibold uppercase tracking-wide text-slate-500">Kod pemulihan</p>
            <p class="mt-1 text-sm text-slate-600">Simpan kod ini di tempat selamat. Setiap kod hanya boleh digunakan sekali.</p>
            <ul class="mt-3 grid gap-2 sm:grid-cols-2">
                @foreach($recoveryCodes as $recoveryCode)
                    <li class="rounded-lg bg-slate-50 px-3 py-2 font-mono text-sm text-slate-800">{{ $recoveryCode }}</li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ route('two-factor.confirm') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label for="code" class="mb-1 block text-sm font-medium text-slate-700">Kod pengesahan</label>
                <input type="text" name="code" id="code" inputmode="numeric" autocomplete="one-time-code" required class="glass-input w-full rounded-xl px-3 py-2 text-sm tracking-widest">
                @error('code')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="glass-btn-primary rounded-xl px-4 py-2.5 text-sm font-medium">Sahkan</button>
        </form>
    </div>
</div>
@endsection
