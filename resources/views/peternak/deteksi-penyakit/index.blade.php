<x-layout>
    <main>
        <!-- Content header -->
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <h1 class="text-2xl font-semibold">Deteksi Penyakit Murai Batu</h1>
        </div>

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

                        <p class="text-black mb-3">Untuk hasil terbaik, silakan:</p>

                        <ul class="list-disc pl-5 text-black mb-4 space-y-1">
                            <li>Upload foto yang jelas (maks 5)</li>
                            <li>Tulis gejala sejelas mungkin</li>
                            <li>Pilih perilaku yang berubah</li>
                            <li>Tambahkan riwayat kesehatan & info kandang</li>
                        </ul>

                        <div class="bg-amber-50 border-l-4 border-amber-500 p-4 dark:border-amber-600">
                            <p class="text-amber-800 dark:text-amber-200 text-sm">
                                <strong>Disclaimer:</strong> Hasil analisis bersifat referensi awal, bukan pengganti diagnosis dokter hewan.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- FORM DETEKSI -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden dark:bg-darker">
                    <div class="p-4 bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-900 border-b dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Form Deteksi Penyakit</h2>
                    </div>

                    <div class="p-5">

                        @if(session('error'))
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 dark:bg-red-900/30 dark:border-red-600">
                            <p class="text-red-700 dark:text-red-300">{{ session('error') }}</p>
                        </div>
                        @endif

                        <form action="{{ route('peternak.deteksi-penyakit.process') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- FOTO -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-black mb-2">
                                    Upload Foto Burung <span class="text-red-500">*</span>
                                </label>

                                <div id="previewContainer" class="hidden mb-4 grid grid-cols-2 md:grid-cols-3 gap-3"></div>

                                <div id="dropArea"
                                    class="border-2 border-dashed border-blue-300 dark:border-blue-700 rounded-lg p-6 flex flex-col items-center justify-center cursor-pointer hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                                    <svg class="w-12 h-12 text-blue-500 dark:text-blue-400 mb-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-blue-600 dark:text-blue-400 font-medium mb-1">Klik atau drag & drop foto disini</p>
                                    <p class="text-xs text-blue-500/70 dark:text-blue-500/70">Maksimal 5 foto (JPG/PNG)</p>

                                    <input type="file" id="fileInput" name="foto[]" class="hidden" accept="image/*" multiple required>
                                </div>

                                @error('foto.*')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- GEJALA -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-black mb-2">Deskripsi Gejala *</label>

                                <textarea name="gejala" rows="4" required minlength="20"
                                    class="w-full rounded-lg bg-white border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500/30">{{ old('gejala') }}</textarea>

                                @error('gejala')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- PERILAKU -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-black mb-2">Perubahan Perilaku *</label>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 dark:bg-gray-800/50 p-4 rounded-lg border">
                                    @php
                                    $behaviorOptions = [
                                        "Kurang aktif",
                                        "Tidak makan",
                                        "Tidak minum",
                                        "Tidak berkicau",
                                        "Bulu mengembang",
                                        "Sering mengantuk",
                                    ];
                                    @endphp

                                    @foreach ($behaviorOptions as $index => $beh)
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" name="perilaku[]" value="{{ $beh }}"
                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span class="text-gray-700 dark:text-gray-200">{{ $beh }}</span>
                                    </label>
                                    @endforeach
                                </div>

                                @error('perilaku')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- MAKANAN -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-black mb-2">Informasi Pakan & Makan</label>

                                <textarea name="makanan" rows="2"
                                    class="w-full rounded-lg border">{{ old('makanan') }}</textarea>
                            </div>

                            <!-- LINGKUNGAN -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-black mb-2">Informasi Lingkungan Kandang</label>
                                <textarea name="lingkungan" rows="2"
                                    class="w-full rounded-lg border">{{ old('lingkungan') }}</textarea>
                            </div>

                            <!-- RIWAYAT KESEHATAN -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-black mb-2">Riwayat Kesehatan</label>
                                <textarea name="riwayat_kesehatan" rows="2"
                                    class="w-full rounded-lg border">{{ old('riwayat_kesehatan') }}</textarea>
                            </div>

                            <!-- SUBMIT -->
                            <div class="flex justify-end">
                                <button type="submit"
                                    class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg shadow hover:from-blue-700 hover:to-indigo-700">
                                    Analisis Penyakit
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- SCRIPT PREVIEW GAMBAR -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const dropArea = document.getElementById("dropArea");
            const fileInput = document.getElementById("fileInput");
            const previewContainer = document.getElementById("previewContainer");

            dropArea.addEventListener("click", () => fileInput.click());

            dropArea.addEventListener("dragover", (e) => {
                e.preventDefault();
                dropArea.classList.add("bg-blue-50");
            });

            dropArea.addEventListener("dragleave", () => {
                dropArea.classList.remove("bg-blue-50");
            });

            dropArea.addEventListener("drop", (e) => {
                e.preventDefault();
                fileInput.files = e.dataTransfer.files;
                showPreview();
            });

            fileInput.addEventListener("change", showPreview);

            function showPreview() {
                previewContainer.innerHTML = "";

                let files = fileInput.files;
                if (!files.length) return;

                previewContainer.classList.remove("hidden");

                [...files].slice(0, 5).forEach((file) => {
                    let reader = new FileReader();
                    reader.onload = (e) => {
                        let div = document.createElement("div");
                        div.className = "relative bg-white p-1 rounded border shadow";

                        let img = document.createElement("img");
                        img.className = "w-full h-24 object-cover rounded";
                        img.src = e.target.result;

                        div.appendChild(img);
                        previewContainer.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            }
        });
    </script>
</x-layout>
