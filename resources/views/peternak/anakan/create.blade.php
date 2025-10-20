<x-layout>
    <main>
        <!-- Content header -->
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <div class="flex items-center">
                <a href="{{ url('/anakan') }}" class="mr-4">
                    <button class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-primary focus:outline-none focus:ring focus:ring-primary-lighter">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-primary-light" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                </a>
                <h1 class="text-2xl font-semibold">Tambah Anakan Baru</h1>
            </div>
        </div>

        <!-- Content -->
        <div class="mt-6 px-4 py-4">
            <div class="max-w-3xl mx-auto bg-white dark:bg-darker rounded-md shadow-md overflow-hidden">
                <!-- Sumber Anakan Selection -->
                <div class="p-6 bg-gray-50 dark:bg-darker-2 border-b dark:border-primary-darker">
                    <div class="flex items-center justify-center space-x-8">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="sumber_anakan" id="sumber-peternakan" value="peternakan" class="form-radio h-5 w-5 text-primary" checked>
                            <span class="ml-2 text-gray-700 dark:text-gray-300">Anakan Dari Kandang</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="sumber_anakan" id="sumber-luar" value="luar" class="form-radio h-5 w-5 text-primary">
                            <span class="ml-2 text-gray-700 dark:text-gray-300">Anakan Dari Luar</span>
                        </label>
                    </div>
                </div>

                <form action="{{ route('peternak.anakan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Form untuk anakan dari kandang -->
                    <div id="form-dari-kandang" class="p-6 space-y-6">
                        <h2 class="text-xl font-medium border-b pb-3 dark:border-primary-darker">Pilih Kandang Asal</h2>
                        
                        <!-- Pilih Kandang -->
                        <div class="mt-4">
                            <label for="kandang_id" class="text-gray-700 dark:text-gray-200">Kandang <span class="text-red-500">*</span></label>
                            <select id="kandang_id" name="kandang_id" required
                                class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-darker dark:text-gray-300 dark:border-gray-600 focus:border-primary dark:focus:border-primary focus:outline-none focus:ring focus:ring-primary focus:ring-opacity-40">
                                <option value="" disabled selected>Pilih Kandang</option>
                                @forelse($kandangs as $kandang)
                                    <option value="{{ $kandang->id }}">
                                        {{ $kandang->nomor_kandang }} - 
                                        {{ $kandang->indukanJantan->nomor_ring ?? 'N/A' }} & 
                                        {{ $kandang->indukanBetina->nomor_ring ?? 'N/A' }}
                                    </option>
                                @empty
                                    <option value="" disabled>Tidak ada kandang dengan status "Menetas"</option>
                                @endforelse
                            </select>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Hanya kandang dengan status "Menetas" yang dapat dipilih</p>
                            @error('kandang_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div id="info-kandang" class="mt-4 hidden bg-gray-50 dark:bg-darker-2 p-4 rounded-md border border-gray-200 dark:border-gray-700">
                            <h3 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Informasi Kandang</h3>
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Indukan Jantan</p>
                                    <p class="text-gray-800 dark:text-gray-200" id="info-indukan-jantan">MB-J-001</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Indukan Betina</p>
                                    <p class="text-gray-800 dark:text-gray-200" id="info-indukan-betina">MB-B-001</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Menetas</p>
                                    <p class="text-gray-800 dark:text-gray-200" id="info-tanggal-menetas">12 Juli 2023</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Jumlah Anakan</p>
                                    <p class="text-gray-800 dark:text-gray-200" id="info-jumlah-anakan">3 ekor</p>
                                </div>
                            </div>
                        </div>
                        
                        <h2 class="text-xl font-medium border-b pb-3 mt-10 dark:border-primary-darker">Informasi Anakan</h2>
                        
                        <!-- Jumlah Anakan -->
                        <div class="mt-4">
                            <label for="jumlah_anakan" class="text-gray-700 dark:text-gray-200">Jumlah Anakan <span class="text-red-500">*</span></label>
                            <select id="jumlah_anakan" name="jumlah_anakan" required
                                class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-darker dark:text-gray-300 dark:border-gray-600 focus:border-primary dark:focus:border-primary focus:outline-none focus:ring focus:ring-primary focus:ring-opacity-40">
                                <option value="1">1 ekor</option>
                                <option value="2">2 ekor</option>
                                <option value="3">3 ekor</option>
                                <option value="4">4 ekor</option>
                                <option value="5">5 ekor</option>
                            </select>
                        </div>
                        
                        <!-- Jenis Kelamin (untuk 1 anakan) -->
                        <div id="jenis-kelamin-single" class="mt-4">
                            <label class="text-gray-700 dark:text-gray-200">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <div class="mt-2 flex space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="jenis_kelamin" value="jantan" class="form-radio text-primary" {{ old('jenis_kelamin', 'jantan') == 'jantan' ? 'checked' : '' }}>
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">Jantan</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="jenis_kelamin" value="betina" class="form-radio text-primary" {{ old('jenis_kelamin') == 'betina' ? 'checked' : '' }}>
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">Betina</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="jenis_kelamin" value="tidak_diketahui" class="form-radio text-primary" {{ old('jenis_kelamin') == 'tidak_diketahui' ? 'checked' : '' }}>
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">Belum Tahu</span>
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Jenis kelamin dapat diubah nanti jika belum yakin</p>
                            @error('jenis_kelamin')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Multiple Anakan Form (hidden by default) -->
                        <div id="multiple-anakan-form" class="hidden space-y-4">
                            <!-- Will be populated dynamically by JavaScript -->
                        </div>
                        
                        <!-- Foto Anakan -->
                        <div class="mt-4">
                            <label for="foto_anakan" class="text-gray-700 dark:text-gray-200">Foto Anakan</label>
                            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md dark:border-gray-600">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="foto_anakan" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-primary-dark focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary">
                                            <span class="px-2 py-1 dark:bg-darker dark:text-gray-300">Pilih foto</span>
                                            <input id="foto_anakan" name="foto_anakan" type="file" class="sr-only" accept="image/*">
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
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Foto dapat ditambahkan nanti setelah jenis kelamin dapat diidentifikasi dengan jelas</p>
                        </div>
                        
                        <!-- Catatan Tambahan -->
                        <div class="mt-6">
                            <label for="catatan" class="text-gray-700 dark:text-gray-200">Catatan Tambahan</label>
                            <textarea id="catatan" name="catatan" rows="3"
                                class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-darker dark:text-gray-300 dark:border-gray-600 focus:border-primary dark:focus:border-primary focus:outline-none focus:ring focus:ring-primary focus:ring-opacity-40"
                                placeholder="Informasi tambahan tentang anakan"></textarea>
                        </div>
                    </div>
                    
                    <!-- Form untuk anakan dari luar -->
                    <div id="form-dari-luar" class="hidden p-6 space-y-6">
                        <h2 class="text-xl font-medium border-b pb-3 dark:border-primary-darker">Informasi Anakan</h2>
                        
                        <!-- Ring Number Preview -->
                        <div class="bg-gray-50 dark:bg-darker-2 p-4 rounded-md border border-gray-200 dark:border-gray-700">
                            <p class="text-gray-700 dark:text-gray-300 text-md">
                                <span class="font-medium">Ring Number:</span> 
                                <span id="ring-number-preview" class="text-primary dark:text-primary-light font-semibold"></span>
                                <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">(Otomatis)</span>
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Ring number akan diberikan secara otomatis berdasarkan jenis kelamin.
                            </p>
                        </div>
                        
                        <!-- Jenis Kelamin -->
                        <div class="mt-4">
                            <label for="jenis_kelamin_luar" class="text-gray-700 dark:text-gray-200">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <select id="jenis_kelamin_luar" name="jenis_kelamin_luar" required
                                class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-darker dark:text-gray-300 dark:border-gray-600 focus:border-primary dark:focus:border-primary focus:outline-none focus:ring focus:ring-primary focus:ring-opacity-40">
                                <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                <option value="jantan" {{ old('jenis_kelamin_luar') == 'jantan' ? 'selected' : '' }}>Jantan</option>
                                <option value="betina" {{ old('jenis_kelamin_luar') == 'betina' ? 'selected' : '' }}>Betina</option>
                                <option value="tidak_diketahui" {{ old('jenis_kelamin_luar') == 'tidak_diketahui' ? 'selected' : '' }}>Belum Tahu</option>
                            </select>
                            @error('jenis_kelamin_luar')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Foto Anakan -->
                        <div class="mt-4">
                            <label for="foto_anakan_luar" class="text-gray-700 dark:text-gray-200">Foto Anakan <span class="text-red-500">*</span></label>
                            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md dark:border-gray-600">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="foto_anakan_luar" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-primary-dark focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary">
                                            <span class="px-2 py-1 dark:bg-darker dark:text-gray-300">Pilih foto</span>
                                            <input id="foto_anakan_luar" name="foto_anakan_luar" type="file" class="sr-only" accept="image/*" required>
                                        </label>
                                        <p class="pl-1 dark:text-gray-400">atau seret dan lepas</p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        PNG, JPG, JPEG hingga 2MB
                                    </p>
                                    <div id="foto-preview-luar" class="mt-2 hidden">
                                        <img id="preview-image-luar" src="#" alt="Preview" class="h-40 mx-auto rounded-md object-cover">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tanggal Lahir / Perkiraan -->
                        <div class="mt-4">
                            <label for="tanggal_lahir" class="text-gray-700 dark:text-gray-200">Tanggal Lahir / Perkiraan <span class="text-red-500">*</span></label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" required
                                class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-darker dark:text-gray-300 dark:border-gray-600 focus:border-primary dark:focus:border-primary focus:outline-none focus:ring focus:ring-primary focus:ring-opacity-40">
                        </div>
                        
                        <h2 class="text-xl font-medium border-b pb-3 mt-10 dark:border-primary-darker">Informasi Asal</h2>
                        
                        <!-- Asal/Penjual -->
                        <div class="mt-4">
                            <label for="asal_penjual" class="text-gray-700 dark:text-gray-200">Asal/Penjual <span class="text-red-500">*</span></label>
                            <input type="text" id="asal_penjual" name="asal_penjual" required
                                class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-darker dark:text-gray-300 dark:border-gray-600 focus:border-primary dark:focus:border-primary focus:outline-none focus:ring focus:ring-primary focus:ring-opacity-40"
                                placeholder="Nama peternak/toko">
                        </div>
                        
                        <!-- Tanggal Pembelian -->
                        <div class="mt-4">
                            <label for="tanggal_pembelian" class="text-gray-700 dark:text-gray-200">Tanggal Pembelian <span class="text-red-500">*</span></label>
                            <input type="date" id="tanggal_pembelian" name="tanggal_pembelian" required
                                class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-darker dark:text-gray-300 dark:border-gray-600 focus:border-primary dark:focus:border-primary focus:outline-none focus:ring focus:ring-primary focus:ring-opacity-40">
                        </div>
                        
                        <!-- Informasi Indukan (Opsional) -->
                        <div class="mt-4">
                            <h3 class="text-md font-medium text-gray-700 dark:text-gray-300">Informasi Indukan (Opsional)</h3>
                            <div class="grid grid-cols-1 gap-4 mt-2 sm:grid-cols-2">
                                <div>
                                    <label for="indukan_jantan_info" class="text-gray-700 dark:text-gray-200">Indukan Jantan</label>
                                    <input type="text" id="indukan_jantan_info" name="indukan_jantan_info"
                                        class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-darker dark:text-gray-300 dark:border-gray-600 focus:border-primary dark:focus:border-primary focus:outline-none focus:ring focus:ring-primary focus:ring-opacity-40"
                                        placeholder="Ring number atau deskripsi">
                                </div>
                                <div>
                                    <label for="indukan_betina_info" class="text-gray-700 dark:text-gray-200">Indukan Betina</label>
                                    <input type="text" id="indukan_betina_info" name="indukan_betina_info"
                                        class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-darker dark:text-gray-300 dark:border-gray-600 focus:border-primary dark:focus:border-primary focus:outline-none focus:ring focus:ring-primary focus:ring-opacity-40"
                                        placeholder="Ring number atau deskripsi">
                                </div>
                            </div>
                        </div>
                        
                        <h2 class="text-xl font-medium border-b pb-3 mt-10 dark:border-primary-darker">Status dan Harga</h2>
                        
                        <!-- Status Pertumbuhan -->
                        <div class="mt-4">
                            <label for="status" class="text-gray-700 dark:text-gray-200">Status Pertumbuhan <span class="text-red-500">*</span></label>
                            <select id="status" name="status" required
                                class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-darker dark:text-gray-300 dark:border-gray-600 focus:border-primary dark:focus:border-primary focus:outline-none focus:ring focus:ring-primary focus:ring-opacity-40">
                                <option value="" disabled selected>Pilih Status Pertumbuhan</option>
                                <option value="trotol" {{ old('status') == 'trotol' ? 'selected' : '' }}>Trotol</option>
                                <option value="pastol" {{ old('status') == 'pastol' ? 'selected' : '' }}>Pastol</option>
                                <option value="lomba" class="status-jantan" {{ old('status') == 'lomba' ? 'selected' : '' }}>Lomba</option>
                                <option value="dewasa" class="status-betina hidden">Dewasa</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Harga -->
                        <div class="mt-4">
                            <label for="harga" class="text-gray-700 dark:text-gray-200">Harga <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-700 dark:text-gray-300">Rp</span>
                                <input type="number" id="harga" name="harga" required min="0" step="1000" value="{{ old('harga') }}"
                                    class="block w-full px-10 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-darker dark:text-gray-300 dark:border-gray-600 focus:border-primary dark:focus:border-primary focus:outline-none focus:ring focus:ring-primary focus:ring-opacity-40">
                            </div>
                            @error('harga')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Harga Beli -->
                        <div class="mt-4">
                            <label for="harga_beli" class="text-gray-700 dark:text-gray-200">Harga Beli <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-700 dark:text-gray-300">Rp</span>
                                <input type="number" id="harga_beli" name="harga_beli" required min="0" step="1000" value="{{ old('harga_beli') }}"
                                    class="block w-full px-10 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-darker dark:text-gray-300 dark:border-gray-600 focus:border-primary dark:focus:border-primary focus:outline-none focus:ring focus:ring-primary focus:ring-opacity-40">
                            </div>
                            @error('harga_beli')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Catatan Tambahan -->
                        <div class="mt-6">
                            <label for="catatan_luar" class="text-gray-700 dark:text-gray-200">Catatan Tambahan</label>
                            <textarea id="catatan_luar" name="catatan_luar" rows="3"
                                class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-darker dark:text-gray-300 dark:border-gray-600 focus:border-primary dark:focus:border-primary focus:outline-none focus:ring focus:ring-primary focus:ring-opacity-40"
                                placeholder="Informasi tambahan tentang anakan">{{ old('catatan_luar') }}</textarea>
                            @error('catatan_luar')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Form actions -->
                    <div class="flex items-center justify-end px-6 py-4 bg-gray-50 dark:bg-darker-2 border-t dark:border-primary-darker">
                        <a href="{{ route('peternak.anakan.index') }}" class="px-4 py-2 mr-2 text-gray-700 bg-white border rounded-md dark:text-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-darker-2 focus:outline-none focus:ring focus:ring-primary-lighter">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 text-white bg-primary rounded-md hover:bg-primary-dark focus:outline-none focus:ring focus:ring-primary focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    
    <!-- Script untuk menangani dinamika form -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elements for source toggle
            const sourcePeternakan = document.getElementById('sumber-peternakan');
            const sourceLuar = document.getElementById('sumber-luar');
            const formDariKandang = document.getElementById('form-dari-kandang');
            const formDariLuar = document.getElementById('form-dari-luar');
            
            // Toggle between form sources
            sourcePeternakan.addEventListener('change', function() {
                if (this.checked) {
                    formDariKandang.classList.remove('hidden');
                    formDariLuar.classList.add('hidden');
                }
            });
            
            sourceLuar.addEventListener('change', function() {
                if (this.checked) {
                    formDariKandang.classList.add('hidden');
                    formDariLuar.classList.remove('hidden');
                }
            });
            
            // Kandang selection
            const kandangSelect = document.getElementById('kandang_id');
            const infoKandang = document.getElementById('info-kandang');
            
            kandangSelect.addEventListener('change', function() {
                if (this.value) {
                    infoKandang.classList.remove('hidden');
                    // In real app, you'd fetch kandang data here and update the info
                } else {
                    infoKandang.classList.add('hidden');
                }
            });
            
            // Multiple anakan logic
            const jumlahAnakanSelect = document.getElementById('jumlah_anakan');
            const jenisSingle = document.getElementById('jenis-kelamin-single');
            const multipleForm = document.getElementById('multiple-anakan-form');
            
            jumlahAnakanSelect.addEventListener('change', function() {
                const count = parseInt(this.value);
                
                if (count > 1) {
                    jenisSingle.classList.add('hidden');
                    multipleForm.classList.remove('hidden');
                    
                    // Generate form for multiple anakan
                    multipleForm.innerHTML = '';
                    
                    for (let i = 1; i <= count; i++) {
                        const anakanItem = document.createElement('div');
                        anakanItem.className = 'p-4 border rounded-md dark:border-primary-darker';
                        anakanItem.innerHTML = `
                            <h3 class="font-medium text-gray-800 dark:text-gray-200 mb-3">Anakan #${i}</h3>
                            <div class="mb-3">
                                <!-- Continue from where it was cut off -->
                                <label class="text-gray-700 dark:text-gray-200">Jenis Kelamin <span class="text-red-500">*</span></label>
                                <div class="mt-2 flex space-x-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="jenis_kelamin_${i}" value="jantan" class="form-radio text-primary" checked>
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">Jantan</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="jenis_kelamin_${i}" value="betina" class="form-radio text-primary">
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">Betina</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="jenis_kelamin_${i}" value="belum_tahu" class="form-radio text-primary">
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">Belum Tahu</span>
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="text-gray-700 dark:text-gray-200">Foto Anakan</label>
                                <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md dark:border-gray-600">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="foto_anakan_${i}" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-primary-dark focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary">
                                                <span class="px-2 py-1 dark:bg-darker dark:text-gray-300">Pilih foto</span>
                                                <input id="foto_anakan_${i}" name="foto_anakan_${i}" type="file" class="sr-only" accept="image/*">
                                            </label>
                                            <p class="pl-1 dark:text-gray-400">atau seret dan lepas</p>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            PNG, JPG, JPEG hingga 2MB
                                        </p>
                                        <div id="foto-preview-${i}" class="mt-2 hidden">
                                            <img id="preview-image-${i}" src="#" alt="Preview" class="h-40 mx-auto rounded-md object-cover">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        multipleForm.appendChild(anakanItem);
                    }
                } else {
                    jenisSingle.classList.remove('hidden');
                    multipleForm.classList.add('hidden');
                }
            });
            
            // Photo preview logic
            const fotoInput = document.getElementById('foto_anakan');
            const fotoPreview = document.getElementById('foto-preview');
            const previewImage = document.getElementById('preview-image');
            
            fotoInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        fotoPreview.classList.remove('hidden');
                    };
                    
                    reader.readAsDataURL(this.files[0]);
                }
            });
            
            // Photo preview for "dari luar" form
            const fotoInputLuar = document.getElementById('foto_anakan_luar');
            const fotoPreviewLuar = document.getElementById('foto-preview-luar');
            const previewImageLuar = document.getElementById('preview-image-luar');
            
            fotoInputLuar.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        previewImageLuar.src = e.target.result;
                        fotoPreviewLuar.classList.remove('hidden');
                    };
                    
                    reader.readAsDataURL(this.files[0]);
                }
            });
            
            // Gender-specific options and ring number preview
            const jenisKelaminLuar = document.getElementById('jenis_kelamin_luar');
            const ringNumberPreview = document.getElementById('ring-number-preview');
            const statusSelect = document.getElementById('status');
            const statusJantanOptions = document.querySelectorAll('.status-jantan');
            const statusBetinaOptions = document.querySelectorAll('.status-betina');
            
            jenisKelaminLuar.addEventListener('change', function() {
                // Reset selection
                statusSelect.selectedIndex = 0;
                
                // Show/hide options based on gender
                if (this.value === 'jantan') {
                    statusJantanOptions.forEach(option => option.classList.remove('hidden'));
                    statusBetinaOptions.forEach(option => option.classList.add('hidden'));
                    ringNumberPreview.textContent = 'MB-J-XXX';
                } else if (this.value === 'betina') {
                    statusJantanOptions.forEach(option => option.classList.add('hidden'));
                    statusBetinaOptions.forEach(option => option.classList.remove('hidden'));
                    ringNumberPreview.textContent = 'MB-B-XXX';
                } else {
                    statusJantanOptions.forEach(option => option.classList.remove('hidden'));
                    statusBetinaOptions.forEach(option => option.classList.remove('hidden'));
                    ringNumberPreview.textContent = 'MB-X-XXX';
                }
            });
            
            // Form validation before submit
            const form = document.querySelector('form');
            
            form.addEventListener('submit', function(event) {
                let isValid = true;
                
                // Validate form fields based on which form is active
                if (sourcePeternakan.checked) {
                    // Validate "dari kandang" form
                    if (!kandangSelect.value) {
                        isValid = false;
                        kandangSelect.classList.add('border-red-500');
                    }
                } else {
                    // Validate "dari luar" form
                    if (!jenisKelaminLuar.value) {
                        isValid = false;
                        jenisKelaminLuar.classList.add('border-red-500');
                    }
                    
                    if (!document.getElementById('tanggal_lahir').value) {
                        isValid = false;
                        document.getElementById('tanggal_lahir').classList.add('border-red-500');
                    }
                    
                    if (!document.getElementById('asal_penjual').value) {
                        isValid = false;
                        document.getElementById('asal_penjual').classList.add('border-red-500');
                    }
                    
                    if (!document.getElementById('tanggal_pembelian').value) {
                        isValid = false;
                        document.getElementById('tanggal_pembelian').classList.add('border-red-500');
                    }
                    
                    if (!statusSelect.value) {
                        isValid = false;
                        statusSelect.classList.add('border-red-500');
                    }
                    
                    if (!document.getElementById('harga').value) {
                        isValid = false;
                        document.getElementById('harga').classList.add('border-red-500');
                    }
                    
                    if (!document.getElementById('harga_beli').value) {
                        isValid = false;
                        document.getElementById('harga_beli').classList.add('border-red-500');
                    }
                    
                    if (!fotoInputLuar.files || !fotoInputLuar.files.length) {
                        isValid = false;
                        fotoInputLuar.parentElement.parentElement.parentElement.parentElement.classList.add('border-red-500');
                    }
                }
                
                if (!isValid) {
                    event.preventDefault();
                    // Show error message
                    alert('Mohon lengkapi semua kolom yang wajib diisi!');
                }
            });
            
            // Remove error styling on input
            const inputs = document.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.classList.remove('border-red-500');
                });
            });
            
            // Initialize the preview for multiple anakan photos
            document.addEventListener('change', function(e) {
                if (e.target && e.target.id && e.target.id.startsWith('foto_anakan_') && e.target.type === 'file') {
                    const index = e.target.id.split('_')[2];
                    const previewDiv = document.getElementById(`foto-preview-${index}`);
                    const previewImg = document.getElementById(`preview-image-${index}`);
                    
                    if (e.target.files && e.target.files[0]) {
                        const reader = new FileReader();
                        
                        reader.onload = function(e) {
                            previewImg.src = e.target.result;
                            previewDiv.classList.remove('hidden');
                        };
                        
                        reader.readAsDataURL(e.target.files[0]);
                    }
                }
            });
        });
    </script>
</x-layout>
