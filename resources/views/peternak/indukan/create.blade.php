<x-layout>
    <main>
        <!-- Content header -->
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <h1 class="text-2xl font-semibold">Tambah Indukan Baru</h1>
            <a href="{{ route('peternak.indukan.index') }}"
                class="px-4 py-2 text-sm font-medium
          text-white bg-indigo-600
          hover:bg-indigo-700
          dark:bg-indigo-500 dark:hover:bg-indigo-600
          rounded-md focus:outline-none">
                Kembali
            </a>

        </div>

        <!-- Form -->
        <div class="px-4 py-6">
            <div class="max-w-2xl mx-auto">
                <form action="{{ route('peternak.indukan.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf

                    <div class="bg-white dark:bg-darker rounded-lg shadow p-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Dasar</h2>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Nomor Ring -->
                            <div class="sm:col-span-2">
                                <label for="nomor_ring"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Nomor Ring <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nomor_ring" id="nomor_ring" value="{{ old('nomor_ring') }}"
                                    required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-darker dark:border-primary-darker dark:text-white sm:text-sm @error('nomor_ring') border-red-300 @enderror">
                                @error('nomor_ring')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nama -->
                            <div class="sm:col-span-2">
                                <label for="nama"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Nama (Opsional)
                                </label>
                                <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-darker dark:border-primary-darker dark:text-white sm:text-sm @error('nama') border-red-300 @enderror">
                                @error('nama')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jenis Kelamin -->
                            <div>
                                <label for="jenis_kelamin"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Jenis Kelamin <span class="text-red-500">*</span>
                                </label>
                                <select name="jenis_kelamin" id="jenis_kelamin" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-darker dark:border-primary-darker dark:text-white sm:text-sm @error('jenis_kelamin') border-red-300 @enderror">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="jantan" {{ old('jenis_kelamin') === 'jantan' ? 'selected' : '' }}>
                                        Jantan</option>
                                    <option value="betina" {{ old('jenis_kelamin') === 'betina' ? 'selected' : '' }}>
                                        Betina</option>
                                </select>
                                @error('jenis_kelamin')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Lahir -->
                            <div>
                                <label for="tanggal_lahir"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Tanggal Lahir
                                </label>
                                <input type="text" name="tanggal_lahir" id="tanggal_lahir"
                                    value="{{ old('tanggal_lahir') }}"
                                    placeholder="dd/mm/yyyy"
                                    autocomplete="off"
                                    class="custom-datepicker mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-darker dark:border-primary-darker dark:text-white sm:text-sm @error('tanggal_lahir') border-red-300 @enderror">
                                @error('tanggal_lahir')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mt-4">
                                <label for="foto_indukan" class="text-gray-700 dark:text-gray-200">Foto Indukan</label>
                                <div
                                    class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md dark:border-gray-600">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor"
                                            fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>

                                        <div class="flex text-sm text-gray-600">
                                            <label for="foto_indukan"
                                                class="cursor-pointer bg-white rounded-md font-medium text-primary">
                                                <span class="px-2 py-1 dark:bg-darker dark:text-gray-300">Pilih
                                                    foto</span>
                                                <input id="foto_indukan" name="foto_indukan" type="file"
                                                    class="sr-only" accept="image/*">
                                            </label>
                                            <p class="pl-1 dark:text-gray-400">atau seret dan lepas</p>
                                        </div>

                                        <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, JPEG maksimal 5MB
                                        </p>
                                    </div>
                                </div>




                                @error('foto_indukan')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>


                    <!-- Informasi Tambahan -->
                    <div class="bg-white dark:bg-darker rounded-lg shadow p-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Tambahan</h2>

                        <div class="space-y-6">
                            <!-- Catatan -->
                            <div>
                                <label for="catatan"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Catatan
                                </label>
                                <textarea name="catatan" id="catatan" rows="3"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-darker dark:border-primary-darker dark:text-white sm:text-sm @error('catatan') border-red-300 @enderror">{{ old('catatan') }}</textarea>
                                @error('catatan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Prestasi -->
                            <div>
                                <label for="prestasi"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Prestasi
                                </label>
                                <textarea name="prestasi" id="prestasi" rows="3"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-darker dark:border-primary-darker dark:text-white sm:text-sm @error('prestasi') border-red-300 @enderror">{{ old('prestasi') }}</textarea>
                                @error('prestasi')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Karakteristik -->
                            <div>
                                <label for="karakteristik"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Karakteristik
                                </label>
                                <textarea name="karakteristik" id="karakteristik" rows="3"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-darker dark:border-primary-darker dark:text-white sm:text-sm @error('karakteristik') border-red-300 @enderror">{{ old('karakteristik') }}</textarea>
                                @error('karakteristik')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!-- PERILAKU BREEDING -->
<div class="bg-white dark:bg-darker rounded-lg shadow p-6">
    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Perilaku Breeding</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        <!-- JANTAN -->
        <div id="breeding-jantan" class="{{ old('jenis_kelamin')=='jantan' ? '' : 'hidden' }}">
            <label class="block mb-2">
                <input type="checkbox" name="aktif_kicau" value="1" {{ old('aktif_kicau')?'checked':'' }}>
                Aktif Kicau
            </label>

            <label class="block">
                <input type="checkbox" name="mendekati_betina" value="1" {{ old('mendekati_betina')?'checked':'' }}>
                Mendekati Betina
            </label>
        </div>

        <!-- BETINA -->
        <div id="breeding-betina" class="{{ old('jenis_kelamin')=='betina' ? '' : 'hidden' }}">
            <label class="block mb-2">
                <input type="checkbox" name="nafsu_makan_meningkat" value="1" {{ old('nafsu_makan_meningkat')?'checked':'' }}>
                Nafsu Makan Meningkat
            </label>

            <label class="block">
                <input type="checkbox" name="aktif_buat_sarang" value="1" {{ old('aktif_buat_sarang')?'checked':'' }}>
                Aktif Membuat Sarang
            </label>
        </div>

        <div>
            <label class="block text-sm font-medium">Temperamen</label>
            <select name="temperamen"
                class="mt-1 block w-full px-2 py-1 border rounded dark:bg-darker dark:text-white">
                <option value="">-</option>
                <option value="jinak">Jinak</option>
                <option value="sedang">Sedang</option>
                <option value="fighter">Fighter</option>
            </select>
        </div>

    </div>
</div>



                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('peternak.indukan.index') }}"
                            class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring focus:ring-gray-500 focus:ring-offset-1">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-4 py-2 text-white bg-cyan-600
           hover:bg-cyan-700
           dark:bg-cyan-500 dark:hover:bg-cyan-600
           rounded-md focus:outline-none focus:ring focus:ring-cyan-400 focus:ring-offset-1">
                            Simpan Indukan
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </main>
    <script>
document.getElementById('jenis_kelamin').addEventListener('change', function(){
    let jk = this.value;

    document.getElementById('breeding-jantan').classList.add('hidden');
    document.getElementById('breeding-betina').classList.add('hidden');

    if(jk === 'jantan'){
        document.getElementById('breeding-jantan').classList.remove('hidden');
    }else if(jk === 'betina'){
        document.getElementById('breeding-betina').classList.remove('hidden');
    }
});
</script>

</x-layout>
