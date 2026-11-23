<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #0f172a; background: #ffffff; }
        h2 { font-size: 16px; line-height: 1.45; color: #991b1b; }
        table { border-collapse: collapse; width: 100%; margin: 16px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; vertical-align: top; }
        th { background: #f4f4f4; }
        .note { color: #64748b; font-size: 13px; }
        .address { white-space: pre-wrap; }
    </style>
</head>
<body style="background:#ffffff;color:#0f172a;">
    <p>Assalamualaikum dan Salam Sejahtera,</p>
    <h2>{{ $heading }}</h2>
    <p>{{ $intro }}</p>
    <table>
        <thead>
            <tr>
                <th>Negeri</th>
                <th>PTJ</th>
                <th>Alamat</th>
                <th>Tarikh Mula Kontrak</th>
                <th>Tarikh Tamat Kontrak</th>
                <th>Baki Bulan/Hari</th>
                <th>Kategori</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($contracts as $contract)
                <tr>
                    <td>{{ $contract->premise?->negeri ?? '–' }}</td>
                    <td>{{ $contract->premise?->nama_ptj ?? '–' }}</td>
                    <td class="address">{{ $contract->premise?->alamat_penuh ?? '–' }}</td>
                    <td>{{ $contract->contractStartDate()?->format('d/m/Y') ?? '–' }}</td>
                    <td>{{ $contract->contractEndDate()?->format('d/m/Y') ?? '–' }}</td>
                    <td>{{ $contract->contractRemainderLabel() }}</td>
                    <td>{{ \App\Support\RentalStatusReport::kategoriLabel($contract) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p>Sila ambil tindakan Pindah atau Lanjutan. Notifikasi ini dihantar setiap hari pada jam 8:00 pagi sehingga tindakan tersebut dihantar ke Ibu Pejabat.</p>
    <p class="note">Nota: Emel ini dijana oleh sistem dan tidak perlu dibalas.</p>
</body>
</html>
