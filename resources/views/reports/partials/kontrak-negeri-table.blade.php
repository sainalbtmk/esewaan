@php
    use App\Support\ContractNegeriReport;
@endphp

<div class="overflow-x-auto">
    <table class="min-w-[1400px] text-left text-xs">
        <thead class="bg-slate-800 text-[10px] uppercase tracking-wide text-white">
            <tr>
                <th class="px-2 py-2 font-semibold">No</th>
                <th class="px-2 py-2 font-semibold">Negeri</th>
                <th class="px-2 py-2 font-semibold">Daerah</th>
                <th class="px-2 py-2 font-semibold">PTJ</th>
                <th class="min-w-40 px-2 py-2 font-semibold">Alamat</th>
                <th class="px-2 py-2 font-semibold">Jenis Bangunan</th>
                <th class="px-2 py-2 font-semibold">Mula</th>
                <th class="px-2 py-2 font-semibold">Tamat</th>
                <th class="px-2 py-2 text-center font-semibold">Baki Bulan</th>
                <th class="px-2 py-2 text-right font-semibold">Keluasan (mp)</th>
                <th class="px-2 py-2 text-right font-semibold">Kadar Sewa</th>
                <th class="px-2 py-2 font-semibold">Pemilik</th>
                <th class="px-2 py-2 text-center font-semibold">Kategori</th>
                <th class="px-2 py-2 text-center font-semibold">Status Permohonan</th>
                <th class="min-w-72 px-2 py-2 font-semibold">Perincian Proses Cawangan Pembangunan</th>
                <th class="px-2 py-2 font-semibold">Rumusan Status Terkini</th>
                <th class="px-2 py-2 font-semibold">Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $index => $contract)
                @php
                    $kronologi = $contract->laporan_kronologi ?? [];
                    $kritikal = ContractNegeriReport::isBakiHariKritikal($contract);
                    $amaran = ContractNegeriReport::isBakiHariAmaran($contract);
                    $bakiBulan = ContractNegeriReport::bakiBulan($contract);
                    $diterima = ContractNegeriReport::isStatusDiterima($contract);
                @endphp
                <tr class="border-t border-slate-200 align-top odd:bg-white even:bg-slate-50/70">
                    <td class="px-2 py-2 text-slate-500">{{ $index + 1 }}</td>
                    <td class="px-2 py-2 font-medium uppercase text-slate-800">{{ $contract->premise?->negeri ?? '–' }}</td>
                    <td class="px-2 py-2 text-slate-800">{{ $contract->premise?->daerah ?: '–' }}</td>
                    <td class="px-2 py-2 font-medium text-slate-900">{{ $contract->premise?->nama_ptj ?? '–' }}</td>
                    <td class="whitespace-pre-line px-2 py-2 text-slate-700">{{ $contract->premise?->alamat_penuh ?: '–' }}</td>
                    <td class="px-2 py-2 text-slate-700">{{ ContractNegeriReport::jenisLangganan($contract) }}</td>
                    <td class="whitespace-nowrap px-2 py-2 text-slate-700">{{ ContractNegeriReport::mula($contract) }}</td>
                    <td class="whitespace-nowrap px-2 py-2 text-slate-700">{{ ContractNegeriReport::tamat($contract) }}</td>
                    <td @class([
                        'px-2 py-2 text-center',
                        'bg-red-600 font-bold text-white' => $kritikal,
                        'bg-amber-100 font-semibold text-amber-900' => $amaran,
                        'font-medium text-slate-800' => ! $kritikal && ! $amaran,
                    ])>{{ $bakiBulan === null ? '–' : number_format($bakiBulan) }}</td>
                    <td class="px-2 py-2 text-right text-slate-700">{{ ContractNegeriReport::keluasan($contract) }}</td>
                    <td class="whitespace-nowrap px-2 py-2 text-right text-slate-700">{{ ContractNegeriReport::kadarSewa($contract) }}</td>
                    <td class="px-2 py-2 text-slate-700">{{ $contract->premise?->nama_pemilik ?: '–' }}</td>
                    <td class="bg-amber-200 px-2 py-2 text-center font-semibold text-slate-900">{{ ContractNegeriReport::kategoriLabel($contract) }}</td>
                    <td @class([
                        'px-2 py-2 text-center font-semibold uppercase',
                        'bg-emerald-600 text-white' => $diterima,
                        'bg-slate-200 text-slate-700' => ! $diterima,
                    ])>{{ ContractNegeriReport::statusPermohonan($contract) }}</td>
                    <td class="px-2 py-2 text-slate-700">
                        @if($kronologi === [])
                            <span class="text-slate-400">Tiada kronologi.</span>
                        @else
                            <div class="whitespace-pre-line leading-relaxed">{{ implode("\n", $kronologi) }}</div>
                        @endif
                    </td>
                    <td class="px-2 py-2 font-medium text-slate-800">{{ ContractNegeriReport::rumusan($contract) }}</td>
                    <td class="px-2 py-2 font-semibold text-slate-900">{{ ContractNegeriReport::tindakan($contract) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="17" class="px-4 py-8 text-center text-sm text-slate-500">Tiada rekod untuk laporan ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
