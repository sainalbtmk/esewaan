<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #0f172a; }
        h2 { font-size: 16px; line-height: 1.45; }
        table { border-collapse: collapse; width: 100%; margin: 16px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; vertical-align: top; }
        th { background: #f4f4f4; }
        .note { color: #64748b; font-size: 13px; }
        .address { white-space: pre-wrap; }
    </style>
</head>
<body>
    <p>Assalamualaikum dan Salam Sejahtera,</p>
    <p>Tuan/Puan,</p>
    <h2>PERINGATAN PENGHANTARAN PERMOHONAN PELANJUTAN SEWAAN RUANG PEJABAT AGENSI ANTIDADAH KEBANGSAAN UNTUK KONTRAK YANG AKAN TAMAT DALAM TEMPOH LAPAN (8) BULAN</h2>
    <p>Dengan segala hormatnya saya diarah merujuk kepada perkara di atas.</p>
    <p>2. Untuk makluman tuan/puan, semakan Cawangan Pembangunan mendapati terdapat premis AADK Negeri/ Daerah yang masih belum mengemukakan permohonan pelanjutan sewaan walaupun kontrak akan tamat dalam tempoh kurang daripada lapan (8) bulan. Berikut merupakan butiran premis yang terlibat:</p>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Negeri</th>
                <th>PTJ</th>
                <th>Alamat</th>
                <th>Mula</th>
                <th>Tamat</th>
                <th>Baki Bulan</th>
                <th>Kategori</th>
                <th>Status Permohonan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($contracts as $contract)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $contract->premise?->negeri ?? '–' }}</td>
                    <td>{{ $contract->premise?->nama_ptj ?? '–' }}</td>
                    <td class="address">{{ $contract->premise?->alamat_penuh ?? '–' }}</td>
                    <td>{{ $contract->contractStartDate()?->format('d/m/Y') ?? '–' }}</td>
                    <td>{{ $contract->contractEndDate()?->format('d/m/Y') ?? '–' }}</td>
                    <td>{{ \App\Support\RentalStatusReport::bakiBulan($contract) ?? '–' }}</td>
                    <td>Pelanjutan</td>
                    <td>Belum Terima</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p>3. Semua permohonan di Semenanjung Malaysia dan Wilayah Persekutuan Labuan perlu dihantar melalui atas talian Sistem ProMIS: <a href="https://promis.bph.gov.my/">https://promis.bph.gov.my/</a> dan dikemaskini melalui Sistem SPEKTRA: <a href="{{ config('app.spektra_url') }}">{{ config('app.spektra_url') }}</a>, manakala untuk permohonan di Sabah dan Sarawak perlu dikemukakan secara fizikal kepada urusetia Jawatankuasa Ruang Pejabat (JRP) di Pejabat Setiausaha Persekutuan Sabah (SUPS) / Pejabat Setiausaha Persekutuan Sarawak (SUPS).</p>
    <p>4. Sehubungan itu, kerjasama pihak tuan/puan dipohon untuk mengemukakan dengan segera dokumen sewaan yang lengkap kepada Cawangan Pembangunan bagi tujuan kelulusan di peringkat Bahagian Pengurusan Hartanah, Jabatan Perdana Menteri (BPH, JPM). Kelewatan mengemukakan dokumen berkenaan boleh menyebabkan implikasi kelewatan proses pembayaran sewaan kelak. Kerjasama dan tindakan daripada pihak tuan/puan adalah sangat diharapkan dan didahului dengan ucapan terima kasih.</p>
    <p>Sekian.</p>
    <p class="note">Nota: Emel ini dijana oleh sistem dan tidak perlu dibalas.</p>
</body>
</html>
