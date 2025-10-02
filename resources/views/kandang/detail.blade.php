<x-layout>
    <main>
        <!-- Content header -->
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <h1 class="text-2xl font-semibold">Detail Kandang</h1>
            <a href="/kandang" class="px-4 py-2 text-sm text-white rounded-md bg-primary hover:bg-primary-dark focus:outline-none focus:ring focus:ring-primary focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark">
                Kembali
            </a>
        </div>

        <!-- Content -->
        <div class="mt-2 p-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Section Info Utama -->
                <div class="lg:col-span-1 bg-white dark:bg-darker rounded-md shadow-md p-4">
                    <h2 class="text-xl font-semibold mb-4 text-gray-700 dark:text-light">Informasi Kandang</h2>
                    
                    <!-- Foto & Galeri -->
                    <div class="mb-6">
                        <div class="relative h-64 bg-gray-100 dark:bg-gray-800 rounded-md overflow-hidden">
                            <div id="mainImage" class="w-full h-full">
                                <img src="/img/kandang2.jpg" alt="Foto Kandang" class="w-full h-full object-cover" id="currentImage">
                            </div>
                            <div class="absolute bottom-2 right-2 text-white text-xs bg-black bg-opacity-50 px-2 py-1 rounded">
                                <span id="currentImageIndex">1</span>/<span id="totalImages">5</span>
                            </div>
                            <button class="absolute top-1/2 left-2 transform -translate-y-1/2 bg-black bg-opacity-50 rounded-full p-1 text-white" id="prevImage">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <button class="absolute top-1/2 right-2 transform -translate-y-1/2 bg-black bg-opacity-50 rounded-full p-1 text-white" id="nextImage">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Thumbnail Gallery -->
                        <div class="flex mt-2 space-x-2 overflow-x-auto py-2">
                            <div class="w-16 h-16 rounded-md overflow-hidden cursor-pointer border-2 border-primary">
                                <img src="/images/kandang/murai 1.jpeg" alt="Thumbnail" class="w-full h-full object-cover thumbnail-img" data-index="0">
                            </div>
                            <div class="w-16 h-16 rounded-md overflow-hidden cursor-pointer">
                                <img src="/img/placeholder.jpg" alt="Thumbnail" class="w-full h-full object-cover thumbnail-img" data-index="1">
                            </div>
                            <div class="w-16 h-16 rounded-md overflow-hidden cursor-pointer">
                                <img src="/img/kandang3.jpg" alt="Thumbnail" class="w-full h-full object-cover thumbnail-img" data-index="2">
                            </div>
                            <div class="w-16 h-16 flex items-center justify-center bg-primary text-white rounded-md cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Informasi Dasar -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="text-gray-700 dark:text-light font-medium">Nomor Kandang:</span>
                                <input type="text" value="002" class="ml-2 px-2 py-1 border rounded-md dark:bg-darker dark:border-primary focus:outline-none focus:ring focus:ring-primary-light" id="nomorKandang">
                            </div>
                            <button class="text-primary hover:text-primary-dark" id="editNomor">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                        </div>
                        
                        <div>
                            <span class="text-gray-700 dark:text-light font-medium">Status:</span>
                            <span class="ml-2 px-2 py-1 bg-yellow-500 text-white text-sm rounded-md">Bertelur</span>
                        </div>
                        
                        <div>
                            <label class="text-gray-700 dark:text-light font-medium block mb-1">Catatan Kandang:</label>
                            <textarea class="w-full px-3 py-2 border rounded-md dark:bg-darker dark:border-primary focus:outline-none focus:ring focus:ring-primary-light" rows="3" placeholder="Tambahkan catatan...">Kondisi kandang bersih, pasangan produktif.</textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Section Pasangan Burung dan Status Breeding -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Pasangan Burung -->
                    <div class="bg-white dark:bg-darker rounded-md shadow-md p-4">
                        <h2 class="text-xl font-semibold mb-4 text-gray-700 dark:text-light">Pasangan Burung</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Panel Bapak (Jantan) -->
                            <div class="border dark:border-primary-darker rounded-md p-4">
                                <div class="flex justify-between items-start">
                                    <h3 class="text-lg font-medium text-primary-dark dark:text-primary-light">Bapak (Jantan)</h3>
                                    <button class="text-primary hover:text-primary-dark" id="rotateMale">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                    </button>
                                </div>
                                
                                <div class="flex mt-4">
                                    <div class="w-24 h-24 rounded-md overflow-hidden">
                                        <img src="/images/kandang/murai 1.jpeg"alt="Burung Jantan" class="w-full h-full object-cover">
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <p class="text-gray-700 dark:text-light font-medium">Ring Number:</p>
                                        <p class="text-gray-600 dark:text-gray-300">MB-J-002</p>
                                        
                                        <p class="text-gray-700 dark:text-light font-medium mt-2">Prestasi:</p>
                                        <div class="text-gray-600 dark:text-gray-300">
                                            <p>- Juara 1 Lomba XYZ (12/10/2023)</p>
                                            <p>- Juara 2 Lomba ABC (05/06/2023)</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <button id="changeMaleBtn" class="mt-4 w-full px-4 py-2 text-sm text-black rounded-md bg-primary hover:bg-primary-dark focus:outline-none focus:ring focus:ring-primary-dark focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark">
                                    Ganti Bapak
                                </button>
                            </div>
                            
                            <!-- Panel Induk (Betina) -->
                            <div class="border dark:border-primary-darker rounded-md p-4">
                                <div class="flex justify-between items-start">
                                    <h3 class="text-lg font-medium text-primary-dark dark:text-primary-light">Induk (Betina)</h3>
                                    <button class="text-primary hover:text-primary-dark" id="rotateFemale">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                    </button>
                                </div>
                                
                                <div class="flex mt-4">
                                    <div class="w-24 h-24 rounded-md overflow-hidden">
                                        <img src="/images/kandang/betina 1.jpeg" alt="Burung Betina" class="w-full h-full object-cover">
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <p class="text-gray-700 dark:text-light font-medium">Ring Number:</p>
                                        <p class="text-gray-600 dark:text-gray-300">MB-B-002</p>
                                        
                                        <p class="text-gray-700 dark:text-light font-medium mt-2">Karakteristik:</p>
                                        <p class="text-gray-600 dark:text-gray-300">Postur baik, suara merdu, warna dominan hitam</p>
                                    </div>
                                </div>
                                
                                <button id="changeFemaleBtn" class="mt-4 w-full px-4 py-2 text-sm text-black rounded-md bg-primary hover:bg-primary-dark focus:outline-none focus:ring focus:ring-primary-dark focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark">
                                    Ganti Induk
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Status Breeding -->
                    <div class="bg-white dark:bg-darker rounded-md shadow-md p-4">
                        <h2 class="text-xl font-semibold mb-4 text-gray-700 dark:text-light">Status Breeding</h2>
                        
                        <!-- Timeline Visual -->
                        <div class="relative mb-8">
                            <div class="absolute left-0 inset-y-0 w-1 bg-gray-200 dark:bg-gray-700"></div>
                            
                            <!-- Marker Belum Bertelur -->
                            <div class="relative pl-8 pb-4">
                                <div class="absolute left-0 mt-1.5 w-5 h-5 rounded-full bg-blue-500 border-4 border-white dark:border-darker"></div>
                                <div class="text-sm font-medium text-blue-500">Belum Bertelur</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">10 Februari 2024</div>
                            </div>
                            
                            <!-- Marker Bertelur -->
                            <div class="relative pl-8 pb-4">
                                <div class="absolute left-0 mt-1.5 w-5 h-5 rounded-full bg-yellow-500 border-4 border-white dark:border-darker"></div>
                                <div class="text-sm font-medium text-yellow-500">Bertelur</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">15 Februari 2024</div>
                                <div class="text-sm text-gray-600 dark:text-gray-300 mt-1">Bertelur 2 butir</div>
                            </div>
                            
                            <!-- Form Update Status -->
                            <div class="mt-6 border dark:border-primary-darker rounded-md p-4">
                                <h3 class="text-lg font-medium text-gray-700 dark:text-light mb-4">Update Status</h3>
                                
                                <div class="space-y-4" id="updateStatusForm">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-light">Status Baru</label>
                                        <select id="statusSelect" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md dark:bg-darker dark:border-primary">
                                            <option value="mengeram">Mengeram</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Dinamis: Jumlah Telur (untuk status Belum Bertelur -> Bertelur) -->
                                    <div id="jumlahTelurField" class="hidden">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-light">Jumlah Telur</label>
                                        <input type="number" min="1" max="5" value="2" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md dark:bg-darker dark:border-primary">
                                    </div>
                                    
                                    <!-- Dinamis: Jumlah Telur Menetas (untuk status Mengeram -> Menetas) -->
                                    <div id="jumlahMenetasField" class="hidden">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-light">Jumlah Telur Menetas</label>
                                        <input type="number" min="0" max="5" value="0" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md dark:bg-darker dark:border-primary">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-light">Catatan Tambahan</label>
                                        <textarea class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 dark:bg-darker dark:border-primary" rows="3" placeholder="Tambahkan catatan..."></textarea>
                                    </div>
                                    
                                    <button id="updateStatusBtn" class="w-full px-4 py-2 text-sm font-medium text-black bg-primary rounded-md hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-offset-dark">
                                        Update Status
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Section List Anakan -->
                    <div class="bg-white dark:bg-darker rounded-md shadow-md p-4">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold text-gray-700 dark:text-light">Riwayat Anakan</h2>
                            <div class="flex space-x-2">
                                <a href="/anakan/create?kandang_id=2" class="flex items-center px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded-md text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Tambah Anakan
                                </a>
                                <select class="pl-3 pr-10 py-2 text-sm border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary dark:bg-darker dark:border-primary">
                                    <option value="all">Semua Status</option>
                                    <option value="aktif">Aktif</option>
                                    <option value="terjual">Terjual</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Group by breeding period -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium text-primary-dark dark:text-primary-light mb-2">Periode Breeding: Februari 2024</h3>
                                
                                <div class="space-y-3">
                                    <!-- No anakan yet -->
                                    <div class="text-gray-500 dark:text-gray-400 italic">Belum ada anakan pada periode ini</div>
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-lg font-medium text-primary-dark dark:text-primary-light mb-2">Periode Breeding: Desember 2023</h3>
                                
                                <div class="space-y-3">
                                    <!-- Anakan Item -->
                                    <div class="border dark:border-primary-darker rounded-md p-3 flex items-center">
                                        <div class="w-16 h-16 rounded-md overflow-hidden">
                                            <img src="/img/placeholder.jpg" alt="Anakan" class="w-full h-full object-cover">
                                        </div>
                                        <div class="ml-4 flex-1">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <p class="font-medium text-gray-700 dark:text-light">MB-A-001</p>
                                                    <p class="text-sm text-gray-600 dark:text-gray-300">Pastol - Usia: 3 bulan</p>
                                                </div>
                                                <span class="px-2 py-1 bg-green-500 text-white text-xs rounded-md">Aktif</span>
                                            </div>
                                        </div>
                                        <a href="/anakan/detail/1" class="ml-4 text-primary hover:text-primary-dark">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </div>
                                    
                                    <!-- Anakan Item -->
                                    <div class="border dark:border-primary-darker rounded-md p-3 flex items-center">
                                        <div class="w-16 h-16 rounded-md overflow-hidden">
                                            <img src="/img/placeholder.jpg" alt="Anakan" class="w-full h-full object-cover">
                                        </div>
                                        <div class="ml-4 flex-1">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <p class="font-medium text-gray-700 dark:text-light">MB-A-002</p>
                                                    <p class="text-sm text-gray-600 dark:text-gray-300">Pastol - Usia: 3 bulan</p>
                                                </div>
                                                <span class="px-2 py-1 bg-red-500 text-white text-xs rounded-md">Terjual</span>
                                            </div>
                                        </div>
                                        <a href="/anakan/detail/2" class="ml-4 text-primary hover:text-primary-dark">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Modal Tambah Anakan -->
    <div id="addOffspringModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white dark:bg-darker rounded-lg w-full max-w-lg mx-4 overflow-y-auto max-h-[90vh]">
            <div class="px-4 py-3 border-b dark:border-primary-darker flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-700 dark:text-light">Tambah Anakan Baru</h3>
                <button id="closeOffspringModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <form id="addOffspringForm" class="space-y-5">
                    <div>
                        <h4 class="text-md font-medium text-gray-700 dark:text-light mb-3">Informasi Dasar</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-light mb-1">Ring Number</label>
                                <input type="text" id="offspringRingNumber" class="w-full px-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary rounded-md dark:bg-darker dark:border-primary" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-light mb-1">Tanggal Lahir</label>
                                <input type="date" id="offspringBirthDate" class="w-full px-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary rounded-md dark:bg-darker dark:border-primary" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-light mb-1">Gender</label>
                                <select id="offspringGender" class="w-full px-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary rounded-md dark:bg-darker dark:border-primary">
                                    <option value="unknown">Belum Diketahui</option>
                                    <option value="jantan">Jantan</option>
                                    <option value="betina">Betina</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-light mb-1">Harga Awal</label>
                                <input type="text" id="offspringInitialPrice" class="w-full px-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary rounded-md dark:bg-darker dark:border-primary" placeholder="Rp" value="1,000,000">
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="text-md font-medium text-gray-700 dark:text-light mb-3">Data Asal</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-light mb-1">Kandang Asal</label>
                                <input type="text" value="Kandang #002" class="w-full px-3 py-2 text-base border-gray-300 bg-gray-100 dark:bg-gray-700 rounded-md dark:border-primary" disabled>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-light mb-1">Bapak (Jantan)</label>
                                <input type="text" value="MB-J-002" class="w-full px-3 py-2 text-base border-gray-300 bg-gray-100 dark:bg-gray-700 rounded-md dark:border-primary" disabled>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-light mb-1">Induk (Betina)</label>
                                <input type="text" value="MB-B-002" class="w-full px-3 py-2 text-base border-gray-300 bg-gray-100 dark:bg-gray-700 rounded-md dark:border-primary" disabled>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-light mb-1">Periode Breeding</label>
                                <input type="text" value="Februari 2024" class="w-full px-3 py-2 text-base border-gray-300 bg-gray-100 dark:bg-gray-700 rounded-md dark:border-primary" disabled>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-light mb-1">Foto Anakan</label>
                        <div class="border-2 border-dashed dark:border-primary-darker rounded-md px-6 pt-5 pb-6">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="offspring-photo-upload" class="relative cursor-pointer rounded-md font-medium text-primary hover:text-primary-dark focus-within:outline-none">
                                        <span>Upload foto</span>
                                        <input id="offspring-photo-upload" name="offspring-photo-upload" type="file" class="sr-only" accept="image/*">
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    PNG, JPG, GIF up to 5MB
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-light mb-1">Karakteristik & Catatan</label>
                        <textarea id="offspringNotes" class="w-full px-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary rounded-md dark:bg-darker dark:border-primary" rows="3" placeholder="Tambahkan karakteristik atau catatan khusus..."></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-3">
                        <button type="button" id="cancelAddOffspring" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-primary-darker dark:text-light dark:hover:bg-primary-dark">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-offset-dark">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    // -------- GALERI FOTO ---------
    const images = [
        '/images/kandang/murai 1.jpeg',
        '/img/placeholder.jpg',
        '/img/kandang3.jpg',
        '/img/kandang4.jpg',
        '/img/kandang5.jpg'
    ];
    let currentIndex = 0;
    const totalImages = images.length;
    
    document.getElementById('totalImages').textContent = totalImages;
    
    // Event listener untuk thumbnail
    document.querySelectorAll('.thumbnail-img').forEach(thumb => {
        thumb.addEventListener('click', function() {
            currentIndex = parseInt(this.dataset.index);
            updateMainImage();
            updateThumbnailSelection();
        });
    });
    
    // Event listener untuk navigasi
    document.getElementById('prevImage').addEventListener('click', function() {
        currentIndex = (currentIndex - 1 + totalImages) % totalImages;
        updateMainImage();
        updateThumbnailSelection();
    });
    
    document.getElementById('nextImage').addEventListener('click', function() {
        currentIndex = (currentIndex + 1) % totalImages;
        updateMainImage();
        updateThumbnailSelection();
    });
    
    function updateMainImage() {
        document.getElementById('currentImage').src = images[currentIndex];
        document.getElementById('currentImageIndex').textContent = currentIndex + 1;
    }
    
    function updateThumbnailSelection() {
        document.querySelectorAll('.thumbnail-img').forEach((thumb, index) => {
            if (index === currentIndex) {
                thumb.parentElement.classList.add('border-2', 'border-primary');
            } else {
                thumb.parentElement.classList.remove('border-2', 'border-primary');
            }
        });
    }
    
    // -------- EDIT NOMOR KANDANG ---------
    const nomorKandangInput = document.getElementById('nomorKandang');
    const editNomorBtn = document.getElementById('editNomor');
    
    editNomorBtn.addEventListener('click', function() {
        nomorKandangInput.focus();
        nomorKandangInput.select();
    });
    
    nomorKandangInput.addEventListener('blur', function() {
        // Disini bisa ditambahkan ajax untuk update nomor kandang
        console.log('Nomor kandang updated to:', nomorKandangInput.value);
    });

    // -------- HAPUS TOMBOL ROTASI ---------
    // Hapus tombol rotasi dari UI
    const rotateMaleBtn = document.getElementById('rotateMale');
    const rotateFemaleBtn = document.getElementById('rotateFemale');
    
    if (rotateMaleBtn) rotateMaleBtn.remove();
    if (rotateFemaleBtn) rotateFemaleBtn.remove();
    
    // -------- TAMBAHKAN MODAL GANTI BURUNG ---------
    // Cek dan tambahkan modal ganti burung jika belum ada
    if (!document.getElementById('changeBirdModal')) {
        const modalHTML = `
        <div id="changeBirdModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
            <div class="bg-white dark:bg-darker rounded-lg w-full max-w-md mx-4 overflow-hidden">
                <div class="px-4 py-3 border-b dark:border-primary-darker flex justify-between items-center">
                    <h3 id="modalTitle" class="text-lg font-medium text-gray-700 dark:text-light">Ganti Burung</h3>
                    <button id="closeModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-4">
                    <form id="changeBirdForm" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-light mb-1">Pilih Burung</label>
                            <select id="birdSelect" class="w-full px-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary rounded-md dark:bg-darker dark:border-primary">
                                <option value="">-- Pilih Burung --</option>
                                <!-- Opsi burung jantan -->
                                <option value="MB-J-001" data-type="male">MB-J-001 (Jantan)</option>
                                <option value="MB-J-003" data-type="male">MB-J-003 (Jantan)</option>
                                <option value="MB-J-004" data-type="male">MB-J-004 (Jantan)</option>
                                <option value="MB-J-005" data-type="male">MB-J-005 (Jantan)</option>
                                <!-- Opsi burung betina -->
                                <option value="MB-B-001" data-type="female">MB-B-001 (Betina)</option>
                                <option value="MB-B-003" data-type="female">MB-B-003 (Betina)</option>
                                <option value="MB-B-004" data-type="female">MB-B-004 (Betina)</option>
                                <option value="MB-B-005" data-type="female">MB-B-005 (Betina)</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-light mb-1">Alasan Pergantian</label>
                            <textarea id="reasonText" class="w-full px-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary rounded-md dark:bg-darker dark:border-primary" rows="3" placeholder="Jelaskan alasan pergantian..."></textarea>
                        </div>
                        
                        <div class="flex justify-end space-x-3 pt-3">
                            <button type="button" id="cancelChangeBird" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-primary-darker dark:text-light dark:hover:bg-primary-dark">
                                Batal
                            </button>
                            <button type="button" id="confirmChangeBird" class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-offset-dark">
                                Konfirmasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>`;
        
        document.body.insertAdjacentHTML('beforeend', modalHTML);
    }
    
    // Setup event listeners untuk modal ganti burung
    const changeBirdModal = document.getElementById('changeBirdModal');
    const modalTitle = document.getElementById('modalTitle');
    const closeModal = document.getElementById('closeModal');
    const cancelChangeBird = document.getElementById('cancelChangeBird');
    const confirmChangeBird = document.getElementById('confirmChangeBird');
    const birdSelect = document.getElementById('birdSelect');
    
    let currentBirdType = ''; // 'male' atau 'female'
    
    // Tombol ganti bapak
    document.getElementById('changeMaleBtn').addEventListener('click', function() {
        openBirdModal('male');
    });
    
    // Tombol ganti induk
    document.getElementById('changeFemaleBtn').addEventListener('click', function() {
        openBirdModal('female');
    });
    
    function openBirdModal(birdType) {
        currentBirdType = birdType;
        
        // Filter opsi burung berdasarkan jenis kelamin
        Array.from(birdSelect.options).forEach(option => {
            if (option.value === '') return; // Skip option placeholder
            
            const optionType = option.getAttribute('data-type');
            if (optionType === birdType) {
                option.classList.remove('hidden');
            } else {
                option.classList.add('hidden');
            }
        });
        
        modalTitle.textContent = birdType === 'male' ? 'Ganti Bapak (Jantan)' : 'Ganti Induk (Betina)';
        changeBirdModal.classList.remove('hidden');
    }
    
    function closeBirdModal() {
        changeBirdModal.classList.add('hidden');
        birdSelect.value = '';
        document.getElementById('reasonText').value = '';
    }
    
    closeModal.addEventListener('click', closeBirdModal);
    cancelChangeBird.addEventListener('click', closeBirdModal);
    
    confirmChangeBird.addEventListener('click', function() {
        if (!birdSelect.value) {
            alert('Silakan pilih burung terlebih dahulu');
            return;
        }
        
        // Simulasi pergantian burung
        const selectedBirdId = birdSelect.value;
        const selectedOption = birdSelect.options[birdSelect.selectedIndex];
        
        // Update UI berdasarkan burung yang dipilih
        if (currentBirdType === 'male') {
            // Update panel jantan
            const malePanelRingNumber = document.querySelector('.border:nth-of-type(1) .text-gray-600.dark\\:text-gray-300');
            if (malePanelRingNumber) {
                malePanelRingNumber.textContent = selectedBirdId;
            }
        } else {
            // Update panel betina
            const femalePanelRingNumber = document.querySelector('.border:nth-of-type(2) .text-gray-600.dark\\:text-gray-300');
            if (femalePanelRingNumber) {
                femalePanelRingNumber.textContent = selectedBirdId;
            }
        }
        
        alert(`Burung ${currentBirdType === 'male' ? 'jantan' : 'betina'} berhasil diganti dengan ${selectedBirdId}`);
        closeBirdModal();
    });
    
    // -------- FILTER RIWAYAT ANAKAN ---------
    // Data dummy anakan
    const anakans = [
        {
            id: 1,
            ringNumber: 'MB-A-001',
            status: 'aktif',
            stage: 'Pastol',
            age: '3 bulan',
            period: 'Desember 2023',
            image: '/img/placeholder.jpg'
        },
        {
            id: 2,
            ringNumber: 'MB-A-002',
            status: 'terjual',
            stage: 'Pastol',
            age: '3 bulan',
            period: 'Desember 2023',
            image: '/img/placeholder.jpg'
        },
        {
            id: 3,
            ringNumber: 'MB-A-003',
            status: 'aktif',
            stage: 'Trotol',
            age: '1 bulan',
            period: 'Februari 2024',
            image: '/img/placeholder.jpg'
        }
    ];
    
    // Tambahkan event listener untuk filter status anakan
