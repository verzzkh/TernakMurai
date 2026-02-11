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
                            @if ($indukan->jenis_kelamin == 'jantan')
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
                            @if ($indukan->jenis_kelamin == 'betina')
                                <div class="space-y-1">
                                    <p>• Nafsu Makan Meningkat :
                                        <b
                                            class="ml-1">{{ $indukan->nafsu_makan_meningkat ? '✔ Ya' : '✘ Tidak' }}</b>
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

                    @php
                        $allPerkawinans = collect()
                            ->merge($indukan->perkawinansJantan ?? collect())
                            ->merge($indukan->perkawinansBetina ?? collect());

                        $allPerkawinans = $allPerkawinans->sortBy(function ($p) {
                            return $p->tanggal_kawin ? $p->tanggal_kawin->timestamp : 0;
                        });

                        $groupedPerkawinan = $allPerkawinans->groupBy(function ($p) {
                            return $p->indukan_jantan_id . '-' . $p->indukan_betina_id;
                        });
                    @endphp


                    @if ($groupedPerkawinan->isNotEmpty())
                        <div class="mt-6 bg-white dark:bg-darker rounded-lg shadow p-6">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                Riwayat Breeding Indukan Ini
                            </h2>

                            @foreach ($groupedPerkawinan as $pairKey => $perkawinanGroup)
                                @php
                                    $first = $perkawinanGroup->first();

                                    $pairName =
                                        ($first->indukanJantan?->nomor_ring ?? 'J?') .
                                        ' × ' .
                                        ($first->indukanBetina?->nomor_ring ?? 'B?');

                                    $totalTrip = $perkawinanGroup->count();
                                    $totalAnakan = $perkawinanGroup->sum(fn($p) => $p->anakans->count());
                                @endphp

                                <div
                                    class="mb-6 border border-primary/40 dark:border-primary-darker rounded-lg shadow-md bg-white dark:bg-gray-900 p-5">

                                    {{-- HEADER PASANGAN --}}
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <h3 class="text-lg font-bold text-primary">
                                                🐦 {{ $pairName }}
                                            </h3>
                                            <p class="text-sm">
                                                Total Trip: <span class="text-primary">{{ $totalTrip }}</span> |
                                                Total Anakan: <span class="text-primary">{{ $totalAnakan }}</span>
                                            </p>
                                        </div>

                                        <button type="button"
                                            onclick="togglePair('indukan-pair-{{ $loop->index }}', this)"
                                            class="px-3 py-1 text-xs font-semibold 
                           bg-gray-100 dark:bg-gray-800 
                           rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                                            👁️ Lihat Riwayat
                                        </button>
                                    </div>

                                    {{-- DAFTAR TRIP (DEFAULT HIDDEN) --}}
                                    <div id="indukan-pair-{{ $loop->index }}" class="hidden mt-3 space-y-4">

                                        @foreach ($perkawinanGroup->values() as $index => $perkawinan)
                                            @php
                                                $anakans = $perkawinan->anakans;
                                                $countTotal = $anakans->count();
                                                $countJantan = $anakans->where('jenis_kelamin', 'jantan')->count();
                                                $countBetina = $anakans->where('jenis_kelamin', 'betina')->count();
                                            @endphp

                                            <div class="relative pl-4 border-l-4 border-primary/70">

                                                <div class="text-sm font-semibold flex flex-wrap items-center gap-1">
                                                    <span
                                                        class="inline-block w-2 h-2 bg-primary rounded-full mr-2"></span>

                                                    Trip {{ $index + 1 }}
                                                    @if ($perkawinan->status === 'gagal')
                                                        <span
                                                            class="ml-2 px-2 py-0.5 text-xs font-semibold 
        bg-red-100 text-red-700 
        dark:bg-red-900 dark:text-red-200 
        rounded-full">
                                                            GAGAL
                                                        </span>
                                                    @endif

                                                    <span class="font-normal text-gray-600 dark:text-gray-300">
                                                        — {{ $perkawinan->tanggal_kawin?->format('d M Y') ?? '-' }}
                                                    </span>
                                                    , total <span
                                                        class="text-primary font-semibold">{{ $countTotal }}</span>
                                                    anakan
                                                    ({{ $countJantan }} jantan,
                                                    {{ $countBetina }} betina)
                                                    </span>

                                                    @if ($countTotal > 0)
                                                        <button type="button"
                                                            onclick="toggleTrip('indukan-trip-{{ $perkawinan->id }}')"
                                                            class="text-xs text-primary hover:text-primary-dark ml-2">
                                                            👁️ Lihat Anak
                                                        </button>
                                                    @endif
                                                </div>

                                                {{-- LIST ANAK PER TRIP --}}
                                                <div id="indukan-trip-{{ $perkawinan->id }}"
                                                    class="hidden mt-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">

                                                    @foreach ($anakans as $child)
                                                        <div
                                                            class="border border-gray-200 dark:border-gray-700 rounded-lg p-3 bg-gray-50 dark:bg-gray-800 hover:shadow transition">
                                                            <div class="flex justify-between items-center mb-1">
                                                                <h4 class="text-xs font-semibold">
                                                                    {{ $child->nomor_ring ?? '(tanpa ring)' }}
                                                                </h4>
                                                                <span
                                                                    class="px-2 py-0.5 text-xs rounded-full
                                            {{ $child->jenis_kelamin === 'jantan'
                                                ? 'bg-blue-100 text-blue-800'
                                                : ($child->jenis_kelamin === 'betina'
                                                    ? 'bg-pink-100 text-pink-800'
                                                    : 'bg-gray-100 text-gray-800') }}">
                                                                    {{ ucfirst(str_replace('_', ' ', $child->jenis_kelamin)) }}
                                                                </span>
                                                            </div>

                                                            @if ($child->tanggal_lahir)
                                                                <p class="text-xs text-gray-500">
                                                                    {{ $child->tanggal_lahir->format('d M Y') }}
                                                                    ({{ $child->age['formatted'] }})
                                                                </p>
                                                            @endif

                                                            <a href="{{ route('peternak.anakan.show', $child) }}"
                                                                class="text-xs text-primary hover:text-primary-dark">
                                                                🔍 Detail
                                                            </a>
                                                        </div>
                                                    @endforeach

                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif


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

                    <!-- 🐦 Performa Indukan (Global) -->
