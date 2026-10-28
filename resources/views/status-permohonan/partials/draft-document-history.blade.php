@php
    $draftDocuments = $contract->documents
        ->where('jenis', \App\Models\ContractDocument::JENIS_DRAF_PERJANJIAN)
        ->sortByDesc('semakan_round');
    $puuAmendments = $contract->documents
        ->where('jenis', \App\Models\ContractDocument::JENIS_PINDAAN_PUU)
        ->keyBy(fn ($document) => (int) $document->semakan_round);
    $approvedDocuments = $contract->documents
        ->where('jenis', \App\Models\ContractDocument::JENIS_LULUS_PUU)
        ->keyBy(fn ($document) => (int) $document->semakan_round);
@endphp

@if($draftDocuments->isNotEmpty())
    <div>
        <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Sejarah Muat Naik Draf</p>
        <ul class="mt-2 space-y-2">
            @foreach($draftDocuments as $document)
                <li class="flex flex-col gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="shrink-0 font-medium text-indigo-700">{{ $document->semakanLabel() ?? 'Draf Perjanjian' }}</span>
                        <span class="{{ $document->semakanStatusBadgeClass() }} text-[11px]">
                            {{ $document->semakanStatusLabel() }}
                        </span>
                    </div>
                    <div class="flex min-w-0 flex-col gap-1 sm:items-end">
                        <a
                            href="{{ $document->downloadUrl() }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="truncate text-slate-600 underline-offset-2 hover:text-indigo-600 hover:underline"
                        >
                            {{ $document->nama_fail }}
                        </a>
                        @if($amendment = $puuAmendments->get((int) $document->semakan_round))
                            <a
                                href="{{ $amendment->downloadUrl() }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="truncate text-xs text-amber-800 underline-offset-2 hover:underline"
                            >
                                Dokumen pindaan PUU: {{ $amendment->nama_fail }}
                            </a>
                        @endif
                        @if($approved = $approvedDocuments->get((int) $document->semakan_round))
                            <a
                                href="{{ $approved->downloadUrl() }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="truncate text-xs text-emerald-800 underline-offset-2 hover:underline"
                            >
                                Dokumen lulus PUU: {{ $approved->nama_fail }}
                            </a>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
@endif
