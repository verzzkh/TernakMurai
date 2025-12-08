<x-layout>
    @php
        $perilakuList = json_decode($deteksi->perilaku ?? '[]', true) ?: [];
    @endphp


    <main>
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between px-4 py-4 border-b dark:border-primary-darker gap-3">
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">
                Hasil Deteksi Penyakit Murai Batu
            </h1>

            <div class="flex flex-wrap items-center gap-2">
                <!-- Tombol PDF -->
                <a
                    href="{{ route('peternak.deteksi-penyakit.pdf', $deteksi->id) }}"
                    class="inline-flex items-center px-4 py-2 text-xs md:text-sm font-medium text-white bg-red-600 rounded-lg shadow hover:bg-red-700 transition"
                >
                    <!-- icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 21h10M12 17v4m4-18H8a2 2 0 0 0-2 2v12h12V5a2 2 0 0 0-2-2z"/>
                    </svg>
                    Download PDF
                </a>

                <!-- Tombol Kembali -->
                <a
                    href="{{ route('peternak.deteksi-penyakit.index') }}"
                    class="inline-flex items-center px-4 py-2 text-xs md:text-sm font-medium text-gray-800 bg-gray-100 rounded-lg shadow hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-100 dark:hover:bg-gray-700 transition"
                >
                    <!-- icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 19l-7-7 7-7m11 14H10"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <div class="p-4">
            <div class="max-w-5xl mx-auto space-y-6">

                <!-- Card utama -->
                <div class="bg-white dark:bg-darker border border-gray-200 dark:border-gray-700 rounded-xl shadow-md overflow-hidden">

                    <!-- Header laporan -->
                    <div class="px-5 py-4 bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                            <div>
                                <h2 class="text-lg md:text-xl font-semibold">
                                    Laporan Analisis Kesehatan Burung
                                </h2>
                                <p class="text-xs md:text-sm text-blue-100">
                                    Dihasilkan oleh Sistem Diagnosa Berbasis AI
                                </p>
                            </div>
                            <div class="text-xs md:text-sm text-blue-100 md:text-right">
                                ID Laporan: #{{ $deteksi->id }}<br>
                                Waktu: {{ $deteksi->created_at->format('d M Y, H:i') }}
                            </div>
                        </div>
                    </div>

                    <div class="p-5 md:p-6 space-y-6">

                        <!-- Foto Burung -->
                        @if($deteksi->fotos->count())
                            <section>
                                <h3 class="text-base md:text-lg font-semibold mb-3 text-gray-800 dark:text-gray-100">
                                    Foto Burung
                                </h3>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-4">
                                    @foreach($deteksi->fotos as $foto)
                                        <div class="rounded-md overflow-hidden border border-gray-200 dark:border-gray-700 shadow-sm bg-gray-50 dark:bg-gray-800">
                                            <img
                                                src="{{ asset('storage/'.$foto->foto_path) }}"
                                                alt="Foto burung"
                                                class="w-full h-32 md:h-40 object-cover"
                                            >
                                        </div>
                                    @endforeach
                                </div>
                            </section>
                        @endif

                        <!-- Informasi Dasar + Perilaku -->
                        <section>
                            <h3 class="text-base md:text-lg font-semibold mb-3 text-gray-800 dark:text-gray-100">
                                Informasi Dasar
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm md:text-base text-gray-700 dark:text-gray-200">
                                <div class="space-y-1">
                                    <p><span class="font-semibold">Nama Burung:</span> {{ $deteksi->nama_burung ?: '-' }}</p>
                                    <p><span class="font-semibold">Tanggal Deteksi:</span> {{ $deteksi->created_at->format('d M Y, H:i') }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="font-semibold mb-1">Perilaku yang Diamati:</p>
                                    @if(count($perilakuList))
                                        <ul class="list-disc list-inside">
                                            @foreach($perilakuList as $p)
                                                <li>{{ $p }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p>-</p>
                                    @endif
                                </div>
                            </div>
                        </section>

                        <!-- Gejala, Lingkungan, Pakan, Riwayat -->
                        <section class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-sm md:text-base font-semibold mb-1 text-gray-800 dark:text-gray-100">
                                        Deskripsi Gejala
                                    </h3>
                                    <div class="p-3 md:p-4 bg-gray-50 dark:bg-gray-800/60 rounded-lg border border-gray-200 dark:border-gray-700 text-sm md:text-base text-gray-700 dark:text-gray-200">
                                        {{ $deteksi->gejala }}
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-sm md:text-base font-semibold mb-1 text-gray-800 dark:text-gray-100">
                                        Informasi Pakan & Makan
                                    </h3>
                                    <div class="p-3 md:p-4 bg-gray-50 dark:bg-gray-800/60 rounded-lg border border-gray-200 dark:border-gray-700 text-sm md:text-base text-gray-700 dark:text-gray-200">
                                        {{ $deteksi->makanan ?: '-' }}
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-sm md:text-base font-semibold mb-1 text-gray-800 dark:text-gray-100">
                                        Lingkungan Kandang
                                    </h3>
                                    <div class="p-3 md:p-4 bg-gray-50 dark:bg-gray-800/60 rounded-lg border border-gray-200 dark:border-gray-700 text-sm md:text-base text-gray-700 dark:text-gray-200">
                                        {{ $deteksi->lingkungan ?: '-' }}
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-sm md:text-base font-semibold mb-1 text-gray-800 dark:text-gray-100">
                                        Riwayat Kesehatan
                                    </h3>
                                    <div class="p-3 md:p-4 bg-gray-50 dark:bg-gray-800/60 rounded-lg border border-gray-200 dark:border-gray-700 text-sm md:text-base text-gray-700 dark:text-gray-200">
                                        {{ $deteksi->riwayat_kesehatan ?: '-' }}
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Ringkasan Diagnosis -->
                        <section>
                            <h3 class="text-base md:text-lg font-semibold mb-3 text-gray-800 dark:text-gray-100">
                                Ringkasan Diagnosis
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm md:text-base">
                                <div class="p-3 md:p-4 rounded-lg border border-blue-200 dark:border-blue-700 bg-blue-50 dark:bg-blue-900/30">
                                    <p class="text-xs uppercase tracking-wide text-blue-700 dark:text-blue-200 font-semibold mb-1">
                                        Diagnosis Utama
                                    </p>
                                    <p class="text-gray-800 dark:text-gray-100">
                                        {{ $deteksi->diagnosis_utama ?: '-' }}
                                    </p>
                                </div>

                                <div class="p-3 md:p-4 rounded-lg border border-emerald-200 dark:border-emerald-700 bg-emerald-50 dark:bg-emerald-900/30">
                                    <p class="text-xs uppercase tracking-wide text-emerald-700 dark:text-emerald-200 font-semibold mb-1">
                                        Tingkat Kepercayaan
                                    </p>
                                    <p class="text-gray-800 dark:text-gray-100">
                                        {{ $deteksi->tingkat_kepercayaan ? $deteksi->tingkat_kepercayaan.'%' : '-' }}
                                    </p>
                                </div>

                                <div class="p-3 md:p-4 rounded-lg border border-amber-200 dark:border-amber-700 bg-amber-50 dark:bg-amber-900/30">
                                    <p class="text-xs uppercase tracking-wide text-amber-700 dark:text-amber-200 font-semibold mb-1">
                                        Catatan Singkat
                                    </p>
                                    <p class="text-gray-800 dark:text-gray-100 text-xs md:text-sm">
                                        Hasil ini adalah referensi awal dari AI dan tidak menggantikan pemeriksaan langsung oleh dokter hewan.
                                    </p>
                                </div>
                            </div>
                        </section>

                        <!-- Detail Analisis AI (Markdown) -->
                        <section>
                            <h3 class="text-base md:text-lg font-semibold mb-3 text-gray-800 dark:text-gray-100">
                                Detail Analisis AI
                            </h3>
                        <div class="prose dark:prose-invert max-w-none leading-relaxed space-y-4">
    {!! Illuminate\Support\Str::markdown($deteksi->hasil_analisis) !!}
</div>

                        </section>

                    </div>
                </div>

            </div>
        </div>
    </main>
</x-layout>
