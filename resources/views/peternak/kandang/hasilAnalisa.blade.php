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
                <h2 class="text-xl font-semibold text-gray-700 dark:text-light mb-4">
                    Data Indukan yang Dianalisis
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="border rounded-md dark:border-primary-darker p-4">
                        <h3 class="font-semibold text-primary">Indukan Jantan</h3>
                        <p><strong>Ring:</strong> {{ $jantan->nomor_ring }}</p>
                        <p><strong>Nama:</strong> {{ $jantan->nama ?? '-' }}</p>
                        <p><strong>Umur:</strong> {{ $jantan->age['formatted'] }}</p>
                    </div>

                    <div class="border rounded-md dark:border-primary-darker p-4">
                        <h3 class="font-semibold text-purple-500">Indukan Betina</h3>
                        <p><strong>Ring:</strong> {{ $betina->nomor_ring }}</p>
                        <p><strong>Nama:</strong> {{ $betina->nama ?? '-' }}</p>
                        <p><strong>Umur:</strong> {{ $betina->age['formatted'] }}</p>
                    </div>

                </div>
            </div>

            <!-- ANALISA DARI AI -->
            <div class="bg-white dark:bg-darker rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-700 dark:text-light mb-4">
                    Hasil Analisa AI
                </h2>

                @if($hasilAnalisa)
                    <div class="prose dark:prose-invert max-w-full">
                        {!! Illuminate\Support\Str::markdown($hasilAnalisa) !!}
                    </div>
                @else
                    <p class="text-gray-600 dark:text-gray-300">Tidak ada hasil analisa.</p>
                @endif
            </div>
        </div>
    </main>
</x-layout>
