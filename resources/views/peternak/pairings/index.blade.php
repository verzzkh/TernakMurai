<x-layout>
    <main>
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <div>
                <h1 class="text-2xl font-semibold">Manajemen Pairing</h1>
                <p class="text-sm text-gray-500">Aktivasi pairing dan lihat analisa terakhir per pasangan.</p>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('peternak.analisaBreeding.form') }}"
                    class="px-4 py-2 text-sm text-white bg-cyan-600 hover:bg-cyan-700 rounded-lg">
                    Evaluasi Breeding
                </a>
                <a href="{{ route('peternak.kandang.index') }}"
                    class="px-4 py-2 text-sm text-white bg-slate-600 hover:bg-slate-700 rounded-lg">
                    Kandang
                </a>
            </div>
        </div>

        <div class="p-4 max-w-6xl mx-auto">
            @if(session('success'))
                <div class="mb-4 p-3 text-sm text-white bg-green-600 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-3 text-sm text-white bg-red-600 rounded-md">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-darker rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-800 text-left">
                            <tr>
                                <th class="px-4 py-3">Pairing</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Trip</th>
                                <th class="px-4 py-3">Analisa Terakhir</th>
                                <th class="px-4 py-3">Perubahan Histori</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pairings as $pairing)
                                <tr class="border-t dark:border-gray-700">
                                    <td class="px-4 py-3">
                                        <div class="font-medium">
                                            {{ $pairing->indukanJantan?->nomor_ring }} - {{ $pairing->indukanJantan?->nama }}
                                        </div>
                                        <div class="text-gray-500">
                                            {{ $pairing->indukanBetina?->nomor_ring }} - {{ $pairing->indukanBetina?->nama }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $pairing->status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-slate-200 text-slate-800' }}">
                                            {{ strtoupper($pairing->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ $pairing->perkawinans_count }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($pairing->lastAnalysis)
                                            <div>{{ $pairing->lastAnalysis->tanggal_analisa?->format('d M Y H:i') }}</div>
                                            <a href="{{ route('peternak.analisaBreeding.detail', $pairing->lastAnalysis->id) }}"
                                                class="text-cyan-700 hover:text-cyan-800">
                                                Lihat hasil terakhir
                                            </a>
                                        @else
                                            <span class="text-gray-500">Belum ada analisa</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ $pairing->last_historical_change_at?->format('d M Y H:i') ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <form method="POST" action="{{ route('peternak.pairings.updateStatus', $pairing) }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="{{ $pairing->status === 'aktif' ? 'dihentikan' : 'aktif' }}">
                                            <button type="submit"
                                                class="px-3 py-2 text-xs text-white rounded-md {{ $pairing->status === 'aktif' ? 'bg-red-600 hover:bg-red-700' : 'bg-emerald-600 hover:bg-emerald-700' }}">
                                                {{ $pairing->status === 'aktif' ? 'Hentikan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                        Belum ada pairing yang tercatat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</x-layout>
