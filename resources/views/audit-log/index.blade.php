@extends('layouts.app')

@section('title', 'Log Audit')
@section('header_title', 'Log Audit')
@section('header_subtitle', 'Rekod aktiviti semua pengguna sistem')

@section('content')
<div class="space-y-4">
    <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
        Log audit ini untuk paparan sahaja. Rekod tidak boleh dikemaskini atau dipadam.
    </div>

    <form method="GET" action="{{ route('users.audit-log') }}" class="flex flex-col gap-3 sm:flex-row sm:items-center">
        <div class="relative w-full sm:max-w-md">
            <label for="audit-log-search" class="sr-only">Cari log audit</label>
            <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input
                type="search"
                id="audit-log-search"
                name="search"
                value="{{ $search }}"
                placeholder="Cari nama atau aktiviti..."
                autocomplete="off"
                class="glass-input w-full rounded-xl py-2.5 pl-10 pr-4 text-sm"
            >
        </div>
        <div class="flex items-center gap-3">
            <button type="submit" class="glass-btn-primary rounded-xl px-4 py-2.5 text-sm font-medium">Cari</button>
            @if($search !== '')
                <a href="{{ route('users.audit-log') }}" class="text-sm font-medium text-slate-700 hover:text-slate-900">Set semula</a>
            @endif
        </div>
    </form>

    <p class="text-sm text-slate-600">
        Menunjukkan <strong class="text-slate-800">{{ $logs->count() }}</strong> daripada <strong class="text-slate-800">{{ $logs->total() }}</strong> rekod audit.
    </p>

    <div class="glass-card glass-table overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[40rem] text-sm">
                <thead>
                    <tr class="glass-divider-soft border-b bg-white/40">
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Cap Masa</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Aktiviti</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($logs as $log)
                        <tr class="glass-row-hover">
                            <td class="px-4 py-3 font-medium text-slate-800">{{ $log->displayName() }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-slate-600">{{ $log->created_at?->format('d/m/Y H:i:s') ?? '–' }}</td>
                            <td class="px-4 py-3 text-slate-700">
                                <p class="font-medium text-slate-800">{{ $log->activityLabel() }}</p>
                                <p class="mt-1 text-slate-600">{{ $log->description }}</p>
                                @if($log->affectsAnotherUser())
                                    <p class="mt-1 text-xs text-slate-500">Terhadap: {{ $log->subject_name ?: ($log->user?->name ?? 'Pengguna tidak diketahui') }}</p>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-16 text-center text-slate-500">
                                Tiada rekod audit dijumpai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>
        {{ $logs->links() }}
    </div>
</div>
@endsection
