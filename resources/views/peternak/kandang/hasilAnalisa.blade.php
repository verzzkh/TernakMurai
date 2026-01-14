<x-layout>
<main>
    <!-- HEADER -->
    <div class="flex items-center justify-between px-4 py-4 border-b dark:border-primary-darker">
        <h1 class="text-2xl font-semibold">Hasil Analisis Kecocokan Indukan</h1>

        <a href="{{ route('peternak.analisaBreeding.form') }}"
            class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg">
            Analisis Baru
        </a>
    </div>

    <div class="p-4 max-w-4xl mx-auto">

        <!-- INFORMASI INDUKAN -->
        <div class="bg-white dark:bg-darker rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Data Indukan</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="border rounded-md p-4">
                    <h3 class="font-semibold text-primary">Indukan Jantan</h3>
                    <p><strong>Ring:</strong> {{ $jantan->nomor_ring }}</p>
                    <p><strong>Nama:</strong> {{ $jantan->nama }}</p>
                    <p><strong>Umur:</strong> {{ $jantan->age['formatted'] }}</p>
                </div>

                <div class="border rounded-md p-4">
                    <h3 class="font-semibold text-purple-500">Indukan Betina</h3>
                    <p><strong>Ring:</strong> {{ $betina->nomor_ring }}</p>
                    <p><strong>Nama:</strong> {{ $betina->nama }}</p>
                    <p><strong>Umur:</strong> {{ $betina->age['formatted'] }}</p>
                </div>

            </div>
        </div>

        <!-- ANALISA -->
        <div class="bg-white dark:bg-darker rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Hasil Analisa AI</h2>

            <div class="prose dark:prose-invert max-w-full">
                {!! Illuminate\Support\Str::markdown($hasilAnalisa) !!}
            </div>
        </div>

        <!-- 🔥 TOMBOL SIMPAN -->
        <form action="{{ route('peternak.analisaBreeding.save') }}" method="POST">
            @csrf
            <input type="hidden" name="jantan_id" value="{{ $jantan->id }}">
            <input type="hidden" name="betina_id" value="{{ $betina->id }}">
            <input type="hidden" name="hasil_ai" value="{{ $hasilAnalisa }}">
             <input type="hidden" name="rekomendasi" value="{{ $rekomendasi }}">

            <button type="submit"
                class="w-full md:w-auto px-5 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded">
                Simpan ke Riwayat Analisa
            </button>
        </form>

    </div>
</main>
</x-layout>
