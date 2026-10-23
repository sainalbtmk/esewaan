<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; color: #0f172a; }
        table { border-collapse: collapse; width: 100%; margin: 16px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f4f4f4; }
        .muted { color: #64748b; font-size: 13px; }
        .warning { color: #991b1b; }
    </style>
</head>
<body>
    <h2 @if($tier === \App\Support\ContractExpiryReminderTier::ThreeMonth) style="color: #991b1b;" @endif>{{ $tier->heading() }}</h2>
    <p>{{ $tier->intro() }}</p>
    <table>
        <thead>
            <tr>
                <th>Nama Premis</th>
                <th>Negeri</th>
                <th>Tarikh Tamat</th>
                <th>Baki Tempoh</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contracts as $contract)
                <tr>
                    <td>{{ $contract->premise?->nama_ptj ?? '–' }}</td>
                    <td>{{ $contract->premise?->negeri ?? '–' }}</td>
                    <td>{{ $contract->contractEndDate()?->format('d/m/Y') ?? '–' }}</td>
                    <td>{{ $contract->daysUntilContractEndLabel() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p>
        <a href="{{ route('kontrak-sewaan.index') }}">Buka Senarai Kontrak Sewaan</a>
    </p>
    <p class="muted">{{ $tier->footnote() }}</p>
    <p class="muted">Ini adalah notifikasi automatik daripada Sistem E-Sewaan AADK.</p>
</body>
</html>
