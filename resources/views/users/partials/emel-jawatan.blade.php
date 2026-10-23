<section id="emel-jawatan" class="glass-card glass-table overflow-hidden">
    <div class="glass-divider-soft border-b px-4 py-3">
        <h2 class="text-base font-semibold text-slate-900">Salinan Emel Jawatan</h2>
        <p class="mt-0.5 text-xs text-slate-500">Emel ini disalin pada amaran kontrak yang berbaki 3 bulan. Tukar emel di sini jika penyandang jawatan bertukar.</p>
    </div>
    <form method="POST" action="{{ route('users.emel-jawatan.update') }}">
        @csrf
        @method('PUT')
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="glass-divider-soft border-b bg-white/40">
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Nama Jawatan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Emel</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($emelJawatan as $jawatan)
                        <tr>
                            <td class="px-4 py-3 font-medium text-slate-800">{{ $jawatan->nama_jawatan }}</td>
                            <td class="px-4 py-3">
                                <label for="emel-jawatan-{{ $jawatan->kod }}" class="sr-only">Emel {{ $jawatan->nama_jawatan }}</label>
                                <input
                                    type="email"
                                    id="emel-jawatan-{{ $jawatan->kod }}"
                                    name="emails[{{ $jawatan->kod }}]"
                                    value="{{ old('emails.'.$jawatan->kod, $jawatan->email) }}"
                                    required
                                    autocomplete="off"
                                    class="glass-input w-full max-w-md rounded-xl px-3 py-2 text-sm"
                                >
                                @error('emails.'.$jawatan->kod)
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="glass-divider-soft border-t px-4 py-3">
            <button type="submit" class="glass-btn-primary rounded-xl px-4 py-2.5 text-sm font-medium">
                Simpan emel
            </button>
        </div>
    </form>
</section>
