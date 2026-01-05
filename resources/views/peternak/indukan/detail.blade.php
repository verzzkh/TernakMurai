<x-layout>
    <main>
        <!-- Content header -->
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <div>
                <h1 class="text-2xl font-semibold">{{ $indukan->nomor_ring }}</h1>
                @if ($indukan->nama)
                    <p class="text-gray-600 dark:text-gray-400">{{ $indukan->nama }}</p>
                @endif
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('peternak.indukan.edit', $indukan) }}"
                    class="px-4 py-2 text-sm font-medium
          text-indigo-700 bg-indigo-300
          hover:bg-indigo-400
          dark:text-indigo-100 dark:bg-indigo-700 
          dark:hover:bg-indigo-600
          rounded-md focus:outline-none">
                    Edit
                </a>

                <a href="{{ route('peternak.indukan.index') }}"
                    class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 
          rounded-lg
          focus:outline-none focus:ring focus:ring-blue-600 
          focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark">
                    Kembali
                </a>

            </div>
        </div>

        <div class="px-4 py-6">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Informasi Indukan -->
                <div class="lg:col-span-2">

                    <div class="bg-white dark:bg-darker rounded-lg shadow p-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Indukan</h2>
                        <!-- Foto Indukan (Responsive + Overlay Button) -->
                        <div
                            class="relative w-full h-60 sm:h-72 md:h-80 bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden mb-6">

                            <!-- Foto -->
                            <img src="{{ $indukan->foto_url }}" alt="Foto Indukan" class="w-full h-full object-cover">

                            <!-- Tombol Ganti Foto (overlay) -->
                            <button id="changePhotoBtn"
                                class="absolute top-3 right-3 bg-white bg-opacity-80 hover:bg-opacity-100 text-gray-900 text-xs px-3 py-1 rounded-md shadow-md">
                                Ganti Foto
                            </button>
                        </div>


                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Ring</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $indukan->nomor_ring }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Kelamin</dt>
                                <dd class="mt-1">
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full {{ $indukan->jenis_kelamin === 'jantan' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200' }}">
                                        {{ ucfirst($indukan->jenis_kelamin) }}
                                    </span>
                                </dd>
                            </div>

                            @if ($indukan->nama)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $indukan->nama }}</dd>
                                </div>
                            @endif

                            @if ($indukan->tanggal_lahir)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Lahir</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                        {{ $indukan->tanggal_lahir->format('d M Y') }}
                                        ({{ $indukan->age['formatted'] }})
                                    </dd>
                                </div>
                            @endif

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Anakan</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $indukan->getAnakanCount() }}
                                    anakan</dd>
                            </div>
                        </div>

                        @if ($indukan->catatan)
                            <div class="mt-6">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $indukan->catatan }}</dd>
                            </div>
                        @endif

                        @if ($indukan->prestasi)
                            <div class="mt-6">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Prestasi</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $indukan->prestasi }}</dd>
                            </div>
                        @endif

                        @if ($indukan->karakteristik)
                            <div class="mt-6">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Karakteristik</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $indukan->karakteristik }}
                                </dd>
                            </div>
                        @endif
                        <!-- PERILAKU BREEDING - View Only -->
<h2 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Perilaku Breeding</h2>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">

    {{-- Jantan --}}
    @if($indukan->jenis_kelamin=='jantan')
    <div class="space-y-1">
        <p>• Aktif Kicau :
            <b class="ml-1">{{ $indukan->aktif_kicau ? '✔ Ya' : '✘ Tidak' }}</b>
        </p>
        <p>• Mendekati Betina :
            <b class="ml-1">{{ $indukan->mendekati_betina ? '✔ Ya' : '✘ Tidak' }}</b>
        </p>
    </div>
    @endif

    {{-- Betina --}}
    @if($indukan->jenis_kelamin=='betina')
    <div class="space-y-1">
        <p>• Nafsu Makan Meningkat :
            <b class="ml-1">{{ $indukan->nafsu_makan_meningkat ? '✔ Ya' : '✘ Tidak' }}</b>
        </p>
        <p>• Aktif Membuat Sarang :
            <b class="ml-1">{{ $indukan->aktif_buat_sarang ? '✔ Ya' : '✘ Tidak' }}</b>
        </p>
    </div>
    @endif

    {{-- Temperamen --}}
    <div>
        <p>• Temperamen :
            <b class="capitalize ml-1">{{ $indukan->temperamen ?? '-' }}</b>
        </p>
    </div>

