<x-layout>
    <main>
        <!-- Content header -->
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <h1 class="text-2xl font-semibold">Detail Anakan</h1>
            <a href="{{ route('peternak.anakan.index') }}"
                class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 
          rounded-lg
          focus:outline-none focus:ring focus:ring-blue-600 
          focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark">
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
                            @if ($anakan->foto_path)
                                <img src="{{ Storage::url($anakan->foto_path) }}" alt="Foto Anakan" id="profileImage"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            <button
    id="changePhotoBtn"
    class="absolute top-3 right-3
           bg-white bg-opacity-80 hover:bg-opacity-100
           text-gray-900 text-xs
           px-3 py-1
           rounded-md shadow-md">
    Ganti Foto
</button>

                        </div>
                    </div>

                    <!-- Data Identitas -->
                    <div class="space-y-4">
                        <!-- Trip Kelahiran -->
                        @if ($anakan->perkawinan)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-700 dark:text-light font-medium">Trip Kelahiran:</span>
                                <span class="text-primary font-semibold">
                                    {{ $anakan->perkawinan->nomor_trip ?? '-' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-gray-700 dark:text-light font-medium">Tanggal Kawin:</span>
                                <span class="text-gray-600 dark:text-gray-300">
                                    {{ $anakan->perkawinan->tanggal_kawin?->format('d F Y') ?? '-' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-gray-700 dark:text-light font-medium">Pasangan:</span>
                                <span class="text-gray-600 dark:text-gray-300 text-right">
                                    {{ $anakan->perkawinan->indukanJantan
                                        ? $anakan->perkawinan->indukanJantan->nomor_ring .
                                            ($anakan->perkawinan->indukanJantan->nama ? ' ' . $anakan->perkawinan->indukanJantan->nama : '')
                                        : 'J?' }}

                                    ×

                                    {{ $anakan->perkawinan->indukanBetina
                                        ? $anakan->perkawinan->indukanBetina->nomor_ring .
                                            ($anakan->perkawinan->indukanBetina->nama ? ' ' . $anakan->perkawinan->indukanBetina->nama : '')
                                        : 'B?' }}
                                </span>


                            </div>
                        @endif

                        <div class="flex items-center justify-between">
                            <span class="text-gray-700 dark:text-light font-medium">Ring Number:</span>
                            <input type="text" id="ringNumberInput" value="{{ $anakan->nomor_ring }}"
                                class="w-28 text-sm border-gray-300 rounded-md px-2 py-1 focus:outline-none focus:ring-primary focus:border-primary dark:bg-darker dark:border-primary dark:text-gray-100">
                            <button id="updateRingBtn"
                                class="ml-1 
           bg-cyan-600 text-white text-[10px] 
           px-1.5 py-0.5 rounded 
           hover:bg-cyan-700 
           dark:bg-cyan-500 dark:hover:bg-cyan-600
           transition-colors">
                                Update
                            </button>

                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-gray-700 dark:text-light font-medium">Tanggal Lahir:</span>
                            <span class="text-gray-600 dark:text-gray-300">
                                {{ $anakan->tanggal_lahir ? $anakan->tanggal_lahir->format('d F Y') : 'Tidak diketahui' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-gray-700 dark:text-light font-medium">Umur:</span>
                            <span class="text-gray-600 dark:text-gray-300">{{ $anakan->age['formatted'] }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-gray-700 dark:text-light font-medium">Gender:</span>
                            <div>
                                <select id="genderSelect"
                                    class="pl-2 pr-8 py-1 text-sm border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary dark:bg-darker dark:border-primary">
                                    <option value="jantan" {{ $anakan->jenis_kelamin == 'jantan' ? 'selected' : '' }}>
                                        Jantan</option>
                                    <option value="betina" {{ $anakan->jenis_kelamin == 'betina' ? 'selected' : '' }}>
                                        Betina</option>
                                    <option value="tidak_diketahui"
                                        {{ $anakan->jenis_kelamin == 'tidak_diketahui' ? 'selected' : '' }}>Belum
                                        Diketahui</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-gray-700 dark:text-light font-medium">Status:</span>
                            <span
                                class="px-2 py-1 {{ $anakan->status_pertumbuhan == 'trotol' ? 'bg-blue-500' : ($anakan->status_pertumbuhan == 'pastol' ? 'bg-purple-500' : 'bg-green-500') }} text-white text-xs rounded-md">
                                {{ ucfirst($anakan->status_pertumbuhan) }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-gray-700 dark:text-light font-medium">Harga:</span>
                            <div class="flex items-center">
                                <span class="text-gray-600 dark:text-gray-300">Rp
                                    {{ number_format($anakan->harga ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div>

                            <label class="block text-gray-700 dark:text-light font-medium mb-1">Karakteristik &
                                Catatan:</label>
                            <textarea id="karakteristikTextarea"
                                class="w-full px-3 py-2 border rounded-md dark:bg-darker dark:border-primary focus:outline-none focus:ring focus:ring-primary-light"
                                rows="4" placeholder="Tambahkan karakteristik atau catatan khusus...">{{ $anakan->deskripsi_karakteristik ?? '' }}</textarea>

                            <button id="updateKarakteristikBtn"
                                class="mt-2 w-full bg-cyan-700 hover:bg-cyan-800 
           text-white font-medium py-1.5 rounded-lg">
                                Simpan Karakteristik
                            </button>

                        </div>
                    </div>
                </div>


                <!-- Section Info Asal dan Actions -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Info Asal -->
                    <div class="bg-white dark:bg-darker rounded-md shadow-md p-4">
                        <h2 class="text-xl font-semibold mb-4 text-gray-700 dark:text-light">Informasi Asal</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if ($anakan->kandang)
                                <!-- Data Kandang -->
                                <div class="border dark:border-primary-darker rounded-md p-4">
                                    <h3 class="text-lg font-medium text-primary-dark dark:text-primary-light mb-3">Data
                                        Kandang</h3>

                                    <div class="space-y-3">
                                        <div class="flex items-center">
                                            <span class="text-gray-700 dark:text-light font-medium w-32">Nomor
                                                Kandang:</span>
                                            <a href="{{ route('peternak.kandang.show', $anakan->kandang->id) }}"
                                                class="text-primary hover:text-primary-dark">{{ $anakan->kandang->nomor_kandang }}</a>
                                        </div>

                                        <div class="flex items-center">
                                            <span
                                                class="text-gray-700 dark:text-light font-medium w-32">Deskripsi:</span>
                                            <span
                                                class="text-gray-600 dark:text-gray-300">{{ $anakan->kandang->deskripsi_kandang ?? 'Tidak ada deskripsi' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Data Indukan -->
                                <div class="border dark:border-primary-darker rounded-md p-4">
                                    <h3 class="text-lg font-medium text-primary-dark dark:text-primary-light mb-3">Data
                                        Indukan</h3>

                                    <div class="space-y-3">

                                        @if ($anakan->perkawinan && $anakan->perkawinan->indukanJantan)
                                            <div class="flex items-center">
                                                <span class="text-gray-700 dark:text-light font-medium w-32">
                                                    Bapak (Jantan):
                                                </span>

                                                <a href="{{ route('peternak.indukan.show', $anakan->perkawinan->indukanJantan) }}"
                                                    class="text-primary hover:text-primary-dark">
                                                    {{ $anakan->perkawinan->indukanJantan->nomor_ring }}
                                                    @if ($anakan->perkawinan->indukanJantan->nama)
                                                        - {{ $anakan->perkawinan->indukanJantan->nama }}
                                                    @endif
                                                </a>
                                            </div>
                                        @endif

                                        @if ($anakan->perkawinan && $anakan->perkawinan->indukanBetina)
                                            <div class="flex items-center">
                                                <span class="text-gray-700 dark:text-light font-medium w-32">
                                                    Induk (Betina):
                                                </span>

                                                <a href="{{ route('peternak.indukan.show', $anakan->perkawinan->indukanBetina) }}"
                                                    class="text-primary hover:text-primary-dark">
                                                    {{ $anakan->perkawinan->indukanBetina->nomor_ring }}
                                                    @if ($anakan->perkawinan->indukanBetina->nama)
                                                        - {{ $anakan->perkawinan->indukanBetina->nama }}
                                                    @endif
                                                </a>
                                            </div>
                                        @endif

                                        <div class="flex items-center">
                                            <span class="text-gray-700 dark:text-light font-medium w-32">Jumlah
                                                Saudara:</span>
                                            <span class="text-gray-600 dark:text-gray-300">
                                                {{ $siblings->count() }} ekor
                                            </span>
                                        </div>

                                    </div>

                                </div>
                            @else
                                <!-- Anakan dari luar -->
                                <div class="border dark:border-primary-darker rounded-md p-4">
                                    <h3 class="text-lg font-medium text-primary-dark dark:text-primary-light mb-3">
                                        Informasi Pembelian</h3>

                                    <div class="space-y-3">
                                        <div class="flex items-center">
                                            <span class="text-gray-700 dark:text-light font-medium w-32">Asal:</span>
                                            <span class="text-gray-600 dark:text-gray-300">Dibeli dari luar</span>
                                        </div>

                                        <div class="flex items-center">
                                            <span class="text-gray-700 dark:text-light font-medium w-32">Tanggal
                                                Beli:</span>
                                            <span
                                                class="text-gray-600 dark:text-gray-300">{{ $anakan->created_at->format('d F Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Trip Kelahiran -->



                    <!-- Tracking Pertumbuhan -->
                    <div class="bg-white dark:bg-darker rounded-md shadow-md p-4">
                        <h2 class="text-xl font-semibold mb-4 text-gray-700 dark:text-light">Tracking Pertumbuhan</h2>

                        <!-- Timeline Visual -->
                        <div class="relative mb-6 pl-8">
                            <div class="absolute left-3 inset-y-0 w-1 bg-gray-200 dark:bg-gray-700"></div>

                            @if ($anakan->catatan_perubahan && count($anakan->catatan_perubahan) > 0)
                                @foreach ($anakan->catatan_perubahan as $index => $perubahan)
                                    <div class="relative pb-6">
                                        <div
                                            class="absolute left-0 mt-1.5 -translate-x-1/2 w-5 h-5 rounded-full {{ $perubahan['status_baru'] == 'trotol' ? 'bg-blue-300' : ($perubahan['status_baru'] == 'pastol' ? 'bg-purple-500' : 'bg-green-500') }} border-4 border-white dark:border-darker">
                                        </div>
                                        <div
                                            class="text-sm font-medium {{ $perubahan['status_baru'] == 'trotol' ? 'text-blue-500' : ($perubahan['status_baru'] == 'pastol' ? 'text-purple-500' : 'text-green-500') }}">
                                            {{ ucfirst($perubahan['status_baru']) }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($perubahan['tanggal'])->format('d F Y') }}</div>
                                        <div class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                            @if ($perubahan['harga_lama'] != $perubahan['harga_baru'])
                                                <p>Harga: Rp {{ number_format($perubahan['harga_lama'], 0, ',', '.') }}
                                                    → Rp {{ number_format($perubahan['harga_baru'], 0, ',', '.') }}</p>
                                            @endif
                                            @if ($perubahan['catatan'])
                                                <p>{{ $perubahan['catatan'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="relative pb-6">
                                    <div
                                        class="absolute left-0 mt-1.5 -translate-x-1/2 w-5 h-5 rounded-full {{ $anakan->status_pertumbuhan == 'trotol' ? 'bg-blue-300' : ($anakan->status_pertumbuhan == 'pastol' ? 'bg-purple-500' : 'bg-green-500') }} border-4 border-white dark:border-darker">
                                    </div>
                                    <div
                                        class="text-sm font-medium {{ $anakan->status_pertumbuhan == 'trotol' ? 'text-blue-500' : ($anakan->status_pertumbuhan == 'pastol' ? 'text-purple-500' : 'text-green-500') }}">
                                        {{ ucfirst($anakan->status_pertumbuhan) }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $anakan->created_at->format('d F Y') }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                        <p>Status awal</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Panel Update Status -->
                        <div class="border dark:border-primary-darker rounded-md p-4 mt-4">
                            <h3 class="text-lg font-medium text-gray-700 dark:text-light mb-4">Update Status</h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-light">Status
                                        Baru</label>
                                    <select id="newStatusSelect"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md dark:bg-darker dark:border-primary">
                                        <option value="trotol"
                                            {{ $anakan->status_pertumbuhan == 'trotol' ? 'selected' : '' }}>Trotol
                                        </option>
                                        <option value="pastol"
                                            {{ $anakan->status_pertumbuhan == 'pastol' ? 'selected' : '' }}>Pastol
                                        </option>
                                        <option value="lomba"
                                            {{ $anakan->status_pertumbuhan == 'lomba' ? 'selected' : '' }}>Lomba
                                        </option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-light">Harga
                                        Baru</label>
                                    <input type="text" id="newPriceInput" value="{{ $anakan->harga ?? 0 }}"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md dark:bg-darker dark:border-primary">
                                </div>

                                <button id="updateStatusBtn"
                                    class="w-full px-4 py-2 text-sm font-medium text-white 
           bg-cyan-700 hover:bg-cyan-800 
           rounded-lg 
           focus:outline-none focus:ring-2 focus:ring-cyan-700 focus:ring-offset-2">
                                    Update Status
                                </button>

                            </div>
                        </div>
                    </div>

                    <!-- Panel Penjualan -->
                    @if ($anakan->status_penjualan === 'belum_dijual')
                        <div class="bg-white dark:bg-darker rounded-md shadow-md p-4">
                            <h2 class="text-xl font-semibold mb-4 text-gray-700 dark:text-light">Penjualan</h2>

                            <form id="saleForm" action="{{ route('peternak.anakan.sell', $anakan->id) }}"
                                method="POST">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-light">Harga
                                            Jual</label>
                                        <input type="text" name="harga_jual" id="salePrice"
                                            value="{{ $anakan->harga ?? 0 }}"
                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md dark:bg-darker dark:border-primary"
                                            required>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-light">Tanggal
                                            Penjualan</label>
                                        <input type="date" name="tanggal_jual" id="saleDate"
                                            value="{{ now()->format('Y-m-d') }}"
                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md dark:bg-darker dark:border-primary"
                                            required>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-light">Catatan
                                            Penjualan</label>
                                        <textarea name="catatan_penjualan" id="saleNotes"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 dark:bg-darker dark:border-primary"
                                            rows="3" placeholder="Tambahkan catatan penjualan..."></textarea>
                                    </div>

                                    <button type="submit"
                                        class="w-full px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-dark">
                                        Konfirmasi Penjualan
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="bg-white dark:bg-darker rounded-md shadow-md p-4">
                            <h2 class="text-xl font-semibold mb-4 text-gray-700 dark:text-light">Status Penjualan</h2>

                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700 dark:text-light font-medium">Status:</span>
                                    <span class="px-2 py-1 bg-red-500 text-white text-xs rounded-md">Terjual</span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700 dark:text-light font-medium">Tanggal Jual:</span>
                                    <span
                                        class="text-gray-600 dark:text-gray-300">{{ $anakan->tanggal_jual ? $anakan->tanggal_jual->format('d F Y') : 'Tidak diketahui' }}</span>
                                </div>

                                @if ($anakan->catatan_penjualan)
                                    <div>
                                        <span class="text-gray-700 dark:text-light font-medium">Catatan
                                            Penjualan:</span>
                                        <p class="text-gray-600 dark:text-gray-300 mt-1">
                                            {{ $anakan->catatan_penjualan }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Edit Harga -->
    <div id="editPriceModal"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white dark:bg-darker rounded-lg w-full max-w-md mx-4 overflow-hidden">
            <div class="px-4 py-3 border-b dark:border-primary-darker flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-700 dark:text-light">Edit Harga</h3>
                <button id="closePriceModal"
                    class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-light mb-1">Harga Baru</label>
                    <input type="text" id="newPriceInput"
                        class="w-full px-3 py-2 text-base border-gray-300 rounded-md" value="{{ $anakan->harga }}">

                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-light mb-1">Alasan
                        Perubahan</label>
                    <textarea id="priceChangeReason"
                        class="w-full px-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary rounded-md dark:bg-darker dark:border-primary"
                        rows="3"></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button id="cancelPriceChange"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-primary-darker dark:text-light dark:hover:bg-primary-dark">
                        Batal
                    </button>
                    <button id="confirmPriceChange"
                        class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-offset-dark">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Upload Foto -->
    <div id="uploadPhotoModal"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white dark:bg-darker rounded-lg w-full max-w-md mx-4 overflow-hidden">
            <div class="px-4 py-3 border-b dark:border-primary-darker flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-700 dark:text-light">Upload Foto Baru</h3>
                <button id="closePhotoModal"
                    class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-light mb-2">Pilih Foto</label>
                    <div class="border-2 border-dashed dark:border-primary-darker rounded-md px-6 pt-5 pb-6">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                viewBox="0 0 48 48" aria-hidden="true">
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="file-upload"
                                    class="relative cursor-pointer rounded-md
               bg-blue-600 hover:bg-blue-700
               dark:bg-blue-500 dark:hover:bg-blue-600
               text-white font-medium
               px-3 py-1
               focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-500">
                                    <span>Upload a file</span>
                                    <input id="file-upload" name="file-upload" type="file" class="sr-only">
                                </label>

                                <p class="pl-2 text-gray-500 dark:text-gray-400">
                                    or drag and drop
                                </p>
                            </div>

                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                PNG, JPG, GIF up to 5MB
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button id="cancelPhotoUpload"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-primary-darker dark:text-light dark:hover:bg-primary-dark">
                        Batal
                    </button>
                   <button id="confirmPhotoUpload"
    class="px-4 py-2 text-sm font-medium
           text-white
           bg-cyan-600 hover:bg-cyan-700
           dark:bg-cyan-500 dark:hover:bg-cyan-600
           rounded-md
           focus:outline-none focus:ring-2
           focus:ring-cyan-500 focus:ring-offset-2
           dark:focus:ring-offset-dark">
    Upload
</button>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const anakanId = {{ $anakan->id }};
            const baseUrl = `/peternak/anakan/${anakanId}`;

            // ===================================================
            // 🔔 TOAST NOTIFICATION
            // ===================================================
            function showToast(message, success = true) {
                const toast = document.createElement('div');
                toast.textContent = message;
                toast.className = `
            fixed bottom-4 right-4 z-50 px-4 py-2 rounded-md text-white text-sm shadow-lg
            ${success ? 'bg-green-600' : 'bg-red-600'}
            opacity-0 translate-y-3 transition-all duration-300
        `;
                document.body.appendChild(toast);

                requestAnimationFrame(() => {
                    toast.classList.remove('opacity-0', 'translate-y-3');
                });

                setTimeout(() => {
                    toast.classList.add('opacity-0', 'translate-y-3');
                    setTimeout(() => toast.remove(), 300);
                }, 2500);
            }

            // ===================================================
            // 📸 UPLOAD FOTO
            // ===================================================
            const changePhotoBtn = document.getElementById('changePhotoBtn');
            const uploadPhotoModal = document.getElementById('uploadPhotoModal');
            const closePhotoModal = document.getElementById('closePhotoModal');
            const cancelPhotoUpload = document.getElementById('cancelPhotoUpload');
            const confirmPhotoUpload = document.getElementById('confirmPhotoUpload');
            const fileUpload = document.getElementById('file-upload');

            function togglePhotoModal(show = true) {
                uploadPhotoModal.classList.toggle('hidden', !show);
            }

            if (changePhotoBtn) {
                changePhotoBtn.addEventListener('click', () => togglePhotoModal(true));
                closePhotoModal?.addEventListener('click', () => togglePhotoModal(false));
                cancelPhotoUpload?.addEventListener('click', () => togglePhotoModal(false));
            }

            confirmPhotoUpload?.addEventListener('click', async function() {
                if (!fileUpload.files || !fileUpload.files[0]) {
                    showToast('Pilih foto terlebih dahulu', false);
                    return;
                }

                const formData = new FormData();
                formData.append('foto_anakan', fileUpload.files[0]);
                formData.append('_token', csrf);
                formData.append('_method', 'PUT');

                try {
                    const res = await fetch(baseUrl, {
                        method: 'POST',
                        body: formData
                    });

                    const data = await res.json();
                    if (data.success) {
                        showToast('Foto berhasil diperbarui ✅');
                        togglePhotoModal(false);
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast('Gagal memperbarui foto ❌', false);
                    }
                } catch (err) {
                    console.error(err);
                    showToast('Terjadi kesalahan jaringan ⚠️', false);
                }
            });

            // ===================================================
            // 🧬 UPDATE GENDER
            // ===================================================
            const genderSelect = document.getElementById('genderSelect');
            genderSelect?.addEventListener('change', async function() {
                const newGender = this.value;

                try {
                    const res = await fetch(baseUrl, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                        },
                        body: JSON.stringify({
                            jenis_kelamin: newGender,
                            deskripsi_karakteristik: document.getElementById(
                                'karakteristikTextarea').value,
                        }),
                    });

                    const data = await res.json();
                    if (data.success) {
                        showToast('Gender berhasil diperbarui ✅');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast('Gagal memperbarui gender ❌', false);
                    }
                } catch (err) {
                    console.error(err);
                    showToast('Kesalahan jaringan ⚠️', false);
                }
            });

            function formatRupiah(value) {
                return value.replace(/\D/g, "")
                    .replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            // ===================================================
            // 💰 FORMAT RUPIAH — FORM PENJUALAN
            // ===================================================
            const salePriceInput = document.getElementById("salePrice");

            if (salePriceInput) {

                // Format saat load
                if (salePriceInput.value.trim() !== "") {
                    salePriceInput.value = formatRupiah(salePriceInput.value);
                }

                // Format saat mengetik
                salePriceInput.addEventListener("input", function() {
                    this.value = formatRupiah(this.value);
                });

                // Hapus titik sebelum submit
                const saleForm = document.getElementById("saleForm");
                saleForm.addEventListener("submit", function() {
                    salePriceInput.value = salePriceInput.value.replace(/\./g, "");
                });
            }


            const hargaInput = document.getElementById("hargaAnakan");

            if (hargaInput) {
                // Format saat load
                if (hargaInput.value.trim() !== "") {
                    hargaInput.value = formatRupiah(hargaInput.value);
                }

                // Format saat mengetik
                hargaInput.addEventListener("input", function() {
                    this.value = formatRupiah(this.value);
                });

                // Bersihkan titik sebelum submit
                hargaInput.form.addEventListener("submit", function() {
                    hargaInput.value = hargaInput.value.replace(/\./g, "");
                });
            }


            // ===================================================
            // ✏️ AUTO-SAVE KARAKTERISTIK
            // ===================================================
            const karakteristikTextarea = document.getElementById('karakteristikTextarea');
            if (karakteristikTextarea) {
                let typingTimer;
                karakteristikTextarea.addEventListener('input', function() {
                    clearTimeout(typingTimer);
                    typingTimer = setTimeout(async () => {
                        try {
                            const res = await fetch(baseUrl, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrf,
                                },
                                body: JSON.stringify({
                                    jenis_kelamin: genderSelect.value,
                                    deskripsi_karakteristik: this.value.trim(),
                                }),
                            });
                            const data = await res.json();
                            if (data.success) {
                                showToast('Karakteristik tersimpan ✅');
                            } else {
                                showToast('Gagal menyimpan karakteristik ❌', false);
                            }
                        } catch (err) {
                            console.error(err);
                            showToast('Kesalahan jaringan ⚠️', false);
                        }
                    }, 1000); // autosave setelah berhenti mengetik 1 detik
                });
            }

            // ===================================================
            // 🧾 UPDATE STATUS PERTUMBUHAN
            // ===================================================
            const updateStatusBtn = document.getElementById('updateStatusBtn');

            updateStatusBtn?.addEventListener('click', async function() {
                const newStatus = document.getElementById('newStatusSelect').value;
                let newPrice = document.getElementById('newPriceInput').value;

                // Hapus titik sebelum dikirim ke backend
                newPrice = newPrice.replace(/\./g, "");

                if (!newStatus) {
                    showToast('Silakan pilih status pertumbuhan', false);
                    return;
                }

                if (!newPrice) {
                    showToast('Masukkan harga terlebih dahulu', false);
                    return;
                }

                try {
                    const res = await fetch(`${baseUrl}/status`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                        },
                        body: JSON.stringify({
                            status_pertumbuhan: newStatus,
                            harga: parseInt(newPrice),
                            catatan_perubahan: '',
                        }),
                    });

                    const data = await res.json();
                    if (data.success) {
                        showToast('Status berhasil diperbarui ✅');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast('Gagal memperbarui status ❌', false);
                    }
                } catch (err) {
                    console.error(err);
                    showToast('Kesalahan jaringan ⚠️', false);
                }
            });
            const newPriceInput = document.getElementById("newPriceInput");

            if (newPriceInput) {
                // Format saat user mengetik
                newPriceInput.addEventListener("input", function() {
                    this.value = formatRupiah(this.value);
                });

                // Format saat terbuka (jika ada nilai existing)
                if (newPriceInput.value.trim() !== "") {
                    newPriceInput.value = formatRupiah(newPriceInput.value);
                }
            }


            // ===================================================
            // 💍 INLINE EDIT NOMOR RING
            // ===================================================
            const ringDisplay = document.querySelector('[data-ring-display]');
            if (ringDisplay) {
                const originalRing = ringDisplay.textContent.trim();
                const input = document.createElement('input');
                input.type = 'text';
                input.value = originalRing;
                input.className =
                    'border border-gray-300 rounded px-2 py-1 text-sm w-36 focus:ring focus:ring-primary dark:bg-darker dark:text-gray-100';
                ringDisplay.replaceWith(input);

                input.addEventListener('blur', async function() {
                    const newRing = input.value.trim();
                    if (newRing !== originalRing && newRing !== '') {
                        try {
                            const res = await fetch(baseUrl, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrf,
                                },
                                body: JSON.stringify({
                                    nomor_ring: newRing
                                }),
                            });
                            const data = await res.json();
                            if (data.success) {
                                showToast('Nomor ring berhasil diupdate ✅');
                            } else {
                                showToast('Gagal memperbarui nomor ring ❌', false);
                            }
                        } catch (err) {
                            console.error(err);
                            showToast('Kesalahan jaringan ⚠️', false);
                        }
                    }
                });
            }


            // ===================================================
            // 💍 UPDATE NOMOR RING MANUAL
            // ===================================================
            const updateRingBtn = document.getElementById('updateRingBtn');
            updateRingBtn?.addEventListener('click', async function() {
                const newRing = document.getElementById('ringNumberInput').value.trim();
                if (!newRing) {
                    showToast('Nomor ring tidak boleh kosong', false);
                    return;
                }

                try {
                    const res = await fetch(baseUrl, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                        },
                        body: JSON.stringify({
                            nomor_ring: newRing,
                            jenis_kelamin: document.getElementById('genderSelect')
                                .value,
                            deskripsi_karakteristik: document.getElementById(
                                'karakteristikTextarea').value,
                        }),
                    });

                    const data = await res.json();
                    if (data.success) {
                        showToast('Nomor ring berhasil diperbarui ✅');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast('Gagal memperbarui nomor ring ❌', false);
                    }
                } catch (err) {
                    console.error(err);
                    showToast('Kesalahan jaringan ⚠️', false);
                }
            });

            // ===================================================
            // 🧾 UPDATE KARAKTERISTIK MANUAL
            // ===================================================
            const updateKarakteristikBtn = document.getElementById('updateKarakteristikBtn');
            updateKarakteristikBtn?.addEventListener('click', async function() {
                const karakteristik = document.getElementById('karakteristikTextarea').value.trim();

                try {
                    const res = await fetch(baseUrl, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                        },
                        body: JSON.stringify({
                            jenis_kelamin: document.getElementById('genderSelect')
                                .value,
                            deskripsi_karakteristik: karakteristik,
                        }),
                    });
                    const data = await res.json();
                    if (data.success) {
                        showToast('Karakteristik berhasil diperbarui ✅');
                    } else {
                        showToast('Gagal memperbarui karakteristik ❌', false);
                    }
                } catch (err) {
                    console.error(err);
                    showToast('Kesalahan jaringan ⚠️', false);
                }
            });
        });
    </script>

</x-layout>
