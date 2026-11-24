<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="utf-8">
    <title>Log Audit SPEKTRA</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #0f172a; }
        h1 { font-size: 18px; margin: 0 0 4px; }
        .meta { color: #475569; margin-bottom: 16px; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data th, table.data td { border: 1px solid #cbd5e1; padding: 6px 8px; text-align: left; vertical-align: top; }
        table.data th { background: #e2e8f0; font-size: 10px; text-transform: uppercase; }
        .muted { color: #64748b; }
        .bil { width: 36px; text-align: center; }
    </style>
</head>
<body>
    @include('partials.pdf-sulit')
    @if (is_file(public_path('images/spektra-logo.jpg')))
        <img src="{{ str_replace('\\', '/', public_path('images/spektra-logo.jpg')) }}" alt="SPEKTRA" style="height: 62px; margin: 0 0 12px;">
    @endif

    <h1>Log Audit</h1>
    <p class="meta">
        Aktiviti yang bermula dari {{ $tarikhMula->format('d/m/Y') }} hingga {{ $tarikhAkhir->format('d/m/Y') }}
        · Waktu Malaysia
        · Dijana oleh {{ $generatedBy }} pada {{ $generatedAt->timezone('Asia/Kuala_Lumpur')->format('d/m/Y H:i:s') }}
        · {{ $logs->count() }} rekod
    </p>

    <table class="data">
        <thead>
            <tr>
                <th class="bil">Bil</th>
                <th>Nama</th>
                <th>Cap Masa</th>
                <th>Aktiviti</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td class="bil">{{ $loop->iteration }}</td>
                    <td>{{ $log->displayName() }}</td>
                    <td>{{ $log->startedAt()?->format('d/m/Y H:i:s') ?? '–' }}</td>
                    <td>
                        <strong>{{ $log->activityLabel() }}</strong><br>
                        {{ $log->description }}
                        @if($log->affectsAnotherUser())
                            <br><span class="muted">Terhadap: {{ $log->subject_name ?: ($log->user?->name ?? 'Pengguna tidak diketahui') }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Tiada rekod audit bagi julat tarikh ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