</div>



                    </div>

                    <!-- Anakan dari Indukan ini -->
                    <div class="mt-6 bg-white dark:bg-darker rounded-lg shadow p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Anakan dari Indukan ini</h2>
                            <a href="{{ route('peternak.anakan.create') }}?indukan_id={{ $indukan->id }}"
                                class="px-3 py-1 text-sm text-white 
           bg-cyan-600 hover:bg-cyan-700
           dark:bg-cyan-500 dark:hover:bg-cyan-600
           rounded-md focus:outline-none
           focus:ring focus:ring-cyan-400 focus:ring-offset-1">
                                Tambah Anakan
                            </a>

                        </div>

                        @if ($indukan->anakans->count() > 0)
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach ($indukan->anakans as $anakan)
                                    <div class="border dark:border-primary-darker rounded-lg p-4">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $anakan->nomor_ring ?? 'Belum ada ring' }}
                                            </h3>
                                            <span
                                                class="px-2 py-1 text-xs font-medium rounded-full {{ $anakan->jenis_kelamin === 'jantan' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : ($anakan->jenis_kelamin === 'betina' ? 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200') }}">
                                                {{ ucfirst(str_replace('_', ' ', $anakan->jenis_kelamin)) }}
                                            </span>
                                        </div>
                                        @if ($anakan->tanggal_lahir)
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                {{ $anakan->tanggal_lahir->format('d M Y') }}
                                                ({{ $anakan->age['formatted'] }})
                                            </p>
                                        @endif
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            Status: {{ ucfirst(str_replace('_', ' ', $anakan->status_pertumbuhan)) }}
                                        </p>
                                        <div class="mt-2">
                                            <a href="{{ route('peternak.anakan.show', $anakan) }}"
                                                class="text-xs text-primary hover:text-primary-dark">Lihat Detail</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada anakan
                                </h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Indukan ini belum memiliki
                                    anakan.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Kandang yang menggunakan indukan ini -->
                    @if ($indukan->kandangsJantan->count() > 0 || $indukan->kandangsBetina->count() > 0)
                        <div class="bg-white dark:bg-darker rounded-lg shadow p-6 mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Kandang</h3>

                            @if ($indukan->kandangsJantan->count() > 0)
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sebagai
                                        Jantan
                                    </h4>
                                    <div class="space-y-2">
                                        @foreach ($indukan->kandangsJantan as $kandang)
                                            <div class="text-sm">
                                                <a href="{{ route('peternak.kandang.show', $kandang) }}"
                                                    class="text-primary hover:text-primary-dark">{{ $kandang->nomor_kandang }}</a>
                                                <span
                                                    class="text-gray-500 dark:text-gray-400">({{ ucfirst($kandang->status) }})</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if ($indukan->kandangsBetina->count() > 0)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sebagai
                                        Betina
                                    </h4>
                                    <div class="space-y-2">
                                        @foreach ($indukan->kandangsBetina as $kandang)
                                            <div class="text-sm">
                                                <a href="{{ route('peternak.kandang.show', $kandang) }}"
                                                    class="text-primary hover:text-primary-dark">{{ $kandang->nomor_kandang }}</a>
                                                <span
                                                    class="text-gray-500 dark:text-gray-400">({{ ucfirst($kandang->status) }})</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Statistik -->
                    <div class="bg-white dark:bg-darker rounded-lg shadow p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Statistik</h3>

                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Total Anakan</span>
                                <span
                                    class="text-sm font-medium text-gray-900 dark:text-white">{{ $indukan->getAnakanCount() }}</span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Anakan Jantan</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $indukan->anakans->where('jenis_kelamin', 'jantan')->count() }}
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Anakan Betina</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $indukan->anakans->where('jenis_kelamin', 'betina')->count() }}
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Anakan Aktif</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $indukan->anakans->where('status_penjualan', 'belum_dijual')->count() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Upload Foto Indukan -->
    <div id="uploadPhotoModal"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white dark:bg-darker rounded-lg w-full max-w-md mx-4 overflow-hidden">
            <div class="px-4 py-3 border-b dark:border-primary-darker flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-700 dark:text-light">Upload Foto Baru</h3>
                <button id="closePhotoModal"
                    class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-light mb-2">Pilih Foto</label>
                    <div class="border-2 border-dashed dark:border-primary-darker rounded-md px-6 pt-5 pb-6">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                viewBox="0 0 48 48" aria-hidden="true">
                                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" />
                            </svg>

                            <div class="flex text-sm text-gray-600">
                                <label for="file-upload-indukan"
                                    class="relative cursor-pointer rounded-md font-medium text-primary">
                                    <span>Pilih foto</span>
                                    <input id="file-upload-indukan" name="file-upload-indukan" type="file"
                                        accept="image/*" class="sr-only">
                                </label>
                            </div>

                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, JPEG max 2MB</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button id="cancelPhotoUpload"
                        class="px-4 py-2 text-sm bg-gray-200 dark:bg-gray-700 rounded-md hover:bg-gray-300">
                        Batal
                    </button>

                    <button id="confirmPhotoUpload"
                        class="px-4 py-2 text-sm  bg-gray-200 dark:bg-gray-700 text-white bg-primary rounded-md hover:bg-primary-dark">
                        Upload
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        const changePhotoBtn = document.getElementById('changePhotoBtn');
        const uploadPhotoModal = document.getElementById('uploadPhotoModal');
        const closePhotoModal = document.getElementById('closePhotoModal');
        const cancelPhotoUpload = document.getElementById('cancelPhotoUpload');
        const confirmPhotoUpload = document.getElementById('confirmPhotoUpload');
        const fileUploadIndukan = document.getElementById('file-upload-indukan');

        function togglePhotoModal(show = true) {
            uploadPhotoModal.classList.toggle('hidden', !show);
        }

        changePhotoBtn?.addEventListener('click', () => togglePhotoModal(true));
        closePhotoModal?.addEventListener('click', () => togglePhotoModal(false));
        cancelPhotoUpload?.addEventListener('click', () => togglePhotoModal(false));

        confirmPhotoUpload?.addEventListener('click', async () => {
            if (!fileUploadIndukan.files[0]) {
                alert("Pilih foto terlebih dahulu");
                return;
            }

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'PUT'); // spoof method
            formData.append('foto_indukan', fileUploadIndukan.files[0]);

            const url = "{{ route('peternak.indukan.update-foto', $indukan->id) }}";

            const res = await fetch(url, {
                method: "POST", // tetap POST saat dikirim
                body: formData
            });

            const data = await res.json();

            if (data.success) {
                location.reload();
            } else {
                alert("Gagal memperbarui foto");
            }
        });
    </script>

</x-layout>
