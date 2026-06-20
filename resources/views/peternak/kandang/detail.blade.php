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

                            @if ($currentPairing)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Pairing</dt>
                                    <dd class="mt-1">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $currentPairing->status === 'aktif' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-slate-200 text-slate-800 dark:bg-slate-800 dark:text-slate-200' }}">
                                            {{ ucfirst($currentPairing->status) }}
                                        </span>
                                    </dd>
                                </div>
                            @endif
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
                            $perkawinanAktif = \App\Models\Perkawinan::where('indukan_jantan_id', $kandang->indukan_jantan_id)
                                ->where('indukan_betina_id', $kandang->indukan_betina_id)
                                ->with('anakans')
                                ->orderBy('tanggal_kawin')
                                ->get()
                                ->values();
                        @endphp

                        @if ($perkawinanAktif->count() > 0)
                            <div class="space-y-4 trip-pagination-container">
                                @foreach ($perkawinanAktif as $index => $perkawinan)
                                    @php
                                        $anakans = $perkawinan->anakans;
                                        $countTotal = $anakans->count();
                                        $countJantan = $anakans->where('jenis_kelamin', 'jantan')->count();
                                        $countBetina = $anakans->where('jenis_kelamin', 'betina')->count();
                                    @endphp

                                    <div class="relative pl-4 border-l-4 border-primary/70 trip-item">

                                        <div class="text-sm font-semibold flex flex-wrap items-center gap-1">
                                            <span class="inline-block w-2 h-2 bg-primary rounded-full mr-2"></span>

                                            Trip {{ $index + 1 }}
                                            @if ($perkawinan->status === 'gagal')
                                                <span class="ml-2 px-2 py-0.5 text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200 rounded-full">
                                                    GAGAL
                                                </span>
                                            @endif

                                            <span class="font-normal text-gray-600 dark:text-gray-300">
                                                — {{ $perkawinan->tanggal_kawin?->format('d M Y') ?? '-' }}
                                            </span>
                                            , total <span class="text-primary font-semibold">{{ $countTotal }}</span> anakan
                                            ({{ $countJantan }} jantan, {{ $countBetina }} betina)
                                            </span>

                                            @if ($countTotal > 0)
                                                <button type="button" onclick="toggleTrip('kandang-trip-{{ $perkawinan->id }}')"
                                                    class="text-xs text-primary hover:text-primary-dark ml-2">
                                                    👁️ Lihat Anak
                                                </button>
                                            @endif
                                        </div>

                                        @if ($perkawinan->status === 'gagal' && !empty($perkawinan->catatan))
                                            <div class="mt-1 text-xs text-red-600 dark:text-red-400 italic">
                                                *Catatan kegagalan: {{ $perkawinan->catatan }}
                                            </div>
                                        @endif

                                        {{-- LIST ANAK PER TRIP --}}
                                        <div id="kandang-trip-{{ $perkawinan->id }}"
                                            class="hidden mt-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">

                                            @foreach ($anakans as $child)
                                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-3 bg-gray-50 dark:bg-gray-800 hover:shadow transition">
                                                    <div class="flex justify-between items-center mb-1">
                                                        <h4 class="text-xs font-semibold">
                                                            {{ $child->nomor_ring ?? '(tanpa ring)' }}
                                                        </h4>
                                                        <span class="px-2 py-0.5 text-xs rounded-full {{ $child->jenis_kelamin === 'jantan' ? 'bg-blue-100 text-blue-800' : ($child->jenis_kelamin === 'betina' ? 'bg-pink-100 text-pink-800' : 'bg-gray-100 text-gray-800') }}">
                                                            {{ ucfirst(str_replace('_', ' ', $child->jenis_kelamin)) }}
                                                        </span>
                                                    </div>

                                                    @if ($child->tanggal_lahir)
                                                        <p class="text-xs text-gray-500">
                                                            {{ $child->tanggal_lahir->format('d M Y') }}
                                                            ({{ $child->age['formatted'] ?? '-' }})
                                                        </p>
                                                    @endif
                                                    
                                                    @if ($child->status_pertumbuhan)
                                                        <p class="text-xs text-gray-500 mb-2">
                                                            Status: {{ ucfirst(str_replace('_', ' ', $child->status_pertumbuhan)) }}
                                                        </p>
                                                    @endif

                                                    <a href="{{ route('peternak.anakan.show', $child) }}" class="text-xs text-primary hover:text-primary-dark">
                                                        🔍 Detail
                                                    </a>
                                                </div>
                                            @endforeach

                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <!-- Jika tidak ada anak atau trip -->
                            <div class="text-center py-10">
                                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                <h3 class="mt-3 text-sm font-medium text-gray-900 dark:text-white">Belum ada riwayat trip</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Belum ada riwayat trip atau anakan dari pasangan aktif di kandang ini.
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
        $bolehUbah = ($kandang->indukanJantan && $kandang->indukanBetina && count($validNextStatuses) > 0) ? true : false;
    @endphp

    @if(!$kandang->indukanJantan || !$kandang->indukanBetina)
        <div class="p-3 mb-3 text-sm rounded bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200">
            Status hanya dapat diubah jika terdapat pasangan <b>Indukan Jantan dan Betina</b> yang lengkap di kandang.
        </div>
    @elseif($currentPairing && $currentPairing->status === 'dihentikan')
        <div class="p-3 mb-3 text-sm rounded bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
            <p class="font-medium">Pairing ini sedang dihentikan.</p>
            <p class="mt-1">Trip breeding baru belum bisa dimulai sampai pairing diaktifkan kembali.</p>
        </div>

        <form action="{{ route('peternak.kandang.activatePairing', $kandang) }}" method="POST" class="mb-3">
            @csrf
            @method('PUT')
            <button type="submit"
                class="w-full px-3 py-2 text-sm text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg">
                Aktifkan Kembali Pairing
            </button>
        </form>

        <div class="p-3 mb-3 text-xs rounded bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200">
            Aktivasi ulang hanya mengubah status pairing saat ini. Tindak lanjut peternak pada hasil analisa lama tetap tersimpan sebagai histori.
        </div>
    @elseif(count($validNextStatuses) === 0)
        <div class="p-3 mb-3 text-sm rounded bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-200">
            Tidak ada transisi status lanjutan yang tersedia untuk status saat ini.
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

                <option value="{{ $kandang->status }}" selected>
                    Saat ini: {{ $statusLabels[$kandang->status] ?? ucfirst($kandang->status) }}
                </option>
                @foreach($validNextStatuses as $status)
                    <option value="{{ $status }}">{{ $statusLabels[$status] ?? ucfirst($status) }}</option>
                @endforeach
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
                <input type="text" id="tanggal_gagal" name="tanggal_gagal"
                       value="{{ old('tanggal_gagal', $kandang->perkawinans()->latest('tanggal_kawin')->first()?->tanggal_kawin?->toDateString() ?? now()->format('Y-m-d')) }}"
                       placeholder="dd/mm/yyyy"
                       autocomplete="off"
                       required
                       class="custom-datepicker mt-1 w-full px-3 py-2 border rounded-md dark:bg-darker dark:border-gray-600">
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
            window.toggleTrip = function(id) {
                const el = document.getElementById(id);
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


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const containers = document.querySelectorAll('.trip-pagination-container');
            containers.forEach(container => {
                const items = Array.from(container.querySelectorAll('.trip-item'));
                if (items.length <= 10) return; // No pagination needed

                let currentPage = 1;
                const perPage = 10;
                const totalPages = Math.ceil(items.length / perPage);

                // Create pagination controls
                const controls = document.createElement('div');
                controls.className = 'flex justify-between items-center mt-4 pt-4 border-t border-gray-200 dark:border-gray-700';
                
                const prevBtn = document.createElement('button');
                prevBtn.type = 'button';
                prevBtn.className = 'px-3 py-1 text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition';
                prevBtn.innerText = '← Sebelumnya';

                const info = document.createElement('span');
                info.className = 'text-xs text-gray-500 font-medium';

                const nextBtn = document.createElement('button');
                nextBtn.type = 'button';
                nextBtn.className = 'px-3 py-1 text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition';
                nextBtn.innerText = 'Selanjutnya →';

                controls.appendChild(prevBtn);
                controls.appendChild(info);
                controls.appendChild(nextBtn);
                container.appendChild(controls);

                function render() {
                    items.forEach((item, index) => {
                        if (index >= (currentPage - 1) * perPage && index < currentPage * perPage) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                    prevBtn.disabled = currentPage === 1;
                    nextBtn.disabled = currentPage === totalPages;
                    info.innerText = `Halaman ${currentPage} dari ${totalPages}`;
                }

                prevBtn.addEventListener('click', () => {
                    if (currentPage > 1) {
                        currentPage--;
                        render();
                    }
                });

                nextBtn.addEventListener('click', () => {
                    if (currentPage < totalPages) {
                        currentPage++;
                        render();
                    }
                });

                render(); // initial render
            });
        });
    </script>

</x-layout>