const statusFilter = document.querySelector('select.pl-3.pr-10.py-2.text-sm');
if (statusFilter) {
    statusFilter.addEventListener('change', function() {
        filterAnakans(this.value);
    });
}

// Fungsi untuk memfilter anakan
function filterAnakans(status) {
    console.log("Filter dipilih:", status);
    
    // Cari semua container anakan
    const anakanItems = document.querySelectorAll('.border.dark\\:border-primary-darker.rounded-md.p-3.flex.items-center');
    
    // Loop melalui semua item anakan
    anakanItems.forEach(item => {
        // Cek status badge (Aktif = hijau, Terjual = merah)
        const statusBadge = item.querySelector('span.px-2.py-1');
        
        if (!statusBadge) return;
        
        // Tentukan status berdasarkan warna badge
        const isActive = statusBadge.classList.contains('bg-green-500');
        const isSold = statusBadge.classList.contains('bg-red-500');
        
        // Filter berdasarkan status yang dipilih
        if (status === 'all') {
            item.style.display = 'flex'; // Tampilkan semua
        } else if (status === 'aktif' && isActive) {
            item.style.display = 'flex'; // Tampilkan aktif
        } else if (status === 'terjual' && isSold) {
            item.style.display = 'flex'; // Tampilkan terjual
        } else {
            item.style.display = 'none'; // Sembunyikan yang tidak sesuai
        }
    });
    
    // Update pesan "tidak ada anakan" jika semua anakan dalam periode disembunyikan
    updateEmptyMessages();
}

