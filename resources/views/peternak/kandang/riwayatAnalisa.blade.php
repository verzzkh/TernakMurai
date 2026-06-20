<x-layout>
<main>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 border-b gap-3">
        <h1 class="text-xl sm:text-2xl font-semibold">Riwayat Analisa Breeding</h1>

          <a href="{{ route('peternak.analisaBreeding.form') }}"
                    class="px-4 py-2 text-white bg-purple-600 hover:bg-purple-700 
           rounded-lg focus:outline-none focus:ring focus:ring-purple-600 
           focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark">
                    Analisis Indukan
                </a>
    </div>

    <div class="p-3 sm:p-4 max-w-5xl mx-auto">

        @if(session('success'))
            <div class="p-3 bg-green-600 text-white rounded-md mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @forelse($riwayat as $item)
        <div class="bg-white dark:bg-darker rounded-lg shadow p-3 sm:p-4 mb-3 sm:mb-4">

            <div class="flex items-start justify-between gap-3">
                {{-- ================= LEFT CONTENT ================= --}}
                <div class="flex-1 min-w-0 text-sm sm:text-base">
                    <p class="truncate">
                        <strong>Jantan:</strong> {{ $item->jantan->nomor_ring }} - {{ $item->jantan->nama }}
                    </p>
                    <p class="truncate">
                        <strong>Betina:</strong> {{ $item->betina->nomor_ring }} - {{ $item->betina->nama }}
                    </p>

                    <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
                        {{ \Carbon\Carbon::parse($item->tanggal_analisa)->format('d M Y') }}
                    </p>

                    <div class="flex flex-wrap gap-2 mt-2">
                        {{-- REKOMENDASI AI --}}
                        <span class="px-2.5 py-1 rounded text-white text-xs
                            @if($item->rekomendasi=='uji_coba') bg-yellow-500 
                            @elseif($item->rekomendasi=='lanjut') bg-green-600
                            @elseif($item->rekomendasi=='stop') bg-red-600
                            @endif">
                            {{ str_replace('_', ' ', strtoupper($item->rekomendasi)) }}
                        </span>

                        {{-- TINDAK LANJUT --}}
                        @if($item->tindak_lanjut_peternak)
                            <span class="px-2.5 py-1 rounded text-white text-xs
                                @if($item->tindak_lanjut_peternak=='lanjut') bg-green-700
                                @elseif($item->tindak_lanjut_peternak=='pantau') bg-blue-600
                                @elseif($item->tindak_lanjut_peternak=='stop') bg-red-700
                                @endif">
                                Tindak Lanjut: {{ strtoupper($item->tindak_lanjut_peternak) }}
                            </span>
                        @else
                            <span class="px-2.5 py-1 rounded bg-gray-400 text-white text-xs">
                               Tindak Lanjut: BELUM DITENTUKAN
                            </span>
                        @endif

                        @if($item->pairing)
                            <span class="px-2.5 py-1 rounded text-white text-xs {{ $item->pairing->status === 'aktif' ? 'bg-emerald-700' : 'bg-slate-700' }}">
                                Pairing: {{ strtoupper($item->pairing->status) }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- ================= RIGHT ACTION ================= --}}
                <div class="flex flex-col gap-2 shrink-0">
                    <a href="{{ route('peternak.analisaBreeding.detail', $item->id) }}"
                        class="px-3 py-1.5 bg-gray-700 text-white rounded text-xs sm:text-sm text-center hover:bg-gray-800">
                        Detail
                    </a>

                    <form action="{{ route('peternak.analisaBreeding.hapus', $item->id) }}"
                          method="POST"
                          onsubmit="return confirm('Hapus riwayat analisa ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full px-3 py-1.5 bg-red-600 text-white rounded text-xs sm:text-sm hover:bg-red-700">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>

            {{-- ================= CATATAN ================= --}}
            @if($item->catatan_user)
                <div class="mt-3 p-2.5 bg-gray-50 dark:bg-gray-800 rounded border text-xs sm:text-sm leading-relaxed">
                    <strong>Catatan Lapangan:</strong><br>
                    {!! nl2br(e($item->catatan_user)) !!}
                </div>
            @endif
        </div>
        @empty
            <p class="text-gray-600 text-center mt-10 text-sm">
                Belum ada riwayat analisa.
            </p>
        @endforelse

    </div>
</main>
</x-layout>
