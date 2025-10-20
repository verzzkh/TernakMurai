<x-layout>
    <main>
        <!-- Content header -->
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <div>
                <h1 class="text-2xl font-semibold">Hasil Deteksi Penyakit</h1>
                <p class="text-gray-500 dark:text-gray-400">
                    {{ $result['created_at']->format('d M Y, H:i') }}
                </p>
            </div>
            <a href="{{ route('deteksi-penyakit.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Deteksi Baru
            </a>
        </div>

        <!-- Content -->
        <div class="mt-2">
            <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Left Column - Input Information -->
                <div class="md:col-span-1 space-y-6">
                    <!-- Input Information -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden dark:bg-darker">
                        <div class="p-4 bg-gradient-to-r from-blue-600 to-blue-800 text-white dark:from-blue-800 dark:to-indigo-900">
                            <h2 class="text-lg font-semibold">Informasi Gejala</h2>
                        </div>
                        <div class="p-5 space-y-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Deskripsi Gejala</h3>
                                <p class="text-gray-700 dark:text-gray-300">{{ $result['symptoms'] }}</p>
                            </div>

                            @if(!empty($result['behaviors']))
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Perubahan Perilaku</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($result['behaviors'] as $behavior)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                        {{ $behavior }}
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            @if(!empty($result['diet_info']))
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Informasi Pakan & Makan</h3>
                                <p class="text-gray-700 dark:text-gray-300">{{ $result['diet_info'] }}</p>
                            </div>
                            @endif

                            @if(!empty($result['environment_info']))
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Informasi Lingkungan</h3>
                                <p class="text-gray-700 dark:text-gray-300">{{ $result['environment_info'] }}</p>
                            </div>
                            @endif

                            @if(!empty($result['health_history']))
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Riwayat Kesehatan</h3>
                                <p class="text-gray-700 dark:text-gray-300">{{ $result['health_history'] }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Image Gallery -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden dark:bg-darker">
                        <div class="p-4 bg-gradient-to-r from-blue-600 to-blue-800 text-white dark:from-blue-800 dark:to-indigo-900">
                            <h2 class="text-lg font-semibold">Foto yang Dianalisis</h2>
                        </div>
                        <div class="p-5">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($result['images'] as $image)
                                <div class="relative group">
                                    <img src="{{ asset('storage/' . $image) }}" alt="Foto Murai Batu" class="w-full h-32 object-cover rounded-lg shadow-sm hover:shadow transition">
                                    <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center">
                                        <button type="button" onclick="openFullImage('{{ asset('storage/' . $image) }}')" class="p-2 bg-white rounded-full text-gray-800 hover:bg-gray-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M5 8a1 1 0 011-1h1V6a1 1 0 012 0v1h1a1 1 0 110 2H9v1a1 1 0 11-2 0V9H6a1 1 0 01-1-1z" />
                                                <path fill-rule="evenodd" d="M2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8zm6-4a4 4 0 100 8 4 4 0 000-8z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Analysis Results -->
                <div class="md:col-span-2 space-y-6">
                    <!-- Possible Disease -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden dark:bg-darker">
                        <div class="p-4 bg-gradient-to-r from-emerald-600 to-teal-600 text-white dark:from-emerald-800 dark:to-teal-800">
                            <h2 class="text-lg font-semibold">Kemungkinan Penyakit</h2>
                        </div>
                        <div class="p-5">
                            <div class="rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                                <div class="bg-gray-50 dark:bg-gray-800 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                    <div class="flex justify-between font-medium">
                                        <span class="text-gray-700 dark:text-gray-300">Penyakit</span>
                                        <span class="text-gray-700 dark:text-gray-300">Kemungkinan</span>
                                    </div>
                                </div>
                                <div>
                                    @foreach($result['possible_diseases'] as $index => $disease)
                                    <div class="px-4 py-3 flex justify-between items-center {{ $index > 0 ? 'border-t border-gray-200 dark:border-gray-700' : '' }}">
                                        <span class="text-gray-800 dark:text-gray-200 font-medium">{{ $disease['name'] }}</span>
                                        <div class="flex items-center">
                                            <div class="w-32 bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mr-2 overflow-hidden">
                                                <div class="h-2.5 rounded-full {{ $disease['probability'] > 80 ? 'bg-emerald-500' : ($disease['probability'] > 50 ? 'bg-blue-500' : 'bg-amber-500') }}" style="width: {{ $disease['probability'] }}%"></div>
                                            </div>
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 min-w-[40px] text-right">{{ $disease['probability'] }}%</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Diagnosis Details -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden dark:bg-darker">
                        <div class="p-4 bg-gradient-to-r from-emerald-600 to-teal-600 text-white dark:from-emerald-800 dark:to-teal-800">
                            <h2 class="text-lg font-semibold">Diagnosis Lengkap</h2>
                        </div>
                        <div class="p-5">
                            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $result['diagnosis'] }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Recommendations -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden dark:bg-darker">
                        <div class="p-4 bg-gradient-to-r from-emerald-600 to-teal-600 text-white dark:from-emerald-800 dark:to-teal-800">
                            <h2 class="text-lg font-semibold">Rekomendasi Penanganan</h2>
                        </div>
                        <div class="p-5">
                            <div class="bg-teal-50 dark:bg-teal-900/20 rounded-lg p-4 border border-teal-100 dark:border-teal-900/50">
                                <ul class="space-y-3">
                                    @foreach($result['recommendations'] as $index => $recommendation)
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 flex items-center justify-center w-7 h-7 rounded-full bg-teal-100 text-teal-800 dark:bg-teal-800/40 dark:text-teal-300 mr-3 mt-0.5 font-bold text-sm">{{ $index + 1 }}</span>
                                        <span class="text-gray-700 dark:text-gray-300">{{ $recommendation }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Disclaimer -->
                    <div class="bg-amber-50 rounded-lg border-l-4 border-amber-500 p-4 dark:bg-amber-900/30 dark:border-amber-600">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-amber-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-amber-800 dark:text-amber-200 text-sm">
                                    <strong class="font-bold">Disclaimer:</strong> Hasil analisis ini hanya bersifat referensi dan tidak menggantikan diagnosis dari dokter hewan profesional. Silakan konsultasikan dengan dokter hewan untuk konfirmasi dan penanganan lebih lanjut.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4">
                        <button onclick="window.print()" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-offset-gray-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Cetak Hasil
                        </button>
                        <a href="{{ route('deteksi-penyakit.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Deteksi Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Image Full Screen Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4">
        <div class="relative max-w-4xl w-full">
            <img id="fullImage" src="" alt="Foto Murai Batu" class="w-full h-auto max-h-[80vh] object-contain rounded-lg">
            <button type="button" onclick="closeModal()" class="absolute top-4 right-4 p-2 bg-white rounded-full text-gray-800 hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Print Styles -->
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            main, main * {
                visibility: visible;
            }
            main {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print, .no-print * {
                display: none !important;
            }
            a, button {
                display: none !important;
            }
        }
    </style>

    <script>
        // Full Screen Image Modal
        function openFullImage(src) {
            document.getElementById('fullImage').src = src;
            document.getElementById('imageModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside the image
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Close modal with escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</x-layout>