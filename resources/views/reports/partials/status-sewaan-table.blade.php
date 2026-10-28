@php
    use App\Support\ContractNegeriReport;
    use App\Support\RentalStatusReport;
@endphp

<div class="space-y-6 p-4">
    @forelse($groups as $group)
        <div class="overflow-hidden rounded-xl border border-slate-200">
            <div class="bg-slate-800 px-3 py-2 text-xs font-semibold uppercase tracking-wide text-white">
                {{ $group['title'] }} ({{ number_format($group['rows']->count()) }})
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-[1100px] text-left text-xs">
                    <thead class="bg-slate-100 text-[10px] uppercase tracking-wide text-slate-600">
                        <tr>
                            <th class="px-2 py-2 font-semibold">No</th>
                            <th class="px-2 py-2 font-semibold">Negeri</th>
                            <th class="px-2 py-2 font-semibold">Daerah</th>
                            <th class="px-2 py-2 font-semibold">PTJ</th>
                            <th class="min-w-40 px-2 py-2 font-semibold">Alamat</th>
                            <th class="px-2 py-2 font-semibold">Mula</th>
                            <th class="px-2 py-2 font-semibold">Tamat</th>
                            <th class="px-2 py-2 text-center font-semibold">Baki Bulan</th>
                            <th class="px-2 py-2 text-right font-semibold">Kadar Sewa</th>
                            <th class="px-2 py-2 text-center font-semibold">Kategori</th>
                            <th class="px-2 py-2 text-center font-semibold">Status</th>
                            <th class="px-2 py-2 font-semibold">Rumusan Status Terkini</th>
                            <th class="px-2 py-2 font-semibold">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($group['rows'] as $index => $contract)
                            @php
                                $bakiBulan = RentalStatusReport::bakiBulan($contract);
                                $kritikal = RentalStatusReport::isBakiBulanKritikal($contract);
                                $diterima = (bool) ($contract->laporan_status_diterima ?? false);
                            @endphp
                            <tr class="border-t border-slate-200 align-top odd:bg-white even:bg-slate-50/70">
                                <td class="px-2 py-2 text-slate-500">{{ $index + 1 }}</td>
                                <td class="px-2 py-2 font-medium uppercase text-slate-800">{{ $contract->premise?->negeri ?? '–' }}</td>
                                <td class="px-2 py-2 text-slate-800">{{ $contract->premise?->daerah ?: '–' }}</td>
                                <td class="px-2 py-2 font-medium text-slate-900">{{ $contract->premise?->nama_ptj ?? '–' }}</td>
                                <td class="whitespace-pre-line px-2 py-2 text-slate-700">{{ $contract->premise?->alamat_penuh ?: '–' }}</td>
                                <td class="whitespace-nowrap px-2 py-2 text-slate-700">{{ ContractNegeriReport::mula($contract) }}</td>
                                <td class="whitespace-nowrap px-2 py-2 text-slate-700">{{ ContractNegeriReport::tamat($contract) }}</td>
                                <td @class([
                                    'px-2 py-2 text-center font-bold',
                                    'text-red-600' => $kritikal,
                                    'text-slate-800' => ! $kritikal,
                                ])>{{ $bakiBulan === null ? '–' : number_format($bakiBulan) }}</td>
                                <td class="whitespace-nowrap px-2 py-2 text-right text-slate-700">{{ ContractNegeriReport::kadarSewa($contract) }}</td>
                                <td class="bg-amber-200 px-2 py-2 text-center font-semibold text-slate-900">{{ RentalStatusReport::kategoriLabel($contract) }}</td>
                                <td @class([
                                    'px-2 py-2 text-center font-bold uppercase',
                                    'text-emerald-700' => $diterima,
                                    'text-red-700' => ! $diterima,
                                ])>{{ $diterima ? 'Terima' : 'Belum Terima' }}</td>
                                <td class="px-2 py-2 text-slate-800">{{ $contract->laporan_status_rumusan ?? '–' }}</td>
                                <td class="px-2 py-2 font-semibold text-slate-900">{{ $contract->laporan_status_tindakan ?? '–' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <p class="px-2 py-6 text-center text-sm text-slate-500">Tiada rekod untuk laporan ini.</p>
    @endforelse
</div>
