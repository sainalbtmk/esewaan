@php
    use App\Support\ContractNegeriReport;
    use App\Support\RentalStatusReport;
@endphp

@forelse($groups as $group)
    <h2 class="section-bar">{{ mb_strtoupper($group['title'], 'UTF-8') }} ({{ number_format($group['rows']->count()) }})</h2>
    <table class="data wide">
        <thead>
            <tr>
                <th>No</th>
                <th>Negeri</th>
                <th>Daerah</th>
                <th>PTJ</th>
                <th>Alamat</th>
                <th>Mula</th>
                <th>Tamat</th>
                <th>Baki Bulan</th>
                <th>Kadar Sewa</th>
                <th>Kategori</th>
                <th>Status</th>
                <th>Rumusan Status Terkini</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($group['rows'] as $index => $contract)
                @php
                    $bakiBulan = RentalStatusReport::bakiBulan($contract);
                    $diterima = (bool) ($contract->laporan_status_diterima ?? false);
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $contract->premise?->negeri ?? '–' }}</td>
                    <td>{{ $contract->premise?->daerah ?: '–' }}</td>
                    <td>{{ $contract->premise?->nama_ptj ?? '–' }}</td>
                    <td class="pre">{{ $contract->premise?->alamat_penuh ?: '–' }}</td>
                    <td>{{ ContractNegeriReport::mula($contract) }}</td>
                    <td>{{ ContractNegeriReport::tamat($contract) }}</td>
                    <td class="{{ RentalStatusReport::isBakiBulanKritikal($contract) ? 'baki-negatif' : '' }}">{{ $bakiBulan === null ? '–' : number_format($bakiBulan) }}</td>
                    <td>{{ ContractNegeriReport::kadarSewa($contract) }}</td>
                    <td class="kategori">{{ RentalStatusReport::kategoriLabel($contract) }}</td>
                    <td class="{{ $diterima ? 'status-terima' : 'status-belum' }}">{{ $diterima ? 'Terima' : 'Belum Terima' }}</td>
                    <td>{{ $contract->laporan_status_rumusan ?? '–' }}</td>
                    <td>{{ $contract->laporan_status_tindakan ?? '–' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@empty
    <p class="muted">Tiada rekod untuk laporan ini.</p>
@endforelse
