<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="utf-8">
    <title>Laporan SPEKTRA</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #0f172a; }
        h1 { font-size: 18px; margin: 0 0 4px; }
        h2 { font-size: 13px; margin: 18px 0 8px; }
        .meta { color: #475569; margin-bottom: 16px; }
        .cards { width: 100%; margin-bottom: 12px; }
        .cards td { width: 20%; padding: 8px; border: 1px solid #cbd5e1; background: #f8fafc; }
        .cards .value { font-size: 16px; font-weight: bold; }
        .cards .label { color: #64748b; font-size: 10px; margin-top: 2px; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data th, table.data td { border: 1px solid #cbd5e1; padding: 6px 8px; text-align: left; vertical-align: top; }
        table.data th { background: #e2e8f0; font-size: 10px; text-transform: uppercase; }
        .muted { color: #64748b; }
        table.wide { font-size: 8px; }
        table.wide th, table.wide td { padding: 4px; }
        table.wide th { background: #1e293b; color: #fff; }
        .baki-kritikal { background: #dc2626; color: #fff; font-weight: bold; text-align: center; }
        .baki-amaran { background: #fef3c7; color: #78350f; font-weight: bold; text-align: center; }
        .kategori { background: #fde68a; font-weight: bold; text-align: center; }
        .status-terima { background: #059669; color: #fff; font-weight: bold; text-align: center; }
        .status-lain { background: #e2e8f0; font-weight: bold; text-align: center; }
        .status-belum { background: #fee2e2; color: #b91c1c; font-weight: bold; text-align: center; }
        .baki-negatif { color: #dc2626; font-weight: bold; text-align: center; }
        .section-bar { background: #1e293b; color: #fff; padding: 6px 8px; font-size: 11px; }
        .pre { white-space: pre-line; }
    </style>
</head>
<body>
    @if (is_file(public_path('images/spektra-logo.jpg')))
        <img src="{{ str_replace('\\', '/', public_path('images/spektra-logo.jpg')) }}" alt="SPEKTRA" style="height: 62px; margin: 0 0 12px;">
    @endif
@php
    $summary = $report['summary'];
    $rows = $report['rows'];
@endphp

    <h1>
        @if(($report['jenis'] ?? '') === \App\Services\HqReportService::TYPE_KONTRAK_NEGERI)
            {{ \App\Support\ContractNegeriReport::title($report['negeri'], $report['daerah'] ?? null) }}
        @elseif(($report['jenis'] ?? '') === \App\Services\HqReportService::TYPE_STATUS_SEWAAN)
            {{ \App\Support\RentalStatusReport::title() }}
        @else
            Laporan SPEKTRA
        @endif
    </h1>
    <p class="meta">
        {{ $report['jenisLabel'] }}
        · Dijana pada {{ $report['generatedAt'] }}
        · Skop: {{ $report['scopeLabel'] }}
        @if(($report['jenis'] ?? '') === \App\Services\HqReportService::TYPE_KONTRAK_NEGERI)
            · Laporan secara individu beserta kronologi
        @endif
    </p>

    <table class="cards">
        <tr>
            <td>
                <div class="value">{{ number_format($summary['permohonanBaharu']) }}</div>
                <div class="label">Permohonan Baharu</div>
            </td>
            <td>
                <div class="value">{{ number_format($summary['dalamTindakan']) }}</div>
                <div class="label">Dalam Tindakan</div>
            </td>
            <td>
                <div class="value">{{ number_format($summary['kontrakAktif']) }}</div>
                <div class="label">Kontrak Aktif</div>
            </td>
            <td>
                <div class="value">{{ number_format($summary['kontrakLapanBulan']) }}</div>
                <div class="label">Bawah 8 Bulan</div>
            </td>
            <td>
                <div class="value">{{ number_format($summary['kontrakTigaBulan']) }}</div>
                <div class="label">Bawah 3 Bulan</div>
            </td>
        </tr>
    </table>

    <h2>Status Tindakan</h2>
    <table class="data">
        <thead>
            <tr>
                <th>Status</th>
                <th>Bilangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($report['statusBreakdown'] as $row)
                <tr>
                    <td>{{ $row['status'] }}</td>
                    <td>{{ number_format($row['count']) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="muted">Tiada permohonan dalam tindakan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2>{{ ($report['breakdownMode'] ?? 'negeri') === 'daerah' ? 'Pecahan Mengikut Daerah' : 'Pecahan Mengikut Negeri' }}</h2>
    <table class="data">
        <thead>
            <tr>
                <th>{{ ($report['breakdownMode'] ?? 'negeri') === 'daerah' ? 'Daerah' : 'Negeri' }}</th>
                <th>Permohonan</th>
                <th>Kontrak Aktif</th>
            </tr>
        </thead>
        <tbody>
            @forelse($report['negeriBreakdown'] as $row)
                <tr>
                    <td>{{ $row['label'] }}</td>
                    <td>{{ number_format($row['permohonan']) }}</td>
                    <td>{{ number_format($row['kontrak_aktif']) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="muted">Tiada data untuk dipaparkan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2>
        @if(($report['jenis'] ?? '') === \App\Services\HqReportService::TYPE_KONTRAK_NEGERI)
            {{ \App\Support\ContractNegeriReport::title($report['negeri'], $report['daerah'] ?? null) }}
        @elseif(($report['jenis'] ?? '') === \App\Services\HqReportService::TYPE_STATUS_SEWAAN)
            {{ \App\Support\RentalStatusReport::title() }}
        @else
            {{ $report['jenisLabel'] }}
        @endif
        ({{ number_format($rows->count()) }} rekod)
    </h2>
    @if(($report['jenis'] ?? '') === \App\Services\HqReportService::TYPE_KONTRAK_NEGERI)
        @include('reports.partials.kontrak-negeri-pdf-table', ['rows' => $rows])
    @elseif(($report['jenis'] ?? '') === \App\Services\HqReportService::TYPE_STATUS_SEWAAN)
        @include('reports.partials.status-sewaan-pdf-table', ['groups' => $report['statusGroups'] ?? []])
    @else
    <table class="data">
        <thead>
            <tr>
                <th>No.</th>
                <th>Premis</th>
                <th>Negeri</th>
                <th>Daerah</th>
                <th>Kategori</th>
                <th>Status</th>
                <th>Tarikh Tamat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $index => $contract)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $contract->premise?->nama_ptj ?? '–' }}</td>
                    <td>{{ $contract->premise?->negeri ?? '–' }}</td>
                    <td>{{ $contract->premise?->daerah ?: '–' }}</td>
                    <td>{{ \App\Support\ApplicationCategories::label($contract->kategori_permohonan) }}</td>
                    <td>{{ $contract->workflowLabel() }}</td>
                    <td>
                        @php
                            $endDate = $contract->contractEndDate();
                        @endphp
                        {{ $endDate ? $endDate->format('d/m/Y') : '–' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="muted">Tiada rekod untuk laporan ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @endif
</body>
</html>
