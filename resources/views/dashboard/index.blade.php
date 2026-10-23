@extends('layouts.app')

@section('title', 'Papan Pemuka')
@section('header_title', 'Papan Pemuka')
@section('header_subtitle', filled($scopedNegeri ?? null)
    ? 'Ringkasan permohonan dan kontrak sewaan untuk '.$scopedNegeri
    : 'Ringkasan permohonan dan kontrak sewaan semasa')

@section('content')
@php
    $portfolioSlices = $portfolio['slices'] ?? [];
    $portfolioTotal = (int) ($portfolio['total'] ?? 0);
    $leftSlices = [];
    $rightSlices = [];
    foreach ($portfolioSlices as $index => $slice) {
        if ($index % 2 === 0) {
            $leftSlices[] = $slice;
        } else {
            $rightSlices[] = $slice;
        }
    }
    $donutRadius = 68;
    $donutCircumference = 2 * M_PI * $donutRadius;
    $donutOffset = 0;
@endphp

<div class="space-y-6">
    {{-- KPI cards --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-5">
        <a href="{{ route('status-permohonan.index') }}" class="glass-card glass-kpi-card block p-4 lg:p-5">
            <div class="mb-3 flex items-start justify-between">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-50/80 ring-1 ring-sky-100/80">
                    <svg class="h-5 w-5 text-sky-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                </div>
            </div>
            <p class="text-2xl font-bold leading-tight text-slate-900 lg:text-3xl">{{ number_format($permohonanBaharu) }}</p>
            <p class="mt-1 text-xs font-medium text-slate-500">Permohonan Baharu</p>
            <p class="mt-1.5 text-xs text-sky-600">Menunggu semakan Ibu Pejabat</p>
        </a>

        <a href="#dalam-tindakan" class="glass-card glass-kpi-card block p-4 lg:p-5">
            <div class="mb-3 flex items-start justify-between">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50/80 ring-1 ring-indigo-100/80">
                    <svg class="h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
            </div>
            <p class="text-2xl font-bold leading-tight text-slate-900 lg:text-3xl">{{ number_format($progressPermohonan) }}</p>
            <p class="mt-1 text-xs font-medium text-slate-500">Dalam Tindakan</p>
            <p class="mt-1.5 text-xs text-indigo-600">Dalam penyediaan draf perjanjian</p>
        </a>

        <a href="{{ route('kontrak-sewaan.index') }}" class="glass-card glass-kpi-card block p-4 lg:p-5">
            <div class="mb-3 flex items-start justify-between">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50/80 ring-1 ring-emerald-100/80">
                    <svg class="h-5 w-5 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <p class="text-2xl font-bold leading-tight text-slate-900 lg:text-3xl">{{ number_format($kontrakAktif) }}</p>
            <p class="mt-1 text-xs font-medium text-slate-500">Kontrak Aktif</p>
            <p class="mt-1.5 text-xs text-emerald-600">Dalam senarai kontrak sewaan</p>
        </a>

        <a href="#peringatan-lapan" class="glass-card glass-kpi-card block p-4 lg:p-5">
            <div class="mb-3 flex items-start justify-between">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50/80 ring-1 ring-amber-100/80">
                    <svg class="h-5 w-5 text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <p class="text-2xl font-bold leading-tight text-slate-900 lg:text-3xl">{{ number_format($kontrakLapanBulan) }}</p>
            <p class="mt-1 text-xs font-medium text-slate-500">Kontrak Bawah 8 Bulan</p>
            <p class="mt-1.5 text-xs text-amber-600">3 hingga bawah 8 bulan</p>
        </a>

        <a href="#peringatan-tiga" class="glass-card glass-kpi-card block border border-red-200/80 bg-red-50/70 p-4 lg:p-5">
            <div class="mb-3 flex items-start justify-between">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-100 ring-1 ring-red-200">
                    <svg class="h-5 w-5 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" /></svg>
                </div>
            </div>
            <p class="text-2xl font-bold leading-tight text-red-700 lg:text-3xl">{{ number_format($kontrakTigaBulan) }}</p>
            <p class="mt-1 text-xs font-medium text-red-700">Kontrak Bawah 3 Bulan</p>
            <p class="mt-1.5 text-xs text-red-600">Perlu tindakan segera</p>
        </a>
    </div>

    <section class="glass-card overflow-hidden">
        <div class="border-b border-slate-200/80 px-5 py-4">
            <h3 class="text-center text-sm font-semibold uppercase tracking-wide text-slate-900">
                Status Kontrak Sewaan Premis AADK {{ $portfolioYear }}
            </h3>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2">
            <div class="border-b border-slate-200/80 p-5 lg:border-r lg:border-b-0">
                <h4 class="text-base font-semibold text-slate-900">Status Keseluruhan Portfolio</h4>
                <div class="mt-5 flex flex-col items-center gap-6 sm:flex-row sm:justify-center">
                    <ul class="order-2 w-full space-y-3 sm:order-1 sm:max-w-40">
                        @foreach($leftSlices as $slice)
                            <li class="text-xs leading-snug text-slate-600">
                                <span class="mr-1 inline-block h-2.5 w-2.5 rounded-full" style="background: {{ $slice['color'] }}"></span>
                                <span class="font-semibold uppercase text-slate-800">{{ $slice['label'] }}:</span>
                                {{ number_format($slice['count']) }} ({{ number_format($slice['percent'], 1) }}%)
                            </li>
                        @endforeach
                    </ul>
                    <div class="relative order-1 shrink-0 sm:order-2">
                        <svg viewBox="0 0 200 200" class="h-52 w-52" role="img" aria-label="Carta pai status keseluruhan portfolio">
                            @if($portfolioTotal === 0)
                                <circle cx="100" cy="100" r="{{ $donutRadius }}" fill="none" stroke="#e2e8f0" stroke-width="28"></circle>
                            @else
                                @foreach($portfolioSlices as $slice)
                                    @php
                                        $segment = ($slice['count'] / $portfolioTotal) * $donutCircumference;
                                    @endphp
                                    <circle
                                        cx="100"
                                        cy="100"
                                        r="{{ $donutRadius }}"
                                        fill="none"
                                        stroke="{{ $slice['color'] }}"
                                        stroke-width="28"
                                        stroke-dasharray="{{ $segment }} {{ $donutCircumference }}"
                                        stroke-dashoffset="{{ -$donutOffset }}"
                                        transform="rotate(-90 100 100)"
                                    ></circle>
                                    @php $donutOffset += $segment; @endphp
                                @endforeach
                            @endif
                        </svg>
                        <div class="pointer-events-none absolute inset-0 flex items-center justify-center">
                            <div class="flex h-28 w-28 flex-col items-center justify-center rounded-full bg-white px-2 text-center">
                                <p class="text-[9px] font-semibold uppercase leading-tight tracking-wide text-slate-500">Jumlah<br>Keseluruhan</p>
                                <p class="my-0.5 text-2xl font-bold leading-none text-slate-900">{{ number_format($portfolioTotal) }}</p>
                                <p class="text-[9px] font-medium uppercase leading-tight text-slate-500">Kontrak<br>Sewaan Premis</p>
                            </div>
                        </div>
                    </div>
                    <ul class="order-3 w-full space-y-3 sm:max-w-44">
                        @foreach($rightSlices as $slice)
                            <li class="text-xs leading-snug text-slate-600">
                                <span class="mr-1 inline-block h-2.5 w-2.5 rounded-full" style="background: {{ $slice['color'] }}"></span>
                                <span class="font-semibold uppercase text-slate-800">{{ $slice['label'] }}:</span>
                                {{ number_format($slice['count']) }} ({{ number_format($slice['percent'], 1) }}%)
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="p-5">
                <h4 class="text-base font-semibold leading-snug text-slate-900">
                    Tindakan Segera:
                    <span class="text-red-600">{{ number_format($portfolio['urgentPercent'] ?? 0, 1) }}%</span>
                    ({{ number_format($portfolio['urgentCount'] ?? 0) }} Kontrak)
                </h4>
                <p class="mt-1 text-sm font-medium text-slate-800">
                    Berbaki <span class="font-semibold text-red-600">Kurang 8 Bulan</span> &amp; Belum Mengemukakan Permohonan
                </p>
                <div class="mt-4 max-h-96 overflow-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="border-b border-slate-200 text-xs uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-2 py-2 font-semibold">Bil.</th>
                                <th class="px-2 py-2 font-semibold">Negeri</th>
                                <th class="px-2 py-2 font-semibold">PTJ</th>
                                <th class="px-2 py-2 font-semibold">Baki Bulan</th>
                                <th class="px-2 py-2 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($portfolio['urgentRows'] ?? [] as $index => $contract)
                                @php
                                    $bakiBulan = \App\Support\RentalStatusReport::bakiBulan($contract);
                                @endphp
                                <tr class="border-b border-slate-100 align-top">
                                    <td class="px-2 py-2 text-slate-500">{{ $index + 1 }}</td>
                                    <td class="px-2 py-2 text-slate-800">{{ $contract->premise?->negeri ?? '–' }}</td>
                                    <td class="px-2 py-2 font-medium text-slate-900">
                                        <a href="{{ route('kontrak-sewaan.show', $contract) }}" class="hover:underline">{{ $contract->premise?->nama_ptj ?? '–' }}</a>
                                    </td>
                                    <td class="px-2 py-2">
                                        <span @class([
                                            'inline-flex rounded px-2 py-1 text-xs font-bold',
                                            'bg-red-600 text-white' => $bakiBulan !== null && $bakiBulan < 0,
                                            'bg-amber-400 text-slate-900' => $bakiBulan === null || $bakiBulan >= 0,
                                        ])>{{ $bakiBulan === null ? '–' : $bakiBulan.' Bulan' }}</span>
                                    </td>
                                    <td class="px-2 py-2 text-slate-700">Belum Terima Permohonan</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-2 py-8 text-center text-sm text-slate-500">Tiada kontrak berbaki kurang 8 bulan yang belum mengemukakan permohonan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    {{-- Dalam Tindakan: pecahan mengikut status --}}
    <div id="dalam-tindakan" class="glass-card overflow-hidden scroll-mt-24">
        <div class="glass-divider-soft flex items-center justify-between border-b px-5 py-4">
            <div class="flex items-center gap-2">
                <svg class="h-4 w-4 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                <h3 class="font-semibold text-gray-900">Status Tindakan</h3>
                @if($progressPermohonan > 0)
                <span class="rounded-md bg-indigo-100 px-1.5 py-0.5 text-xs font-bold text-indigo-700">{{ $progressPermohonan }}</span>
                @endif
            </div>
            <p class="text-xs text-gray-400">Bilangan kontrak mengikut status permohonan</p>
        </div>
        <div class="divide-y divide-slate-200/50">
            @foreach($dalamTindakanByStatus as $row)
                <div class="flex items-center gap-3 px-5 py-3.5">
                    <div class="h-2 w-2 flex-shrink-0 rounded-full {{ $row['count'] > 0 ? 'bg-indigo-500' : 'bg-slate-300' }}"></div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-gray-900">{{ $row['status'] }}</p>
                    </div>
                    <div class="flex-shrink-0 text-right">
                        <p class="text-sm font-semibold {{ $row['count'] > 0 ? 'text-indigo-700' : 'text-slate-400' }}">{{ number_format($row['count']) }}</p>
                        <p class="text-xs text-gray-400">kontrak</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Alert list (3 bulan) --}}
    <div id="peringatan-tiga" class="glass-card overflow-hidden scroll-mt-24 border border-red-200/70">
        <div class="glass-divider-soft flex items-center justify-between border-b border-red-100 bg-red-50/60 px-5 py-4">
            <div class="flex items-center gap-2">
                <svg class="h-4 w-4 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" /></svg>
                <h3 class="font-semibold text-red-800">Kontrak Bawah 3 Bulan</h3>
                @if($alertListTigaBulan->isNotEmpty())
                <span class="rounded-md bg-red-100 px-1.5 py-0.5 text-xs font-bold text-red-700">{{ $alertListTigaBulan->count() }}</span>
                @endif
            </div>
            <p class="text-xs text-red-600/80">Kontrak aktif dengan baki bawah 3 bulan</p>
        </div>
        <div class="divide-y divide-red-100/80">
            @forelse($alertListTigaBulan as $c)
                <a href="{{ route('kontrak-sewaan.show', $c) }}" class="glass-row-hover flex items-center gap-3 px-5 py-3.5">
                    <div class="h-2 w-2 flex-shrink-0 rounded-full bg-red-500"></div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-gray-900">{{ $c->premise?->nama_ptj ?? '–' }}</p>
                        <p class="text-xs text-gray-400">{{ $c->premise?->negeri ?? '–' }}</p>
                    </div>
                    <div class="flex-shrink-0 text-right">
                        <p class="text-xs font-semibold text-red-700">{{ $c->daysUntilContractEndLabel() }}</p>
                    </div>
                </a>
            @empty
                <div class="py-10 text-center text-gray-400">
                    <p class="text-sm font-medium text-emerald-600">Tiada kontrak bawah 3 bulan</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Alert list (8 bulan, excluding 3 months) --}}
    <div id="peringatan-lapan" class="glass-card overflow-hidden scroll-mt-24">
        <div class="glass-divider-soft flex items-center justify-between border-b px-5 py-4">
            <div class="flex items-center gap-2">
                <svg class="h-4 w-4 text-amber-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <h3 class="font-semibold text-gray-900">Kontrak Bawah 8 Bulan</h3>
                @if($alertListLapanBulan->isNotEmpty())
                <span class="rounded-md bg-amber-100 px-1.5 py-0.5 text-xs font-bold text-amber-700">{{ $alertListLapanBulan->count() }}</span>
                @endif
            </div>
            <p class="text-xs text-gray-400">Baki 3 hingga bawah 8 bulan (tidak termasuk bawah 3 bulan)</p>
        </div>
        <div class="divide-y divide-slate-200/50">
            @forelse($alertListLapanBulan as $c)
                <a href="{{ route('kontrak-sewaan.show', $c) }}" class="glass-row-hover flex items-center gap-3 px-5 py-3.5">
                    <div class="h-2 w-2 flex-shrink-0 rounded-full bg-amber-500"></div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-gray-900">{{ $c->premise?->nama_ptj ?? '–' }}</p>
                        <p class="text-xs text-gray-400">{{ $c->premise?->negeri ?? '–' }}</p>
                    </div>
                    <div class="flex-shrink-0 text-right">
                        <p class="text-xs font-semibold text-amber-700">{{ $c->daysUntilContractEndLabel() }}</p>
                    </div>
                </a>
            @empty
                <div class="py-10 text-center text-gray-400">
                    <p class="text-sm font-medium text-emerald-600">Tiada kontrak dalam julat bawah 8 bulan</p>
                    <p class="mt-1 text-xs">Kontrak kritikal bawah 3 bulan dipaparkan dalam senarai merah</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
