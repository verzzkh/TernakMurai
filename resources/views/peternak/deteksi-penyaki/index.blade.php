<x-layout>
    <main>
        <!-- Content header -->
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <h1 class="text-2xl font-semibold">Deteksi Penyakit Murai Batu</h1>
        </div>

        <!-- Content -->
        <div class="mt-2">
            <div class="p-4">
                <!-- Info card -->
                <div class="mb-6 bg-white rounded-lg shadow-md overflow-hidden dark:bg-darker">
                    <div class="p-4 bg-gradient-to-r from-blue-600 to-blue-800 text-white dark:from-blue-800 dark:to-indigo-900">
                        <h2 class="text-lg font-semibold">Tentang Fitur Deteksi Penyakit</h2>
                    </div>
                    <div class="p-5">
                        <p class="text-black mb-3">
                            Fitur ini membantu Anda mengidentifikasi kemungkinan penyakit pada burung Murai Batu berdasarkan foto dan informasi yang Anda berikan.
                        </p>
                        <p class="text-black mb-3">
                            Untuk hasil terbaik, silakan:
                        </p>
                        <ul class="list-disc pl-5 text-black mb-4 space-y-1">
                            <li>Upload foto yang jelas (min. 2 foto dari sudut berbeda)</li>
                            <li>Berikan deskripsi gejala yang lengkap</li>
                            <li>Sertakan informasi tentang perubahan perilaku</li>
                            <li>Jelaskan kondisi lingkungan/kandang</li>
                        </ul>
                        <div class="bg-amber-50 border-l-4 border-amber-500 p-4  dark:border-amber-600">
                            <p class="text-amber-800 dark:text-amber-200 text-sm">
                                <strong>Disclaimer:</strong> Hasil analisis bersifat sebagai referensi awal dan tidak menggantikan diagnosis dari dokter hewan profesional. Konsultasikan dengan dokter hewan untuk kasus serius.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Form Card -->
                <div id="detectionForm" class="bg-white rounded-lg shadow-md overflow-hidden dark:bg-darker">
                    <div class="p-4 bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-900 border-b dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Form Deteksi Penyakit</h2>
                    </div>
                    <div class="p-5">
                        @if(session('error'))
                            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 dark:bg-red-900/30 dark:border-red-600">
                                <p class="text-red-700 dark:text-red-300">{{ session('error') }}</p>
                            </div>
                        @endif

                        <form action="{{ route('deteksi-penyakit.process') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- Image Upload -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-black mb-2">
                                    Upload Foto Burung <span class="text-red-500">*</span>
                                </label>
                                <div id="imagePreviewContainer" class="hidden mb-4 grid grid-cols-2 md:grid-cols-3 gap-3"></div>
                                <div id="dropZone" class="border-2 border-dashed border-blue-300 dark:border-blue-700 rounded-lg p-6 flex flex-col items-center justify-center cursor-pointer hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                                    <svg class="w-12 h-12 text-blue-500 dark:text-blue-400 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-blue-600 dark:text-blue-400 mb-2 font-medium">Klik atau drag & drop foto disini</p>
                                    <p class="text-xs text-blue-500/70 dark:text-blue-500/70">Minimal 1 foto, maksimal 5 foto (JPG, PNG)</p>
                                    <input type="file" id="imageInput" name="images[]" class="hidden" accept="image/jpeg, image/png" multiple required>
                                </div>
                                @error('images')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Symptoms Description -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-black mb-2">
                                    Deskripsi Gejala <span class="text-red-500">*</span>
                                </label>
                                <textarea name="symptoms" rows="4" class="w-full rounded-lg bg-white border border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500/30 focus:ring-opacity-20" placeholder="Deskripsikan gejala yang Anda amati (min. 20 karakter)" required minlength="20">{{ old('symptoms') }}</textarea>
                                @error('symptoms')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Behavior Changes -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-black mb-2">
                                    Perubahan Perilaku <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 dark:bg-gray-800/50 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                                    <div>
                                        <div class="flex items-center mb-2">
                                            <input type="checkbox" id="behavior_1" name="behavior[]" value="Kurang aktif" class="rounded border-gray-300 text-blue-600 focus:border-blue-500 focus:ring focus:ring-blue-500/30 focus:ring-opacity-50">
                                            <label for="behavior_1" class="ml-2 text-sm text-gray-700 dark:text-gray-900">Kurang aktif</label>
                                        </div>
                                        <div class="flex items-center mb-2">
                                            <input type="checkbox" id="behavior_2" name="behavior[]" value="Tidak makan" class="rounded border-gray-300 text-blue-600 focus:border-blue-500 focus:ring focus:ring-blue-500/30 focus:ring-opacity-50">
                                            <label for="behavior_2" class="ml-2 text-sm text-gray-700 dark:text-gray-900">Tidak makan</label>
                                        </div>
                                        <div class="flex items-center mb-2">
                                            <input type="checkbox" id="behavior_3" name="behavior[]" value="Tidak minum" class="rounded border-gray-300 text-blue-600 focus:border-blue-500 focus:ring focus:ring-blue-500/30 focus:ring-opacity-50">
                                            <label for="behavior_3" class="ml-2 text-sm text-gray-700 dark:text-gray-900">Tidak minum</label>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex items-center mb-2">
                                            <input type="checkbox" id="behavior_4" name="behavior[]" value="Tidak berkicau" class="rounded border-gray-300 text-blue-600 focus:border-blue-500 focus:ring focus:ring-blue-500/30 focus:ring-opacity-50">
                                            <label for="behavior_4" class="ml-2 text-sm text-gray-700 dark:text-gray-900">Tidak berkicau</label>
                                        </div>
                                        <div class="flex items-center mb-2">
                                            <input type="checkbox" id="behavior_5" name="behavior[]" value="Bulu mengembang" class="rounded border-gray-300 text-blue-600 focus:border-blue-500 focus:ring focus:ring-blue-500/30 focus:ring-opacity-50">
                                            <label for="behavior_5" class="ml-2 text-sm text-gray-700 dark:text-gray-900">Bulu mengembang</label>
                                        </div>
                                        <div class="flex items-center mb-2">
                                            <input type="checkbox" id="behavior_6" name="behavior[]" value="Sering mengantuk" class="rounded border-gray-300 text-blue-600 focus:border-blue-500 focus:ring focus:ring-blue-500/30 focus:ring-opacity-50">
                                            <label for="behavior_6" class="ml-2 text-sm text-gray-700 dark:text-gray-900">Sering mengantuk</label>
                                        </div>
                                    </div>
                                </div>
                              
                                @error('behavior')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Diet Information -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-black mb-2">
                                    Informasi Pakan & Makan
                                </label>
                                <textarea name="diet_info" rows="2" class="w-full rounded-lg bg-white border border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500/30 focus:ring-opacity-50" placeholder="Jenis pakan, perubahan pola makan, dll.">{{ old('diet_info') }}</textarea>

                            </div>

                            <!-- Environment Information -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-black mb-2">
                                    Informasi Lingkungan Kandang
                                </label>
                                <textarea name="environment_info" rows="2" class="w-full rounded-lg bg-white border border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500/30 focus:ring-opacity-50" placeholder="Suhu, kelembaban, perubahan kondisi kandang, dll.">{{ old('environment_info') }}</textarea>
                            </div>

                            <!-- Health History -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-black mb-2">
                                    Riwayat Kesehatan
                                </label>
                                <textarea name="health_history" rows="2" class="w-full rounded-lg bg-white border border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500/30 focus:ring-opacity-50" placeholder="Riwayat penyakit sebelumnya, pengobatan yang pernah diberikan, dll.">{{ old('health_history') }}</textarea>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="px-5 py-2 text-sm font-medium text-white rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-colors">
                                    <span class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        Analisis Penyakit
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Script untuk image preview dan form handling -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropZone = document.getElementById('dropZone');
            const imageInput = document.getElementById('imageInput');
            const imagePreviewContainer = document.getElementById('imagePreviewContainer');

            // Trigger file input when clicking on drop zone
            dropZone.addEventListener('click', function() {
                imageInput.click();
            });

            // Handle drag and drop
            dropZone.addEventListener('dragover', function(e) {
                e.preventDefault();
                dropZone.classList.add('border-blue-500');
                dropZone.classList.add('bg-blue-50');
                dropZone.classList.add('dark:bg-blue-900/20');
            });

            dropZone.addEventListener('dragleave', function() {
                dropZone.classList.remove('border-blue-500');
                dropZone.classList.remove('bg-blue-50');
                dropZone.classList.remove('dark:bg-blue-900/20');
            });

            dropZone.addEventListener('drop', function(e) {
                e.preventDefault();
                dropZone.classList.remove('border-blue-500');
                dropZone.classList.remove('bg-blue-50');
                dropZone.classList.remove('dark:bg-blue-900/20');
                
                if (e.dataTransfer.files.length) {
                    imageInput.files = e.dataTransfer.files;
                    handleFileSelect();
                }
            });

            // Handle file selection
            imageInput.addEventListener('change', handleFileSelect);

            function handleFileSelect() {
                imagePreviewContainer.innerHTML = '';
                
                if (imageInput.files.length > 0) {
                    imagePreviewContainer.classList.remove('hidden');
                    
                    for (let i = 0; i < Math.min(imageInput.files.length, 5); i++) {
                        const file = imageInput.files[i];
                        const reader = new FileReader();
                        
                        reader.onload = function(e) {
                            const previewDiv = document.createElement('div');
                            previewDiv.className = 'relative bg-white p-1 rounded-lg shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700';
                            
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'w-full h-24 object-cover rounded-md';
                            img.alt = 'Preview';
                            
                            const removeBtn = document.createElement('button');
                            removeBtn.type = 'button';
                            removeBtn.className = 'absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 shadow-sm';
                            removeBtn.innerHTML = '&times;';
                            removeBtn.dataset.index = i;
                            
                            removeBtn.addEventListener('click', function() {
                                // Note: This is a visual removal only, handling actual FileList removal is complex
                                // and might require creating a new FileList object which isn't directly possible
                                previewDiv.remove();
                                
                                // If no previews left, hide container
                                if (imagePreviewContainer.children.length === 0) {
                                    imagePreviewContainer.classList.add('hidden');
                                }
                            });
                            
                            previewDiv.appendChild(img);
                            previewDiv.appendChild(removeBtn);
                            imagePreviewContainer.appendChild(previewDiv);
                        };
                        
                        reader.readAsDataURL(file);
                    }
                } else {
                    imagePreviewContainer.classList.add('hidden');
                }
            }
        });
    </script>
</x-layout>