<div class="bg-white dark:bg-darker rounded-xl shadow-lg p-6">

    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-5 flex items-center gap-2">
        🐦 Performa Indukan Secara Keseluruhan
    </h3>

    <div class="space-y-4 text-sm">

        <div class="flex justify-between items-center">
            <span class="text-gray-500 dark:text-gray-400">Total Trip</span>
            <span class="font-semibold text-gray-900 dark:text-white text-base">
                {{ $performa['total_trip'] }}
            </span>
        </div>

        <div class="flex justify-between items-center">
            <span class="text-gray-500 dark:text-gray-400">Berhasil</span>
            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                         bg-green-100 text-green-700 
                         dark:bg-green-900 dark:text-green-300">
                {{ $performa['berhasil'] }}
            </span>
        </div>

        <div class="flex justify-between items-center">
            <span class="text-gray-500 dark:text-gray-400">Gagal</span>
            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                         bg-red-100 text-red-700 
                         dark:bg-red-900 dark:text-red-300">
                {{ $performa['gagal'] }}
            </span>
        </div>

        <div class="flex justify-between items-center">
            <span class="text-gray-500 dark:text-gray-400">Total Anakan</span>
            <span class="font-semibold text-gray-900 dark:text-white text-base">
                {{ $performa['total_anakan'] }}
            </span>
        </div>

        <!-- Success Rate Highlight -->
        <div class="mt-6 p-4 rounded-lg 
            {{ $performa['success_rate'] >= 60 
                ? 'bg-green-50 dark:bg-green-900/30' 
                : ($performa['success_rate'] >= 40 
                    ? 'bg-yellow-50 dark:bg-yellow-900/30' 
                    : 'bg-red-50 dark:bg-red-900/30') }}">

            <div class="flex justify-between items-center">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    Success Rate
                </span>

                <span class="text-xl font-bold 
                    {{ $performa['success_rate'] >= 60 
                        ? 'text-green-600' 
                        : ($performa['success_rate'] >= 40 
                            ? 'text-yellow-600' 
                            : 'text-red-600') }}">
                    {{ $performa['success_rate'] }}%
                </span>
            </div>

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

                            <div class="flex text-sm">
                                <label for="file-upload-indukan"
                                    class="relative cursor-pointer
               bg-blue-600 hover:bg-blue-700
               dark:bg-blue-500 dark:hover:bg-blue-600
               text-white font-medium
               px-4 py-2
               rounded-md
               focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-500">
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
                        class="px-4 py-2 text-sm font-medium
           text-white
           bg-cyan-600 hover:bg-cyan-700
           dark:bg-cyan-500 dark:hover:bg-cyan-600
           rounded-md">
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

    <script>
        function togglePair(id, btn) {
            const el = document.getElementById(id);
            el.classList.toggle('hidden');

            btn.innerText = el.classList.contains('hidden') ?
                '👁️ Lihat Riwayat' :
                '🚫 Sembunyikan';
        }

        function toggleTrip(id) {
            const el = document.getElementById(id);
            el.classList.toggle('hidden');
        }
    </script>



</x-layout>