// Fungsi untuk update pesan "tidak ada anakan" per periode
function updateEmptyMessages() {
    // Cari semua heading periode
    const periodHeadings = document.querySelectorAll('h3.text-lg.font-medium.text-primary-dark.dark\\:text-primary-light.mb-2');
    
    periodHeadings.forEach(heading => {
        // Cari container anakan untuk periode ini
        const container = heading.nextElementSibling;
        if (!container) return;
        
        // Cari anakan yang visible di container ini
        const visibleAnakans = container.querySelectorAll('.border.dark\\:border-primary-darker.rounded-md.p-3.flex.items-center:not([style*="display: none"])');
        
        // Cek apakah ada pesan "belum ada anakan"
        let emptyMessage = container.querySelector('.text-gray-500.dark\\:text-gray-400.italic');
        
        // Jika tidak ada anakan yang terlihat & belum ada pesan
        if (visibleAnakans.length === 0 && !emptyMessage) {
            container.innerHTML += '<div class="text-gray-500 dark:text-gray-400 italic">Tidak ada anakan yang sesuai filter</div>';
        } 
        // Jika ada anakan yang terlihat & ada pesan
        else if (visibleAnakans.length > 0 && emptyMessage) {
            emptyMessage.remove();
        }
    });
}

// Jalankan filter default saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.querySelector('select.pl-3.pr-10.py-2.text-sm');
    if (statusFilter) {
        // Trigger filter dengan nilai default (all)
        filterAnakans(statusFilter.value);
    }
});
    
    // Fungsi untuk membuat elemen anakan dari data
    function createAnakanElement(anakan) {
        const div = document.createElement('div');
        div.className = 'border dark:border-primary-darker rounded-md p-3 flex items-center';
        
        div.innerHTML = `
            <div class="w-16 h-16 rounded-md overflow-hidden">
                <img src="${anakan.image}" alt="Anakan" class="w-full h-full object-cover">
            </div>
            <div class="ml-4 flex-1">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-medium text-gray-700 dark:text-light">${anakan.ringNumber}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-300">${anakan.stage} - Usia: ${anakan.age}</p>
                    </div>
                    <span class="px-2 py-1 bg-${anakan.status === 'aktif' ? 'green' : 'red'}-500 text-white text-xs rounded-md">
                        ${anakan.status === 'aktif' ? 'Aktif' : 'Terjual'}
                    </span>
                </div>
            </div>
            <a href="/anakan/detail/${anakan.id}" class="ml-4 text-primary hover:text-primary-dark">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        `;
        
        return div;
    }
    
    // -------- SISTEM UPDATE STATUS BREEDING ---------
    const currentStatus = 'bertelur'; // Simulasi status saat ini
    const statusSelect = document.getElementById('statusSelect');
    const jumlahTelurField = document.getElementById('jumlahTelurField');
    const jumlahMenetasField = document.getElementById('jumlahMenetasField');
    const updateStatusBtn = document.getElementById('updateStatusBtn');
    
    // Setup form berdasarkan status saat ini
    function setupUpdateStatusForm() {
        // Reset form
        statusSelect.innerHTML = '';
        jumlahTelurField.classList.add('hidden');
        jumlahMenetasField.classList.add('hidden');
        
        // Tampilkan pilihan yang relevan berdasarkan status saat ini
        switch(currentStatus) {
            case 'belum_bertelur':
                statusSelect.innerHTML = '<option value="bertelur">Bertelur</option>';
                jumlahTelurField.classList.remove('hidden');
                break;
            case 'bertelur':
                statusSelect.innerHTML = '<option value="mengeram">Mengeram</option>';
                break;
            case 'mengeram':
                statusSelect.innerHTML = '<option value="menetas">Menetas</option>';
                jumlahMenetasField.classList.remove('hidden');
                break;
            case 'menetas':
                statusSelect.innerHTML = '<option value="belum_bertelur">Belum Bertelur (Periode Baru)</option>';
                break;
        }
    }
    
    // Setup awal form
    setupUpdateStatusForm();
    
    // Event listener untuk update status breeding
    updateStatusBtn.addEventListener('click', function() {
        const newStatus = statusSelect.value;
        let additionalInfo = '';
        
        // Cek untuk input tambahan berdasarkan status
        if (newStatus === 'bertelur' && !jumlahTelurField.classList.contains('hidden')) {
            const jumlahTelur = jumlahTelurField.querySelector('input').value;
            additionalInfo = `, Jumlah Telur: ${jumlahTelur}`;
        } else if (newStatus === 'menetas' && !jumlahMenetasField.classList.contains('hidden')) {
            const jumlahMenetas = jumlahMenetasField.querySelector('input').value;
            additionalInfo = `, Jumlah Menetas: ${jumlahMenetas}`;
            
            // Jika berhasil menetas, tambahkan form untuk input anakan baru
            if (parseInt(jumlahMenetas) > 0) {
                document.getElementById('addOffspringModal').classList.remove('hidden');
            }
        }
        
        // Simulasi update status
        alert(`Status berhasil diupdate dari ${currentStatus} ke ${newStatus}${additionalInfo}`);
        
        // Jika implementasi sebenarnya, perlu reload halaman atau update UI
    });
    
    // -------- MODAL TAMBAH ANAKAN ---------
    const addOffspringModal = document.getElementById('addOffspringModal');
    const closeOffspringModal = document.getElementById('closeOffspringModal');
    const cancelAddOffspring = document.getElementById('cancelAddOffspring');
    const addOffspringForm = document.getElementById('addOffspringForm');
    
    // Setup event listener untuk menutup modal
    if (closeOffspringModal) {
        closeOffspringModal.addEventListener('click', function() {
            addOffspringModal.classList.add('hidden');
        });
    }
    
    if (cancelAddOffspring) {
        cancelAddOffspring.addEventListener('click', function() {
            addOffspringModal.classList.add('hidden');
        });
    }
    
    // Format input harga dengan rupiah
    const priceInput = document.getElementById('offspringInitialPrice');
    if (priceInput) {
        priceInput.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            if (value) {
                value = parseInt(value).toLocaleString('id-ID');
            }
            this.value = value;
        });
    }
    
    // Submit form tambah anakan
    if (addOffspringForm) {
        addOffspringForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validasi form
            const ringNumber = document.getElementById('offspringRingNumber').value;
            const birthDate = document.getElementById('offspringBirthDate').value;
            
            if (!ringNumber || !birthDate) {
                alert('Ring number dan tanggal lahir wajib diisi');
                return;
            }
            
            // Simulasi penambahan anakan baru
            alert(`Anakan baru dengan ring number ${ringNumber} berhasil ditambahkan`);
            addOffspringModal.classList.add('hidden');
            
            // Refresh halaman atau update UI
            // location.reload();
        });
    }
});
    </script>
</x-layout>