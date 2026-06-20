<x-layout>
<main class="p-5 max-w-4xl mx-auto">
    @php
        $tindakLanjutLabels = [
            'lanjut' => 'Lanjut pairing',
            'pantau' => 'Pantau kondisi',
            'stop' => 'Hentikan sementara',
        ];
    @endphp

  <div class="flex items-center justify-between p-4 border-b">
        <h1 class="text-2xl font-semibold">Detail Analisa Breeding</h1>

        <a href="{{ route('peternak.analisaBreeding.riwayat') }}"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Kembali
        </a>
    </div>

    <div class="bg-white p-4 rounded shadow mb-4">
        <p><strong>Jantan:</strong> {{ $data->jantan->nomor_ring }} - {{ $data->jantan->nama }}</p>
        <p><strong>Betina:</strong> {{ $data->betina->nomor_ring }} - {{ $data->betina->nama }}</p>
        <p><strong>Tanggal Analisa:</strong> {{ $data->tanggal_analisa->format('d M Y') }}</p>
        <p><strong>Rekomendasi Awal:</strong> {{ strtoupper($data->rekomendasi) }}</p>
        @if($data->pairing)
            <p><strong>Status Pairing:</strong> {{ strtoupper($data->pairing->status) }}</p>
        @endif
    </div>

    <div class="bg-white p-4 rounded shadow mb-4">
        <h2 class="font-semibold mb-2">Hasil Analisa AI</h2>

        <div class="prose">
            {!! Illuminate\Support\Str::markdown($data->hasil_ai) !!}
        </div>
    </div>
{{-- ===================== TINDAK LANJUT PETERNAK ===================== --}}
<div class="bg-white p-4 rounded shadow mb-4">
    <h2 class="font-semibold mb-2">Tindak Lanjut Peternak</h2>

    @if(session('error'))
        <p class="mb-3 rounded bg-red-50 px-3 py-2 text-sm text-red-700">
            {{ session('error') }}
        </p>
    @endif

    @if($data->tindak_lanjut_peternak)
        <div class="rounded border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            <p class="font-semibold">Keputusan tersimpan</p>
            <p class="mt-1">{{ $tindakLanjutLabels[$data->tindak_lanjut_peternak] ?? ucfirst($data->tindak_lanjut_peternak) }}</p>
            <p class="mt-1 text-xs text-emerald-700">
                Tindak lanjut untuk hasil analisa ini bersifat final dan disimpan sebagai histori keputusan.
            </p>
        </div>
    @else
        <form method="POST" action="{{ route('peternak.analisaBreeding.updateTindakLanjut', $data->id) }}">
            @csrf

            <div class="flex flex-col gap-2 mt-2">
                <label class="flex items-center gap-2">
                    <input type="radio" name="tindak_lanjut_peternak" value="lanjut"
                        {{ old('tindak_lanjut_peternak') === 'lanjut' ? 'checked' : '' }}>
                    <span>Lanjut pairing</span>
                </label>

                <label class="flex items-center gap-2">
                    <input type="radio" name="tindak_lanjut_peternak" value="pantau"
                        {{ old('tindak_lanjut_peternak') === 'pantau' ? 'checked' : '' }}>
                    <span>Pantau kondisi</span>
                </label>

                <label class="flex items-center gap-2">
                    <input type="radio" name="tindak_lanjut_peternak" value="stop"
                        {{ old('tindak_lanjut_peternak') === 'stop' ? 'checked' : '' }}>
                    <span>Hentikan sementara</span>
                </label>
            </div>

            @if($data->pairing && $data->pairing->status === 'dihentikan')
                <p class="text-xs text-red-700 mt-2 italic">
                    Pairing ini sedang dihentikan. Analisa baru akan diblokir sampai pairing diaktifkan kembali.
                </p>
            @endif

            <button class="mt-3 px-4 py-2 bg-indigo-600 text-white rounded shadow hover:bg-indigo-700">
                Simpan Tindak Lanjut
            </button>
        </form>

        <p class="text-xs text-gray-500 mt-2 italic">
            Tindak lanjut belum ditentukan.
        </p>
    @endif
</div>
{{-- =============================================================== --}}

 <form method="POST" action="{{ route('peternak.analisaBreeding.updateCatatan', $data->id) }}">
    @csrf

    <label class="font-semibold">Catatan Lapangan / Hasil Breeding:</label>
    <textarea name="catatan_user" rows="4" class="w-full p-2 border rounded mt-2"
        placeholder="Contoh: Hari ke-10 mulai bertelur, hari ke-12 inkubasi..." >{{ $data->catatan_user }}</textarea>

    <button class="mt-3 px-4 py-2 bg-green-600 text-white rounded shadow hover:bg-green-700">
        Simpan Catatan
    </button>
</form>

@if(session('success'))
    <p class="mt-3 text-green-600 font-semibold">{{ session('success') }}</p>
@endif

@if(session('info'))
    <p class="mt-3 text-blue-600 font-semibold">{{ session('info') }}</p>
@endif


</main>
</x-layout>
