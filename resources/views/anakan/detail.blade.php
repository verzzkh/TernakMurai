<x-layout>
    <main>
        <!-- Content header -->
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <h1 class="text-2xl font-semibold">Detail Anakan</h1>
            <a href="/anakan" class="px-4 py-2 text-sm text-white rounded-md bg-primary hover:bg-primary-dark focus:outline-none focus:ring focus:ring-primary focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark">
                Kembali
            </a>
        </div>

        <!-- Content -->
        <div class="mt-2 p-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Section Info Utama -->
                <div class="lg:col-span-1 bg-white dark:bg-darker rounded-md shadow-md p-4">
                    <h2 class="text-xl font-semibold mb-4 text-gray-700 dark:text-light">Informasi Anakan</h2>
                    
                    <!-- Foto Profil -->
                    <div class="mb-6">
                        <div class="relative h-64 bg-gray-100 dark:bg-gray-800 rounded-md overflow-hidden">
                            <img src="/img/placeholder.jpg" alt="Foto Anakan" id="profileImage" class="w-full h-full object-cover">
                            <button class="absolute bottom-2 right-2 bg-primary hover:bg-primary-dark text-white rounded-full p-2" id="changePhotoBtn">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Data Identitas -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700 dark:text-light font-medium">Ring Number:</span>
                            <span class="text-gray-600 dark:text-gray-300">MB-A-001</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700 dark:text-light font-medium">Tanggal Lahir:</span>
                            <span class="text-gray-600 dark:text-gray-300">15 Desember 2023</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700 dark:text-light font-medium">Gender:</span>
                            <div>
                                <select class="pl-2 pr-8 py-1 text-sm border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary dark:bg-darker dark:border-primary">
                                    <option value="jantan">Jantan</option>
                                    <option value="betina">Betina</option>
                                    <option value="unknown" selected>Belum Diketahui</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700 dark:text-light font-medium">Status:</span>
                            <span class="px-2 py-1 bg-blue-500 text-white text-xs rounded-md">Pastol</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700 dark:text-light font-medium">Harga:</span>
                            <div class="flex items-center">
                                <span class="text-gray-600 dark:text-gray-300 mr-2">Rp 2,500,000</span>
                                <button class="text-primary hover:text-primary-dark" id="editPriceBtn">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 dark:text-light font-medium mb-1">Karakteristik & Catatan:</label>
                            <textarea class="w-full px-3 py-2 border rounded-md dark:bg-darker dark:border-primary focus:outline-none focus:ring focus:ring-primary-light" rows="4" placeholder="Tambahkan karakteristik atau catatan khusus...">Anakan jantan berkualitas baik. Perawakan tegap, sudah mulai rajin bunyi. Potensi untuk lomba sangat baik.</textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Section Info Asal dan Actions -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Info Asal -->
                    <div class="bg-white dark:bg-darker rounded-md shadow-md p-4">
                        <h2 class="text-xl font-semibold mb-4 text-gray-700 dark:text-light">Informasi Asal</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Data Kandang -->
                            <div class="border dark:border-primary-darker rounded-md p-4">
                                <h3 class="text-lg font-medium text-primary-dark dark:text-primary-light mb-3">Data Kandang</h3>
                                
                                <div class="space-y-3">
                                    <div class="flex items-center">
                                        <span class="text-gray-700 dark:text-light font-medium w-32">Nomor Kandang:</span>
                                        <a href="/kandang/detail/2" class="text-primary hover:text-primary-dark">Kandang #002</a>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <span class="text-gray-700 dark:text-light font-medium w-32">Periode Breeding:</span>
                                        <span class="text-gray-600 dark:text-gray-300">Desember 2023</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Data Indukan -->
                            <div class="border dark:border-primary-darker rounded-md p-4">
                                <h3 class="text-lg font-medium text-primary-dark dark:text-primary-light mb-3">Data Indukan</h3>
                                
                                <div class="space-y-3">
                                    <div class="flex items-center">
                                        <span class="text-gray-700 dark:text-light font-medium w-32">Bapak (Jantan):</span>
                                        <a href="#" class="text-primary hover:text-primary-dark">MB-J-002</a>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <span class="text-gray-700 dark:text-light font-medium w-32">Induk (Betina):</span>
                                        <a href="#" class="text-primary hover:text-primary-dark">MB-B-002</a>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <span class="text-gray-700 dark:text-light font-medium w-32">Jumlah Saudara:</span>
                                        <span class="text-gray-600 dark:text-gray-300">2 ekor</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tracking Pertumbuhan -->
                    <div class="bg-white dark:bg-darker rounded-md shadow-md p-4">
                        <h2 class="text-xl font-semibold mb-4 text-gray-700 dark:text-light">Tracking Pertumbuhan</h2>
                        
                        <!-- Timeline Visual -->
                        <div class="relative mb-6 pl-8">
                            <div class="absolute left-3 inset-y-0 w-1 bg-gray-200 dark:bg-gray-700"></div>
                            
                            <!-- Marker Trotol -->
                            <div class="relative pb-6">
                                <div class="absolute left-0 mt-1.5 -translate-x-1/2 w-5 h-5 rounded-full bg-blue-300 border-4 border-white dark:border-darker"></div>
                                <div class="text-sm font-medium text-blue-500">Trotol</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">15 Desember 2023</div>
                                <div class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                    <p>Anakan baru menetas</p>
                                    <p>Berat: 12 gram</p>
                                </div>
                            </div>
                            
                            <!-- Marker Pastol -->
                            <div class="relative pb-6">
                                <div class="absolute left-0 mt-1.5 -translate-x-1/2 w-5 h-5 rounded-full bg-blue-500 border-4 border-white dark:border-darker"></div>
                                <div class="text-sm font-medium text-blue-500">Pastol</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">15 Januari 2024</div>
                                <div class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                    <p>Sudah bisa makan sendiri</p>
                                    <p>Berat: 35 gram</p>
                                    <p>Harga updated: Rp 2,500,000</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Panel Update Status -->
                        <div class="border dark:border-primary-darker rounded-md p-4 mt-4">
                            <h3 class="text-lg font-medium text-gray-700 dark:text-light mb-4">Update Status</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-light">Status Baru</label>
                                    <select id="newStatusSelect" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md dark:bg-darker dark:border-primary">
                                        <option value="lomba">Lomba</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-light">Harga Baru</label>
                                    <input type="text" value="5,000,000" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md dark:bg-darker dark:border-primary">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-light">Catatan Perubahan</label>
                                    <textarea class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 dark:bg-darker dark:border-primary" rows="3" placeholder="Tambahkan catatan perubahan..."></textarea>
                                </div>
                                
                                <button id="updateStatusBtn" class="w-full px-4 py-2 text-sm font-medium text-black bg-primary rounded-md hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-offset-dark">
                                    Update Status
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Panel Penjualan -->
                    <div class="bg-white dark:bg-darker rounded-md shadow-md p-4">
                        <h2 class="text-xl font-semibold mb-4 text-gray-700 dark:text-light">Penjualan</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-light">Harga Jual</label>
                                <input type="text" id="salePrice" value="2,500,000" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md dark:bg-darker dark:border-primary">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-light">Tanggal Penjualan</label>
                                <input type="date" id="saleDate" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md dark:bg-darker dark:border-primary">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-light">Catatan Penjualan</label>
                                <textarea id="saleNotes" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 dark:bg-darker dark:border-primary" rows="3" placeholder="Tambahkan catatan penjualan..."></textarea>
                            </div>
                            
                            <button id="confirmSaleBtn" class="w-full px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-dark">
                                Konfirmasi Penjualan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Modal Edit Harga -->
    <div id="editPriceModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white dark:bg-darker rounded-lg w-full max-w-md mx-4 overflow-hidden">
            <div class="px-4 py-3 border-b dark:border-primary-darker flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-700 dark:text-light">Edit Harga</h3>
                <button id="closePriceModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-light mb-1">Harga Baru</label>
                    <input type="text" id="newPrice" class="w-full px-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary rounded-md dark:bg-darker dark:border-primary" value="2,500,000">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-light mb-1">Alasan Perubahan</label>
                    <textarea id="priceChangeReason" class="w-full px-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary rounded-md dark:bg-darker dark:border-primary" rows="3"></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button id="cancelPriceChange" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-primary-darker dark:text-light dark:hover:bg-primary-dark">
                        Batal
                    </button>
                    <button id="confirmPriceChange" class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-offset-dark">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Upload Foto -->
    <div id="uploadPhotoModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white dark:bg-darker rounded-lg w-full max-w-md mx-4 overflow-hidden">
            <div class="px-4 py-3 border-b dark:border-primary-darker flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-700 dark:text-light">Upload Foto Baru</h3>
                <button id="closePhotoModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-light mb-2">Pilih Foto</label>
                    <div class="border-2 border-dashed dark:border-primary-darker rounded-md px-6 pt-5 pb-6">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="file-upload" class="relative cursor-pointer rounded-md font-medium text-primary hover:text-primary-dark focus-within:outline-none">
                                    <span>Upload a file</span>
                                    <input id="file-upload" name="file-upload" type="file" class="sr-only">
                                </label>
                                <p class="pl-1 text-gray-500 dark:text-gray-400">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                PNG, JPG, GIF up to 5MB
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button id="cancelPhotoUpload" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-primary-darker dark:text-light dark:hover:bg-primary-dark">
                        Batal
                    </button>
                    <button id="confirmPhotoUpload" class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-offset-dark">
                        Upload
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Edit Harga
            const editPriceBtn = document.getElementById('editPriceBtn');
            const editPriceModal = document.getElementById('editPriceModal');
            const closePriceModal = document.getElementById('closePriceModal');
            const cancelPriceChange = document.getElementById('cancelPriceChange');
            const confirmPriceChange = document.getElementById('confirmPriceChange');
            
            editPriceBtn.addEventListener('click', function() {
                editPriceModal.classList.remove('hidden');
            });
            
            function closePriceEditModal() {
                editPriceModal.classList.add('hidden');
            }
            
            closePriceModal.addEventListener('click', closePriceEditModal);
            cancelPriceChange.addEventListener('click', closePriceEditModal);
            
            confirmPriceChange.addEventListener('click', function() {
                const newPrice = document.getElementById('newPrice').value;
                const reason = document.getElementById('priceChangeReason').value;
                
                // Simulasi update harga
                alert(`Harga berhasil diupdate menjadi Rp ${newPrice}`);
                closePriceEditModal();
            });
            
            // Upload Foto
            const changePhotoBtn = document.getElementById('changePhotoBtn');
            const uploadPhotoModal = document.getElementById('uploadPhotoModal');
            const closePhotoModal = document.getElementById('closePhotoModal');
            const cancelPhotoUpload = document.getElementById('cancelPhotoUpload');
            const confirmPhotoUpload = document.getElementById('confirmPhotoUpload');
            const fileUpload = document.getElementById('file-upload');
            
            changePhotoBtn.addEventListener('click', function() {
                uploadPhotoModal.classList.remove('hidden');
            });
            
            function closePhotoUploadModal() {
                uploadPhotoModal.classList.add('hidden');
            }
            
            closePhotoModal.addEventListener('click', closePhotoUploadModal);
            cancelPhotoUpload.addEventListener('click', closePhotoUploadModal);
            
            fileUpload.addEventListener('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        // Preview file if needed
                        console.log('File selected:', e.target.result);
                    }
                    
                    reader.readAsDataURL(e.target.files[0]);
                }
            });
            
            confirmPhotoUpload.addEventListener('click', function() {
                if (fileUpload.files && fileUpload.files[0]) {
                    // Simulasi upload foto
                    alert('Foto berhasil diupload');
                    closePhotoUploadModal();
                } else {
                    alert('Silakan pilih foto terlebih dahulu');
                }
            });
            
            // Update Status
            const updateStatusBtn = document.getElementById('updateStatusBtn');
            
            updateStatusBtn.addEventListener('click', function() {
                const newStatus = document.getElementById('newStatusSelect').value;
                
                // Simulasi update status
                alert(`Status berhasil diupdate menjadi ${newStatus}`);
            });
            
            // Penjualan
            const confirmSaleBtn = document.getElementById('confirmSaleBtn');
            
            confirmSaleBtn.addEventListener('click', function() {
                const salePrice = document.getElementById('salePrice').value;
                const saleDate = document.getElementById('saleDate').value;
                const saleNotes = document.getElementById('saleNotes').value;
                
                if (!saleDate) {
                    alert('Silakan masukkan tanggal penjualan');
                    return;
                }
                
                // Simulasi penjualan
                alert(`Penjualan berhasil dicatat dengan harga Rp ${salePrice}`);
                
                // Redirect ke halaman anakan
                // window.location.href = '/anakan';
            });
        });
    </script>
</x-layout>