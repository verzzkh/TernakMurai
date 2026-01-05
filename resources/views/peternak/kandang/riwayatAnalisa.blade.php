<x-layout>
<main>
    <div class="flex items-center justify-between p-4 border-b">
        <h1 class="text-2xl font-semibold">Riwayat Analisa Breeding</h1>

        <a href="{{ route('peternak.analisaBreeding.form') }}"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Kembali
        </a>
    </div>

    <div class="p-4 max-w-5xl mx-auto">

        @if(session('success'))
            <div class="p-3 bg-green-600 text-white rounded-md mb-4">
                {{ session('success') }}
            </div>
        @endif

      @forelse($riwayat as $item)
<div class="bg-white dark:bg-darker rounded-lg shadow p-4 mb-4">
    <div class="flex justify-between">
        <div>
            <p><strong>Jantan:</strong> {{ $item->jantan->nomor_ring }} - {{ $item->jantan->nama }}</p>
            <p><strong>Betina:</strong> {{ $item->betina->nomor_ring }} - {{ $item->betina->nama }}</p>
            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($item->tanggal_analisa)->format('d M Y') }}</p>

            <span class="inline-block mt-2 px-3 py-1 rounded text-white text-sm
                @if($item->rekomendasi=='uji_coba') bg-yellow-500 
                @elseif($item->rekomendasi=='lanjut') bg-green-600
                @elseif($item->rekomendasi=='stop') bg-red-600
                @endif"
            >
                {{ strtoupper($item->rekomendasi) }}
            </span>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('peternak.analisaBreeding.detail', $item->id) }}"
                class="px-3 py-2 bg-gray-700 text-white rounded hover:bg-gray-800">
                Detail
            </a>
             <form action="{{ route('peternak.analisaBreeding.hapus', $item->id) }}" method="POST"
          onsubmit="return confirm('Hapus riwayat analisa ini?');">
        @csrf
        @method('DELETE')
        <button type="submit"
            class="px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700">
            Hapus
        </button>
    </form>

        </div>
    </div>

    {{-- ===================== CATATAN USER SECTION ===================== --}}
    @if($item->catatan_user)
    <div class="mt-3 p-3 bg-gray-50 dark:bg-gray-800 rounded border text-sm leading-6">
            <strong>Catatan Lapangan:</strong><br>
           {!! nl2br(e($item->catatan_user)) !!}
       </div>
           @else
       <p class="text-xs text-gray-500 mt-2 italic">Belum ada catatan lapangan.</p>
   @endif
       {{-- =============================================================== --}}

</div>
@empty

            <p class="text-gray-600 text-center mt-10">Belum ada riwayat analisa.</p>
        @endforelse

        
    </div>
</main>
</x-layout>
