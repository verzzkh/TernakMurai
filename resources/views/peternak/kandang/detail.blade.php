<x-layout>
    <main>
        <!-- Content header -->
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <div>
                <h1 class="text-2xl font-semibold">{{ $kandang->nomor_kandang }}</h1>
                <p class="text-gray-600 dark:text-gray-400">Kandang</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('peternak.kandang.edit', $kandang) }}"
                    class="px-4 py-2 text-sm font-medium
          text-indigo-700 bg-indigo-300
          hover:bg-indigo-400
          dark:text-indigo-100 dark:bg-indigo-700 
          dark:hover:bg-indigo-600
          rounded-md focus:outline-none">
                    Edit
                </a>

                <a href="{{ route('peternak.kandang.index') }}"
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
                <!-- Informasi Kandang -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-darker rounded-lg shadow p-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Kandang</h2>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Kandang</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $kandang->nomor_kandang }}
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                <dd class="mt-1">
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full {{ $kandang->status === 'kosong'
                                            ? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
                                            : ($kandang->status === 'bertelur'
                                                ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
                                                : ($kandang->status === 'mengeram'
                                                    ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200'
                                                    : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200')) }}">
                                        {{ ucfirst($kandang->status) }}
                                    </span>
                                </dd>
                            </div>

                            @if ($kandang->indukanJantan)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Indukan Jantan</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                        <a href="{{ route('peternak.indukan.show', $kandang->indukanJantan) }}"
                                            class="text-primary hover:text-primary-dark">
                                            {{ $kandang->indukanJantan->nomor_ring }}
                                            @if ($kandang->indukanJantan->nama)
                                                - {{ $kandang->indukanJantan->nama }}
                                            @endif
                                        </a>
                                    </dd>
                                </div>
                            @endif

                            @if ($kandang->indukanBetina)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Indukan Betina</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                        <a href="{{ route('peternak.indukan.show', $kandang->indukanBetina) }}"
                                            class="text-primary hover:text-primary-dark">
                                            {{ $kandang->indukanBetina->nomor_ring }}
                                            @if ($kandang->indukanBetina->nama)
                                                - {{ $kandang->indukanBetina->nama }}
                                            @endif
                                        </a>
                                    </dd>
                                </div>
                            @endif

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Anakan</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $kandang->anakans->count() }}
                                    anakan</dd>
                            </div>
                        </div>

                        @if ($kandang->deskripsi_kandang)
                            <div class="mt-6">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Deskripsi</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $kandang->deskripsi_kandang }}</dd>
                            </div>
                        @endif
                    </div>


                    <!-- 🐣 Anakan dari Pasangan Aktif -->
                    <div class="mt-6 bg-white dark:bg-darker rounded-lg shadow p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Anakan dari Pasangan Aktif
                                <span class="text-primary font-bold">
                                    {{ $kandang->indukanJantan
                                        ? $kandang->indukanJantan->nomor_ring . ($kandang->indukanJantan->nama ? '  ' . $kandang->indukanJantan->nama : '')
                                        : '-' }}
                                    ×
                                    {{ $kandang->indukanBetina
                                        ? $kandang->indukanBetina->nomor_ring . ($kandang->indukanBetina->nama ? '  ' . $kandang->indukanBetina->nama : '')
                                        : '-' }}
                                </span>

                            </h2>
                        </div>

                        @php
                            // Ambil semua perkawinan pasangan aktif, urut dari trip pertama ke terakhir
                            $perkawinanAktif = $kandang
                                ->perkawinans()
                                ->where('indukan_jantan_id', $kandang->indukan_jantan_id)
                                ->where('indukan_betina_id', $kandang->indukan_betina_id)
                                ->orderBy('tanggal_kawin')
                                ->get()
                                ->values();

                            // Ambil semua anakan pasangan aktif beserta relasinya
                            $anakansAktif = $kandang
                                ->anakans()
                                ->whereHas('perkawinan', function ($q) use ($kandang) {
                                    $q->where('indukan_jantan_id', $kandang->indukan_jantan_id)->where(
                                        'indukan_betina_id',
                                        $kandang->indukan_betina_id,
                                    );
                                })
                                ->with('perkawinan')
                                ->get();

                            // Urutkan anakan berdasarkan nomor trip (bukan id atau tanggal lahir)
                            $anakansAktif = $anakansAktif
                                ->sortBy(function ($anakan) use ($perkawinanAktif) {
                                    $tripIndex = $perkawinanAktif->search(fn($p) => $p->id === $anakan->perkawinan_id);
                                    return $tripIndex !== false ? $tripIndex : 9999; // trip tak dikenal taruh paling bawah
                                })
                                ->values();
                        @endphp


                        @if ($anakansAktif->count() > 0)
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach ($anakansAktif as $anakan)
                                    @php
                                        // cari trip keberapa anakan ini termasuk
                                        $tripIndex =
                                            $perkawinanAktif->search(function ($p) use ($anakan) {
                                                return $p->id === $anakan->perkawinan_id;
                                            }) + 1; // +1 biar mulai dari 1, bukan 0
                                    @endphp

                                    <div
                                        class="border border-gray-200 dark:border-primary-darker rounded-lg p-4 hover:shadow-md transition">
                                        <div class="flex items-center justify-between mb-1">
                                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                                                🪶 {{ $anakan->nomor_ring ?? '(tanpa ring)' }}
                                            </h3>
                                            <span
                                                class="px-2 py-1 text-xs font-medium rounded-full
                            {{ $anakan->jenis_kelamin === 'jantan'
                                ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200'
                                : ($anakan->jenis_kelamin === 'betina'
                                    ? 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200'
                                    : 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200') }}">
                                                {{ ucfirst(str_replace('_', ' ', $anakan->jenis_kelamin)) }}
                                            </span>
                                        </div>

                                        <!-- Info Trip -->
                                        @if ($anakan->perkawinan)
                                            <p class="text-xs text-gray-600 dark:text-gray-300">
                                                Trip ke-{{ $tripIndex }} —
                                                {{ $anakan->perkawinan?->tanggal_kawin?->format('d M Y') ?? '-' }}
                                            </p>
                                        @endif

                                        <!-- Info tanggal lahir -->
                                        @if ($anakan->tanggal_lahir)
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $anakan->tanggal_lahir->format('d M Y') }}
                                                ({{ $anakan->age['formatted'] ?? '-' }})
                                            </p>
                                        @endif

                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                                            Status: {{ ucfirst(str_replace('_', ' ', $anakan->status_pertumbuhan)) }}
                                        </p>

                                        <div class="mt-3 flex justify-between items-center">
                                            <a href="{{ route('peternak.anakan.show', $anakan) }}"
                                                class="text-xs text-primary hover:text-primary-dark flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Lihat Detail
                                            </a>
                                        </div>

                                        <!-- Anak lain di trip yang sama -->
                                        <div id="trip-children-{{ $anakan->perkawinan_id }}"
                                            class="hidden mt-3 border-t pt-2 space-y-1">
                                            @forelse ($anakan->perkawinan->anakans ?? [] as $child)
                                                <a href="{{ route('peternak.anakan.show', $child) }}"
                                                    class="block text-xs text-gray-800 dark:text-gray-300 hover:text-primary">
                                                    • {{ $child->nomor_ring ?? '(tanpa ring)' }}
                                                </a>
                                            @empty
                                                <p class="text-xs text-gray-500 italic">Belum ada anakan di trip ini.
                                                </p>
                                            @endforelse
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <!-- Jika tidak ada anak -->
                            <div class="text-center py-10">
                                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                <h3 class="mt-3 text-sm font-medium text-gray-900 dark:text-white">Belum ada anakan</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Belum ada anakan dari pasangan aktif di kandang ini.
                                </p>
                            </div>
                        @endif
                    </div>


                   
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Statistik -->
                    <div class="bg-white dark:bg-darker rounded-lg shadow p-6">
    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
        🐦 Performa Pasangan Aktif
    </h3>

    @if($kandang->indukanJantan && $kandang->indukanBetina)

        <div class="space-y-4 text-sm">

            <div class="flex justify-between">
                <span>Total Trip</span>
                <span class="font-semibold">{{ $performa['total_trip'] }}</span>
            </div>

            <div class="flex justify-between">
                <span>Berhasil</span>
                <span class="text-green-600 font-semibold">
                    {{ $performa['berhasil'] }}
                </span>
            </div>

            <div class="flex justify-between">
                <span>Gagal</span>
                <span class="text-red-600 font-semibold">
                    {{ $performa['gagal'] }}
                </span>
            </div>

            <div class="flex justify-between">
                <span>Success Rate</span>
                <span class="font-semibold">
                    {{ $performa['success_rate'] }}%
                </span>
            </div>

            <div class="flex justify-between">
                <span>Rata-rata Anakan</span>
                <span class="font-semibold">
                    {{ $performa['rata_anakan'] }} / trip
                </span>
            </div>

        </div>

    @else
        <p class="text-sm text-gray-500">
            Belum ada pasangan aktif.
        </p>
    @endif
</div>


                    <!-- Status Actions -->
                    <div class="mt-6 bg-white dark:bg-darker rounded-lg shadow p-6">
    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Ubah Status</h3>

    @php
        $bolehUbah = $kandang->indukanBetina ? true : false; // hanya boleh jika ada betina
    @endphp

    @if(!$bolehUbah)
        <div class="p-3 mb-3 text-sm rounded bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200">
            Status hanya dapat diubah jika terdapat <b>Indukan Betina</b> di kandang.
        </div>
    @endif

    <form  id="statusForm" action="{{ route('peternak.kandang.update', $kandang) }}" method="POST" class="space-y-3">
        @csrf
        @method('PUT')

        <div>
            <select name="status"
                @if(!$bolehUbah) disabled @endif
                class="block w-full px-3 py-2 border border-gray-300 rounded-md bg-white dark:bg-darker 
                       dark:border-primary-darker dark:text-white focus:outline-none">

                <option value="kosong" {{ $kandang->status === 'kosong' ? 'selected' : '' }}>Kosong</option>
                <option value="bertelur" {{ $kandang->status === 'bertelur' ? 'selected' : '' }}>Bertelur</option>
                <option value="mengeram" {{ $kandang->status === 'mengeram' ? 'selected' : '' }}>Mengeram</option>
                <option value="menetas">Menetas</option>
                <option value="gagal">Gagal</option>
            </select>
        </div>

        <button type="submit"
            @if(!$bolehUbah) disabled @endif
            class="w-full px-3 py-2 text-sm text-white bg-cyan-700 hover:bg-cyan-800 rounded-lg 
                  disabled:bg-gray-400 disabled:cursor-not-allowed">
            Update Status
        </button>
    </form>

</div>

                </div>
            </div>
        </div>

        <!-- Modal Konfirmasi Gagal -->
<!-- Modal Konfirmasi Gagal -->
<div id="gagalModal"
     class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">

    <div class="bg-white dark:bg-darker rounded-lg w-full max-w-md mx-4 p-6 shadow-lg">

        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">
            Breeding Dinyatakan Gagal
        </h3>

        <form id="gagalForm"
      method="POST"
      action="{{ route('peternak.kandang.storeGagal', $kandang->id) }}">
            @csrf

            <!-- Tanggal Gagal -->
            <div class="mb-4">
                <label class="text-sm text-gray-700 dark:text-gray-300">
                    Tanggal Gagal <span class="text-red-500">*</span>
                </label>
                <input type="date" name="tanggal_gagal" required
                       class="mt-1 w-full px-3 py-2 border rounded-md dark:bg-darker dark:border-gray-600">
            </div>

            <!-- Catatan -->
            <div class="mb-4">
                <label class="text-sm text-gray-700 dark:text-gray-300">
                    Catatan (Opsional)
                </label>
                <textarea name="catatan"
                          class="mt-1 w-full px-3 py-2 border rounded-md dark:bg-darker dark:border-gray-600"
                          rows="2"></textarea>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" id="cancelGagal"
                        class="px-4 py-2 text-sm bg-gray-200 rounded-md">
                    Batal
                </button>

                <button type="submit"
                        class="px-4 py-2 text-sm text-white bg-red-600 rounded-md">
                    Simpan Gagal
                </button>
            </div>
        </form>

    </div>
</div>


    </main>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // ===================================================
            // 🐣 LOGIKA JUMLAH ANAKAN (PUNYA KAMU)
            // ===================================================
            const jumlahAnakanSelect = document.getElementById('jumlah_anakan');
            const jenisSingle = document.getElementById('jenis-kelamin-single');
            const multipleForm = document.getElementById('multiple-anakan-form');

            if (jumlahAnakanSelect) {
                jumlahAnakanSelect.addEventListener('change', function() {
                    const count = parseInt(this.value);
                    const singlePhotoSection = document.querySelector('[id="foto_anakan"]').closest(
                        '.mt-4');

                    if (count > 1) {
                        singlePhotoSection.classList.add('hidden');
                        jenisSingle.classList.add('hidden');
                        multipleForm.classList.remove('hidden');
                        setDisabled(jenisSingle, true);
                        multipleForm.innerHTML = '';

                        for (let i = 1; i <= count; i++) {
                            const anakanItem = document.createElement('div');
                            anakanItem.className = 'p-4 border rounded-md dark:border-primary-darker';
                            anakanItem.innerHTML = `
                        <h3 class="font-medium text-gray-800 dark:text-gray-200 mb-3">Anakan #${i}</h3>
                        <div class="mb-3">
                            <label class="text-gray-700 dark:text-gray-200">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <div class="mt-2 flex space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="jenis_kelamin[${i}]" value="jantan" class="form-radio text-primary" checked>
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">Jantan</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="jenis_kelamin[${i}]" value="betina" class="form-radio text-primary">
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">Betina</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="jenis_kelamin[${i}]" value="tidak_diketahui" class="form-radio text-primary">
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">Belum Tahu</span>
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="text-gray-700 dark:text-gray-200">Foto Anakan</label>
                            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md dark:border-gray-600">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                              stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="foto_anakan_${i}" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-primary-dark focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary">
                                            <span class="px-2 py-1 dark:bg-darker dark:text-gray-300">Pilih foto</span>
                                            <input id="foto_anakan_${i}" name="foto_anakan[${i}]" type="file" class="sr-only" accept="image/*">
                                        </label>
                                        <p class="pl-1 dark:text-gray-400">atau seret dan lepas</p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, JPEG hingga 5MB</p>
                                </div>
                            </div>
                        </div>
                    `;
                            multipleForm.appendChild(anakanItem);
                        }
                        setDisabled(multipleForm, false);
                    } else {
                        singlePhotoSection.classList.remove('hidden');
                        jenisSingle.classList.remove('hidden');
                        multipleForm.classList.add('hidden');
                        setDisabled(jenisSingle, false);
                        setDisabled(multipleForm, true);
                    }
                });

                function setDisabled(container, disabled) {
                    if (!container) return;
                    const controls = container.querySelectorAll('input, select, textarea, button');
                    controls.forEach(control => {
                        control.disabled = disabled;
                    });
                }
            }

            function toggleTripChildren(tripId) {
                const el = document.getElementById(`trip-children-${tripId}`);
                if (!el) return;
                el.classList.toggle('hidden');
            }

            // ===================================================
            // 👁️ LOGIKA TOGGLE PASANGAN (BARU)
            // ===================================================
            document.querySelectorAll('.toggle-pair').forEach(button => {
                button.addEventListener('click', () => {
                    const targetId = button.dataset.target;
                    const section = document.getElementById(targetId);
                    if (!section) return;

                    if (section.style.display === 'none') {
                        section.style.display = '';
                        button.textContent = '🚫 Sembunyikan';
                    } else {
                        section.style.display = 'none';
                        button.textContent = '👁️ Tampilkan';
                    }
                });
            });

            // ===================================================
            // 👶 Toggle tampilan daftar anak per trip
            // ===================================================
            window.toggleTrip = function(tripId) {
                const el = document.getElementById(`trip-${tripId}`);
                if (!el) return;
                el.classList.toggle('hidden');
            };
        });
    </script>
    <script>
function togglePair(id, btn) {
    const el = document.getElementById(id);

    if (!el) return;

    el.classList.toggle('hidden');

    if (el.classList.contains('hidden')) {
        btn.innerText = '👁️ Lihat Riwayat';
    } else {
        btn.innerText = '🚫 Sembunyikan';
    }
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const statusForm = document.getElementById('statusForm');
    const statusSelect = statusForm.querySelector('select[name="status"]');
    const modal = document.getElementById('gagalModal');
    const cancelBtn = document.getElementById('cancelGagal');

    statusForm.addEventListener('submit', function (e) {

        if (statusSelect.value === 'gagal') {
            e.preventDefault(); // hentikan submit update
            modal.classList.remove('hidden'); // tampilkan modal
        }

    });

    cancelBtn.addEventListener('click', function () {
        modal.classList.add('hidden');
    });

});
</script>


</x-layout>
