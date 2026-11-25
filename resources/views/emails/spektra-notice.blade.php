<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #0f172a; }
        table { border-collapse: collapse; width: 100%; max-width: 640px; margin: 16px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; vertical-align: top; }
        th { background: #f4f4f4; width: 38%; }
        .note { color: #64748b; font-size: 13px; }
    </style>
</head>
<body>
    <p>Assalamualaikum dan Salam Sejahtera,</p>
    <p>Tuan/Puan,</p>
    <p>Merujuk kepada Permohonan Sewaan yang telah dikemukakan oleh {{ $contract->displayPemohonName() }}, dengan butiran seperti berikut:</p>

    <table>
        <tr>
            <th>Nama Premis</th>
            <td>{{ $contract->premise?->nama_ptj ?? '–' }}</td>
        </tr>
        <tr>
            <th>Negeri</th>
            <td>{{ $contract->premise?->negeri ?? '–' }}</td>
        </tr>
        <tr>
            <th>Kategori Permohonan</th>
            <td>{{ \App\Support\ApplicationCategories::label($contract->kategori_permohonan) }}</td>
        </tr>
        @if ($includeCatatan)
            <tr>
                <th>Catatan</th>
                <td>{{ filled($contract->remark) ? $contract->remark : '–' }}</td>
            </tr>
        @endif
        @if (filled($tindakanLanjut))
            <tr>
                <th>Tindakan Lanjut</th>
                <td>{{ $tindakanLanjut }}</td>
            </tr>
        @endif
    </table>

    @foreach ($paragraphs as $paragraph)
        <p>{{ $paragraph }}</p>
    @endforeach

    <p>Sekian, terima kasih.</p>
    <p>Sistem SPEKTRA: <a href="{{ config('app.spektra_url') }}">{{ config('app.spektra_url') }}</a></p>
    <p class="note">Nota: Emel ini dijana oleh sistem dan tidak perlu dibalas.</p>
</body>
</html>
