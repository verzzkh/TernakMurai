<x-layout>
    <main>
        <!-- Content header -->
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <div class="flex items-center">
                <a href="/kandang" class="mr-4">
                    <button class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-primary focus:outline-none focus:ring focus:ring-primary-lighter">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-primary-light" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                </a>
                <h1 class="text-2xl font-semibold">Tambah Kandang Baru</h1>
            </div>
        </div>

        <!-- Content -->
        <div class="mt-6 px-4 py-4">
            <div class="max-w-3xl mx-auto bg-white dark:bg-darker rounded-md shadow-md overflow-hidden">
                <form action="/kandang/store" method="POST" enctype="multipart/form-data">
                    <!-- @csrf -->
                    
                    <div class="p-6 space-y-6">
                        <h2 class="text-xl font-medium border-b pb-3 dark:border-primary-darker">Informasi Kandang</h2>
                        
                        <!-- Nomor Kandang Preview -->
                        <div class="bg-gray-50 dark:bg-darker-2 p-4 rounded-md border border-gray-200 dark:border-gray-700">
                            <p class="text-gray-700 dark:text-gray-300 text-md">
                                <span class="font-medium">Nomor Kandang:</span> 
                                <span id="nomor-kandang-preview" class="text-primary dark:text-primary-light font-semibold">Kandang #005</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">(Otomatis)</span>
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Nomor kandang akan diberikan secara otomatis sebagai nomor berikutnya dalam urutan.
                            </p>
                        </div>
                        
                        <!-- Foto Kandang -->
                        <div class="mt-4">
                            <label for="foto_kandang" class="text-gray-700 dark:text-gray-200">Foto Kandang <span class="text-red-500">*</span></label>
                            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md dark:border-gray-600">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="foto_kandang" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-primary-dark focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary">
                                            <span class="px-2 py-1 dark:bg-darker dark:text-gray-300">Pilih foto</span>
                                            <input id="foto_kandang" name="foto_kandang" type="file" class="sr-only" accept="image/*" required>
                                        </label>
                                        <p class="pl-1 dark:text-gray-400">atau seret dan lepas</p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        PNG, JPG, JPEG hingga 2MB
                                    </p>
                                    <div id="foto-preview" class="mt-2 hidden">
                                        <img id="preview-image" src="#" alt="Preview" class="h-40 mx-auto rounded-md object-cover">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <h2 class="text-xl font-medium border-b pb-3 mt-10 dark:border-primary-darker">Informasi Pasangan Burung</h2>
                        
                        <!-- Jantan -->
                        <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
                            <div>
                                <label for="ring_jantan" class="text-gray-700 dark:text-gray-200">Ring Number Jantan <span class="text-red-500">*</span></label>
                                <input type="text" id="ring_jantan" name="ring_jantan" required
                                    class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-darker dark:text-gray-300 dark:border-gray-600 focus:border-primary dark:focus:border-primary focus:outline-none focus:ring focus:ring-primary focus:ring-opacity-40">
                            </div>
                            
                            <div>
                                <label for="foto_jantan" class="text-gray-700 dark:text-gray-200">Foto Burung Jantan</label>
                                <input type="file" id="foto_jantan" name="foto_jantan" accept="image/*"
                                    class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-darker dark:text-gray-300 dark:border-gray-600 focus:border-primary dark:focus:border-primary focus:outline-none focus:ring focus:ring-primary focus:ring-opacity-40">
                            </div>
                        </div>
                        
                        <!-- Prestasi Jantan -->
                        <div class="mt-4">
                            <label for="prestasi_jantan" class="text-gray-700 dark:text-gray-200">Prestasi Burung Jantan</label>
                            <textarea id="prestasi_jantan" name="prestasi_jantan" rows="3"
                                class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-darker dark:text-gray-300 dark:border-gray-600 focus:border-primary dark:focus:border-primary focus:outline-none focus:ring focus:ring-primary focus:ring-opacity-40"
                                placeholder="Contoh: Juara 1 Lomba XXX - 12/12/2023"></textarea>
                        </div>
                        
                        <!-- Betina -->
                        <div class="grid grid-cols-1 gap-6 mt-6 sm:grid-cols-2">
                            <div>
                                <label for="ring_betina" class="text-gray-700 dark:text-gray-200">Ring Number Betina <span class="text-red-500">*</span></label>
                                <input type="text" id="ring_betina" name="ring_betina" required
                                    class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-darker dark:text-gray-300 dark:border-gray-600 focus:border-primary dark:focus:border-primary focus:outline-none focus:ring focus:ring-primary focus:ring-opacity-40">
                            </div>
                            
                            <div>
                                <label for="foto_betina" class="text-gray-700 dark:text-gray-200">Foto Burung Betina</label>
                                <input type="file" id="foto_betina" name="foto_betina" accept="image/*"
                                    class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-darker dark:text-gray-300 dark:border-gray-600 focus:border-primary dark:focus:border-primary focus:outline-none focus:ring focus:ring-primary focus:ring-opacity-40">
                            </div>
                        </div>
                        
                        <!-- Karakteristik Betina -->
                        <div class="mt-4">
                            <label for="karakteristik_betina" class="text-gray-700 dark:text-gray-200">Karakteristik Burung Betina</label>
                            <textarea id="karakteristik_betina" name="karakteristik_betina" rows="3"
                                class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-darker dark:text-gray-300 dark:border-gray-600 focus:border-primary dark:focus:border-primary focus:outline-none focus:ring focus:ring-primary focus:ring-opacity-40"
                                placeholder="Deskripsi karakteristik fisik atau perilaku khusus"></textarea>
                        </div>
                        
                        <h2 class="text-xl font-medium border-b pb-3 mt-10 dark:border-primary-darker">Status Breeding</h2>
                        
                        <!-- Status Awal -->
                        <div class="mt-4">
                            <label for="status" class="text-gray-700 dark:text-gray-200">Status Awal Kandang <span class="text-red-500">*</span></label>
                            <select id="status" name="status" required
                                class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-darker dark:text-gray-300 dark:border-gray-600 focus:border-primary dark:focus:border-primary focus:outline-none focus:ring focus:ring-primary focus:ring-opacity-40">
                                <option value="belum_bertelur">Belum Bertelur</option>
                                <option value="bertelur">Bertelur</option>
                                <option value="mengeram">Mengeram</option>
                                <option value="menetas">Menetas</option>
                            </select>
                        </div>
                        
                        <!-- Jumlah Telur -->
                        <div id="telur-container" class="mt-4 hidden">
                            <label for="jumlah_telur" class="text-gray-700 dark:text-gray-200">Jumlah Telur <span class="text-red-500">*</span></label>
                            <input type="number" id="jumlah_telur" name="jumlah_telur" min="1" max="5"
                                class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-darker dark:text-gray-300 dark:border-gray-600 focus:border-primary dark:focus:border-primary focus:outline-none focus:ring focus:ring-primary focus:ring-opacity-40">
                        </div>
                        
                        <!-- Catatan Tambahan -->
                        <div class="mt-6">
                            <label for="catatan" class="text-gray-700 dark:text-gray-200">Catatan Tambahan</label>
                            <textarea id="catatan" name="catatan" rows="3"
                                class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-darker dark:text-gray-300 dark:border-gray-600 focus:border-primary dark:focus:border-primary focus:outline-none focus:ring focus:ring-primary focus:ring-opacity-40"
                                placeholder="Informasi tambahan tentang kandang atau pasangan burung"></textarea>
                        </div>
                    </div>
                    
                    <!-- Form actions -->
                    <div class="flex items-center justify-end px-6 py-4 bg-gray-50 dark:bg-darker-2 border-t dark:border-primary-darker">
                        
                        <button type="submit" class="px-4 py-2 text-white bg-gray-700 rounded-md hover:bg-gray-800 focus:outline-none focus:ring focus:ring-gray-500 focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark">
                            Simpan
                        </button>
                        
                        
                    </div>
                </form>
            </div>
        </div>
    </main>
    
    <!-- Script untuk menangani preview foto dan toggle field -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Preview foto kandang
            const fotoInput = document.getElementById('foto_kandang');
            const previewContainer = document.getElementById('foto-preview');
            const previewImage = document.getElementById('preview-image');
            
            fotoInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        previewContainer.classList.remove('hidden');
                    }
                    reader.readAsDataURL(file);
                } else {
                    previewContainer.classList.add('hidden');
                }
            });
            
            // Toggle field jumlah telur berdasarkan status
            const statusSelect = document.getElementById('status');
            const telurContainer = document.getElementById('telur-container');
            const jumlahTelurInput = document.getElementById('jumlah_telur');
            
            statusSelect.addEventListener('change', function() {
                if (this.value === 'bertelur' || this.value === 'mengeram') {
                    telurContainer.classList.remove('hidden');
                    jumlahTelurInput.required = true;
                } else {
                    telurContainer.classList.add('hidden');
                    jumlahTelurInput.required = false;
                    jumlahTelurInput.value = '';
                }
            });

            // Dalam implementasi sebenarnya, nomor kandang akan diambil dari database
            // Ini hanya simulasi untuk template
            // Kode untuk mendapatkan nomor kandang berikutnya akan ada di controller
            const nomorKandangPreview = document.getElementById('nomor-kandang-preview');
            // Simulasi nomor kandang berikutnya (005)
            nomorKandangPreview.textContent = 'Kandang #005';
        });
    </script>
</x-layout>