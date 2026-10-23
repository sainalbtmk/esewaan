@extends('layouts.app')

@section('title', 'Jana Laporan')
@section('header_title', 'Jana Laporan')
@section('header_subtitle', 'Laporan ringkasan permohonan dan kontrak sewaan untuk Ibu Pejabat')

@section('content')
@php
    $summary = $report['summary'];
    $rows = $report['rows'];
@endphp

<div class="space-y-6">
    <form method="GET" action="{{ route('reports.index') }}" class="glass-card p-4 lg:p-5">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-12 lg:items-end">
            <div class="lg:col-span-4">
                <label for="jenis" class="mb-1 block text-sm font-medium text-slate-700">Jenis Laporan</label>
                <select name="jenis" id="jenis" class="glass-input w-full rounded-xl px-3 py-2.5 text-sm">
                    @foreach($jenisList as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['jenis'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="lg:col-span-4">
                <label for="negeri" class="mb-1 block text-sm font-medium text-slate-700">Negeri</label>
                <select name="negeri" id="negeri" class="glass-input w-full rounded-xl px-3 py-2.5 text-sm">
                    <option value="">Semua negeri</option>
                    @foreach($negeriList as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['negeri'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-slate-500">Laporan mengikut negeri dijana berdasarkan negeri yang dipilih.</p>
            </div>
            <div class="flex flex-col gap-2 sm:flex-row lg:col-span-4">
                <button type="submit" class="glass-btn-primary inline-flex flex-1 items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-medium">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Papar Laporan
                </button>
                <a
                    href="{{ route('reports.pdf', array_filter(['jenis' => $filters['jenis'] ?? null, 'negeri' => $filters['negeri'] ?? null])) }}"
                    class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:ring-offset-2"
                >
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Eksport PDF
                </a>
            </div>
        </div>
    </form>

    <div class="grid grid-cols-2 gap-4 lg:grid-cols-5">
        <div class="glass-card p-4">
            <p class="text-2xl font-bold text-slate-900">{{ number_format($summary['permohonanBaharu']) }}</p>
            <p class="mt-1 text-xs font-medium text-slate-500">Permohonan Baharu</p>
        </div>
        <div class="glass-card p-4">
            <p class="text-2xl font-bold text-slate-900">{{ number_format($summary['dalamTindakan']) }}</p>
            <p class="mt-1 text-xs font-medium text-slate-500">Dalam Tindakan</p>
        </div>
        <div class="glass-card p-4">
            <p class="text-2xl font-bold text-slate-900">{{ number_format($summary['kontrakAktif']) }}</p>
            <p class="mt-1 text-xs font-medium text-slate-500">Kontrak Aktif</p>
        </div>
        <div class="glass-card p-4">
            <p class="text-2xl font-bold text-amber-700">{{ number_format($summary['kontrakLapanBulan']) }}</p>
            <p class="mt-1 text-xs font-medium text-slate-500">Bawah 8 Bulan</p>
        </div>
        <div class="glass-card border border-red-200/80 bg-red-50/70 p-4">
            <p class="text-2xl font-bold text-red-700">{{ number_format($summary['kontrakTigaBulan']) }}</p>
            <p class="mt-1 text-xs font-medium text-red-700">Bawah 3 Bulan</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
        <section class="glass-card overflow-hidden p-5">
            <h2 class="text-sm font-semibold text-slate-900">Status Tindakan</h2>
            <p class="mt-0.5 text-xs text-slate-500">Bilangan permohonan mengikut status semasa</p>
            <div class="mt-4 space-y-2">
                @forelse($report['statusBreakdown'] as $row)
                    <div class="flex items-center justify-between gap-3 rounded-xl bg-slate-50/80 px-3 py-2 text-sm">
                        <span class="text-slate-700">{{ $row['status'] }}</span>
                        <span class="font-semibold text-slate-900">{{ number_format($row['count']) }}</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Tiada permohonan dalam tindakan.</p>
                @endforelse
            </div>
        </section>

        <section class="glass-card overflow-hidden p-5">
            <h2 class="text-sm font-semibold text-slate-900">Pecahan Mengikut Negeri</h2>
            <p class="mt-0.5 text-xs text-slate-500">Permohonan berbanding kontrak aktif</p>
            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-xs uppercase tracking-wide text-slate-500">
                            <th class="px-2 py-2 font-semibold">Negeri</th>
                            <th class="px-2 py-2 font-semibold">Permohonan</th>
                            <th class="px-2 py-2 font-semibold">Kontrak Aktif</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($report['negeriBreakdown'] as $row)
                            <tr class="border-b border-slate-100">
                                <td class="px-2 py-2 text-slate-800">{{ $row['negeri'] }}</td>
                                <td class="px-2 py-2 text-slate-700">{{ number_format($row['permohonan']) }}</td>
                                <td class="px-2 py-2 text-slate-700">{{ number_format($row['kontrak_aktif']) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-2 py-4 text-slate-500">Tiada data untuk dipaparkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <section class="glass-card overflow-hidden">
        <div class="border-b border-slate-200/80 px-5 py-4">
            <h2 class="text-sm font-semibold text-slate-900">
                @if(($report['jenis'] ?? '') === \App\Services\HqReportService::TYPE_KONTRAK_NEGERI)
                    {{ \App\Support\ContractNegeriReport::title($report['negeri']) }}
                @elseif(($report['jenis'] ?? '') === \App\Services\HqReportService::TYPE_STATUS_SEWAAN)
                    {{ \App\Support\RentalStatusReport::title() }}
                @else
                    {{ $report['jenisLabel'] }}
                @endif
            </h2>
            @if(($report['jenis'] ?? '') === \App\Services\HqReportService::TYPE_STATUS_SEWAAN)
                <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-slate-500">Lampiran 1</p>
            @endif
            <p class="mt-0.5 text-xs text-slate-500">
                Dijana pada {{ $report['generatedAt'] }}
                @if(filled($report['negeri']))
                    · Skop: {{ $report['negeri'] }}
                @else
                    · Skop: Semua negeri
                @endif
                · {{ number_format($rows->count()) }} rekod
                @if(($report['jenis'] ?? '') === \App\Services\HqReportService::TYPE_KONTRAK_NEGERI)
                    · Laporan secara individu beserta kronologi
                @endif
            </p>
        </div>
        @if(($report['jenis'] ?? '') === \App\Services\HqReportService::TYPE_KONTRAK_NEGERI)
            @include('reports.partials.kontrak-negeri-table', ['rows' => $rows])
        @elseif(($report['jenis'] ?? '') === \App\Services\HqReportService::TYPE_STATUS_SEWAAN)
            @include('reports.partials.status-sewaan-table', ['groups' => $report['statusGroups'] ?? []])
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-slate-50/80 text-xs uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-4 py-3 font-semibold">No.</th>
                            <th class="px-4 py-3 font-semibold">Premis</th>
                            <th class="px-4 py-3 font-semibold">Negeri</th>
                            <th class="px-4 py-3 font-semibold">Kategori</th>
                            <th class="px-4 py-3 font-semibold">Status</th>
                            <th class="px-4 py-3 font-semibold">Tarikh Tamat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $index => $contract)
                            <tr class="border-t border-slate-100">
                                <td class="px-4 py-3 text-slate-500">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-medium text-slate-900">{{ $contract->premise?->nama_ptj ?? '–' }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $contract->premise?->negeri ?? '–' }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ \App\Support\ApplicationCategories::label($contract->kategori_permohonan) }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $contract->workflowLabel() }}</td>
                                <td class="px-4 py-3 text-slate-700">
                                    @php
                                        $endDate = $contract->contractEndDate();
                                    @endphp
                                    {{ $endDate ? $endDate->format('d/m/Y') : '–' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-slate-500">Tiada rekod untuk laporan ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</div>
@endsection
