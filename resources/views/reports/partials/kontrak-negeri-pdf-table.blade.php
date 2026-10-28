@php
    use App\Support\ContractNegeriReport;
@endphp

<table class="data wide">
    <thead>
        <tr>
            <th>No</th>
            <th>Negeri</th>
            <th>Daerah</th>
            <th>PTJ</th>
            <th>Alamat</th>
            <th>Jenis Bangunan</th>
            <th>Mula</th>
            <th>Tamat</th>
            <th>Baki Bulan</th>
            <th>Keluasan (mp)</th>
            <th>Kadar Sewa</th>
            <th>Pemilik</th>
            <th>Kategori</th>
            <th>Status Permohonan</th>
            <th>Perincian Proses Cawangan Pembangunan</th>
            <th>Rumusan Status Terkini</th>
            <th>Tindakan</th>
        </tr>
    </thead>
    <tbody>
        @forelse($rows as $index => $contract)
            @php
                $kronologi = $contract->laporan_kronologi ?? [];
                $bakiClass = match (true) {
                    ContractNegeriReport::isBakiHariKritikal($contract) => 'baki-kritikal',
                    ContractNegeriReport::isBakiHariAmaran($contract) => 'baki-amaran',
                    default => '',
                };
                $statusClass = ContractNegeriReport::isStatusDiterima($contract) ? 'status-terima' : 'status-lain';
                $bakiBulan = ContractNegeriReport::bakiBulan($contract);
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $contract->premise?->negeri ?? '–' }}</td>
                <td>{{ $contract->premise?->daerah ?: '–' }}</td>
                <td>{{ $contract->premise?->nama_ptj ?? '–' }}</td>
                <td class="pre">{{ $contract->premise?->alamat_penuh ?: '–' }}</td>
                <td>{{ ContractNegeriReport::jenisLangganan($contract) }}</td>
                <td>{{ ContractNegeriReport::mula($contract) }}</td>
                <td>{{ ContractNegeriReport::tamat($contract) }}</td>
                <td class="{{ $bakiClass }}">{{ $bakiBulan === null ? '–' : number_format($bakiBulan) }}</td>
                <td>{{ ContractNegeriReport::keluasan($contract) }}</td>
                <td>{{ ContractNegeriReport::kadarSewa($contract) }}</td>
                <td>{{ $contract->premise?->nama_pemilik ?: '–' }}</td>
                <td class="kategori">{{ ContractNegeriReport::kategoriLabel($contract) }}</td>
                <td class="{{ $statusClass }}">{{ ContractNegeriReport::statusPermohonan($contract) }}</td>
                <td class="pre">{{ $kronologi === [] ? 'Tiada kronologi.' : implode("\n", $kronologi) }}</td>
                <td>{{ ContractNegeriReport::rumusan($contract) }}</td>
                <td>{{ ContractNegeriReport::tindakan($contract) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="17" class="muted">Tiada rekod untuk laporan ini.</td>
            </tr>
        @endforelse
    </tbody>
</table>
