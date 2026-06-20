<x-layout>
    <main>
        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                    Tambah Anakan dari {{ $kandang->nomor_kandang }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400">Kandang {{ $kandang->nomor_kandang }}</p>
            </div>
            <div>
              <a href="{{ route('peternak.kandang.show', $kandang) }}"
   class="px-4 py-2 text-sm font-medium
          text-white bg-indigo-600
          hover:bg-indigo-700
          dark:bg-indigo-500 dark:hover:bg-indigo-600
          rounded-md focus:outline-none">
    Kembali
</a>

            </div>
        </div>

        <!-- Main Content -->
        <div class="px-4 py-6">
            <div class="max-w-4xl mx-auto bg-white dark:bg-darker rounded-lg shadow p-6">
                <h2 class="text-xl font-medium text-gray-900 dark:text-white mb-4">Form Tambah Anakan</h2>

                <form action="{{ route('peternak.kandang.anakan.store', $kandang->id) }}" method="POST"
                    enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <input type="hidden" name="kandang_id" value="{{ $kandang->id }}">
                    <input type="hidden" name="indukan_jantan_id" value="{{ $kandang->indukan_jantan_id }}">
                    <input type="hidden" name="indukan_betina_id" value="{{ $kandang->indukan_betina_id }}">
                    <input type="hidden" name="sumber_anakan" value="internal">

                    <!-- Informasi indukan -->
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Indukan Jantan</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $kandang->indukanJantan->nomor_ring ?? '-' }}
                                @if ($kandang->indukanJantan?->nama)
                                    - {{ $kandang->indukanJantan->nama }}
                                @endif
                            </p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Indukan Betina</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $kandang->indukanBetina->nomor_ring ?? '-' }}
                                @if ($kandang->indukanBetina?->nama)
                                    - {{ $kandang->indukanBetina->nama }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Tanggal menetas -->
                    <div>
                        <label for="tanggal_lahir" class="text-gray-700 dark:text-gray-200">Tanggal Menetas <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="tanggal_lahir" name="tanggal_lahir" required
                            value="{{ old('tanggal_lahir') }}"
                            placeholder="dd/mm/yyyy"
                            autocomplete="off"
                            class="custom-datepicker block w-full px-4 py-2 mt-2 border border-gray-300 rounded-md bg-white dark:bg-darker dark:text-gray-300 dark:border-gray-600 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-40">
                        @error('tanggal_lahir')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jumlah anakan -->
                    <div>
                        <label for="jumlah_anakan" class="text-gray-700 dark:text-gray-200">Jumlah Anakan <span
                                class="text-red-500">*</span></label>
                        <select id="jumlah_anakan" name="jumlah_anakan" required
                            class="block w-full px-4 py-2 mt-2 border border-gray-300 rounded-md bg-white dark:bg-darker dark:text-gray-300 dark:border-gray-600 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-40">
                            <option value="1">1 ekor</option>
                            <option value="2">2 ekor</option>
                            <option value="3">3 ekor</option>
                            <option value="4">4 ekor</option>
                            <option value="5">5 ekor</option>
                        </select>
                    </div>
                    <div class="mt-6">
                        <label for="nomor_ring" class="text-gray-700 dark:text-gray-200">Nomor Ring (Opsional)</label>
                        <input type="text" id="nomor_ring" name="nomor_ring"
                            class="block w-full px-4 py-2 mt-2 border border-gray-300 rounded-md bg-white dark:bg-darker dark:text-gray-300 dark:border-gray-600 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-40"
                            placeholder="Contoh: MB-J-2025-001">
                    </div>

                    <!-- Jenis kelamin tunggal -->
                    <div id="jenis-kelamin-single" class="mt-4">
                        <label class="text-gray-700 dark:text-gray-200">Jenis Kelamin <span
                                class="text-red-500">*</span></label>
                        <div class="mt-2 flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="jenis_kelamin" value="jantan"
                                    class="form-radio text-primary" checked>
                                <span class="ml-2 text-gray-700 dark:text-gray-300">Jantan</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="jenis_kelamin" value="betina"
                                    class="form-radio text-primary">
                                <span class="ml-2 text-gray-700 dark:text-gray-300">Betina</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="jenis_kelamin" value="tidak_diketahui"
                                    class="form-radio text-primary">
                                <span class="ml-2 text-gray-700 dark:text-gray-300">Belum Tahu</span>
                            </label>
                        </div>
                    </div>

                    <!-- Multiple anakan form -->
                    <div id="multiple-anakan-form" class="hidden space-y-4"></div>

                    <!-- Foto -->
                    <div class="mt-4">
                        <label for="foto_anakan" class="text-gray-700 dark:text-gray-200">Foto Anakan</label>
                        <div
                            class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md dark:border-gray-600">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="foto_anakan"
                                        class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-primary-dark">
                                        <span>Pilih file</span>
                                        <input id="foto_anakan" name="foto_anakan" type="file" class="sr-only"
                                            accept="image/*">
                                    </label>
                                    <p class="pl-1 dark:text-gray-400">atau seret ke sini</p>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, JPEG hingga 5MB</p>
                                <div id="foto-preview" class="hidden">
                                    <img id="preview-image" src="#" alt="Preview"
                                        class="h-40 mx-auto rounded-md object-cover">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Karakteristik -->
                    <div class="mt-6">
                        <label for="deskripsi_karakteristik" class="text-gray-700 dark:text-gray-200">Karakter
                            Burung</label>
                        <textarea id="deskripsi_karakteristik" name="deskripsi_karakteristik" rows="3"
                            class="block w-full px-4 py-2 mt-2 border border-gray-300 rounded-md bg-white dark:bg-darker dark:text-gray-300 dark:border-gray-600 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-40"
                            placeholder="Contoh: lincah, rajin bunyi, aktif makan...">{{ old('deskripsi_karakteristik') }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
    class="px-6 py-2 
           text-white 
           bg-cyan-600 rounded-md 
           hover:bg-cyan-700 
           focus:outline-none focus:ring focus:ring-cyan-600 focus:ring-offset-1
           dark:bg-cyan-500 dark:hover:bg-cyan-600">
    Simpan Anakan
</button>

                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- ✅ Perbaikan Script -->
    <script>
    const jumlahAnakanSelect = document.getElementById('jumlah_anakan');
    const jenisSingle = document.getElementById('jenis-kelamin-single');
    const multipleForm = document.getElementById('multiple-anakan-form');
    const singlePhotoSection = document.querySelector('#foto_anakan')?.closest('.mt-4');
    const nomorRingSingle = document.getElementById('nomor_ring')?.closest('.mt-6');

    jumlahAnakanSelect.addEventListener('change', function () {
        const count = parseInt(this.value) || 1;

        // 🧹 Bersihkan dulu semua isi multiple form agar tidak duplikat
        multipleForm.innerHTML = '';

        if (count > 1) {
            // Sembunyikan field tunggal (nomor ring, kelamin, foto)
            singlePhotoSection?.classList.add('hidden');
            nomorRingSingle?.classList.add('hidden');
            jenisSingle.classList.add('hidden');
            multipleForm.classList.remove('hidden');

            setDisabled(jenisSingle, true);
            setDisabled(singlePhotoSection, true);
            setDisabled(nomorRingSingle, true);

            // 🔁 Buat ulang field sesuai jumlah_anakan
            for (let i = 1; i <= count; i++) {
                const anakanItem = document.createElement('div');
                anakanItem.className =
                    'p-4 border rounded-lg shadow-sm bg-gray-50 dark:bg-dark/40 dark:border-primary-darker transition hover:shadow-md';

                anakanItem.innerHTML = `
                    <h3 class="font-medium text-gray-800 dark:text-gray-200 mb-3">Anakan #${i}</h3>

                    <div class="mb-3">
                        <label class="text-gray-700 dark:text-gray-200">Nomor Ring (Opsional)</label>
                        <input type="text" name="nomor_ring[${i}]" placeholder="Contoh: MB-J-2025-${i.toString().padStart(3, '0')}"
                            class="block w-full mt-2 border-gray-300 rounded-md dark:bg-darker dark:text-gray-300 dark:border-gray-600">
                    </div>

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
                        <label class="text-gray-700 dark:text-gray-200">Foto Anakan #${i}</label>
                        <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-gray-300 rounded-md dark:border-gray-600 hover:border-primary transition">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center">
                                    <label for="foto_anakan_${i}" class="relative cursor-pointer bg-white dark:bg-darker rounded-md font-medium text-primary hover:text-primary-dark focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary">
                                        <span class="px-2 py-1">Pilih foto</span>
                                        <input id="foto_anakan_${i}" name="foto_anakan[${i}]" type="file" class="sr-only" accept="image/*">
                                    </label>
                                    <p class="pl-1">atau seret ke sini</p>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, JPEG hingga 5MB</p>

                                <div id="preview-wrapper-${i}" class="hidden mt-3">
                                    <img id="preview_${i}" class="h-32 w-auto mx-auto rounded-md shadow object-cover border border-gray-300 dark:border-gray-600">
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                multipleForm.appendChild(anakanItem);
            }

        } else {
            // Kembali ke mode tunggal
            singlePhotoSection?.classList.remove('hidden');
            nomorRingSingle?.classList.remove('hidden');
            jenisSingle.classList.remove('hidden');
            multipleForm.classList.add('hidden');

            setDisabled(jenisSingle, false);
            setDisabled(singlePhotoSection, false);
            setDisabled(nomorRingSingle, false);
        }
    });

    // ✅ Preview foto dinamis
    document.addEventListener('change', function (e) {
        if (e.target.matches('input[type="file"][id^="foto_anakan_"]')) {
            const input = e.target;
            const index = input.id.split('_')[2];
            const preview = document.getElementById(`preview_${index}`);
            const wrapper = document.getElementById(`preview-wrapper-${index}`);

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (ev) {
                    preview.src = ev.target.result;
                    wrapper.classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    });

    function setDisabled(container, disabled) {
        if (!container) return;
        const controls = container.querySelectorAll('input, select, textarea, button');
        controls.forEach(c => (c.disabled = disabled));
    }

    // Inisialisasi awal (1 ekor default)
    document.addEventListener('DOMContentLoaded', () => {
        jumlahAnakanSelect.dispatchEvent(new Event('change'));
    });
</script>


</x-layout>
