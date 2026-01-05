<x-layout>
    <main>

        <!-- HEADER -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between px-4 py-4 border-b dark:border-primary-darker gap-3">
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">
                Analisa Semua Indukan
            </h1>

            <a
                href="{{ route('peternak.indukan.index') }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-800 bg-gray-100 rounded-lg shadow hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-100 dark:hover:bg-gray-700 transition"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 19l-7-7 7-7m11 14H10"/>
                </svg>
                Kembali
            </a>
        </div>


        <div class="p-4">
            <div class="max-w-5xl mx-auto space-y-6">

                <!-- CARD UTAMA -->
                <div class="bg-white dark:bg-darker border border-gray-200 dark:border-gray-700 rounded-xl shadow-md overflow-hidden">

                    <!-- HEADER CARD -->
                    <div class="px-5 py-4 bg-gradient-to-r from-indigo-600 to-purple-700 text-white">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                            <div>
                                <h2 class="text-lg md:text-xl font-semibold">
                                    Laporan Analisis Semua Indukan & Perkawinan
                                </h2>
                                <p class="text-xs md:text-sm text-purple-100">
                                    Dihasilkan oleh Sistem Analitik Breeding Berbasis AI
                                </p>
                            </div>

                            <div class="text-xs md:text-sm text-purple-100 md:text-right">
                                Waktu Analisa: {{ now()->format('d M Y, H:i') }}
                            </div>
                        </div>
                    </div>


                    <!-- ISI LAPORAN -->
                    <div class="p-5 md:p-6 space-y-6">

                        <!-- STATUS LOADING -->
                        <div class="p-4 bg-blue-50 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 rounded-lg border border-blue-200 dark:border-blue-700">
                            Sistem sedang menganalisa seluruh indukan... mohon tunggu sebentar.
                        </div>


                        <!-- HASIL AI -->
                        <section>
                            <h3 class="text-base md:text-lg font-semibold mb-3 text-gray-800 dark:text-gray-100">
                                Hasil Analisis AI
                            </h3>

                            <div class="prose dark:prose-invert max-w-none leading-relaxed space-y-4">
                                @if($hasilAnalisa)
                                    {!! Illuminate\Support\Str::markdown($hasilAnalisa) !!}
                                @else
                                    <p class="text-gray-500">Tidak ada hasil analisa.</p>
                                @endif
                            </div>
                        </section>

                    </div>

                </div>

            </div>
        </div>

    </main>
</x-layout>
