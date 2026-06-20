<x-layout>
    <main>
        <!-- Content header -->
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <h1 class="text-2xl font-semibold">Manajemen Kandang</h1>

            <div class="flex items-center space-x-4">

                <!-- Tombol Analisis Kecocokan Indukan -->
                <a href="{{ route('peternak.analisaBreeding.form') }}"
                    class="px-4 py-2 text-white bg-purple-600 hover:bg-purple-700 
           rounded-lg focus:outline-none focus:ring focus:ring-purple-600 
           focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark">
                    Analisis Indukan
                </a>

                <!-- Tombol Tambah Kandang -->
                <a href="{{ route('peternak.kandang.create') }}"
                    class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 
                   rounded-lg focus:outline-none focus:ring focus:ring-blue-600 
                   focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark">
                    Tambah Kandang
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="px-4 py-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 justify-center">

                <!-- Total Kandang -->
                <div class="bg-white dark:bg-darker rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-text-tertiary dark:text-gray-400">Total</p>
                            <p class="text-2xl font-semibold text-text-primary dark:text-white">{{ $stats['total'] }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Kosong -->
                <div class="bg-white dark:bg-darker rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-text-tertiary" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-text-tertiary dark:text-gray-400">Kosong</p>
                            <p class="text-2xl font-semibold text-text-primary dark:text-white">{{ $stats['kosong'] }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Bertelur -->
                <div class="bg-white dark:bg-darker rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-text-tertiary dark:text-gray-400">Bertelur</p>
                            <p class="text-2xl font-semibold text-text-primary dark:text-white">{{ $stats['bertelur'] }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Mengeram -->
                <div class="bg-white dark:bg-darker rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-text-tertiary dark:text-gray-400">Mengeram</p>
                            <p class="text-2xl font-semibold text-text-primary dark:text-white">{{ $stats['mengeram'] }}
                            </p>
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <!-- Search and Filter -->
        <div class="px-4 py-4 border-b dark:border-primary-darker">
            <form method="GET" class="flex flex-col space-y-4 sm:flex-row sm:space-y-0 sm:space-x-4">
                <!-- Search Input -->
                <div class="flex-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                            placeholder="Cari nomor kandang atau deskripsi..."
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white dark:bg-darker dark:border-primary-darker dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm">
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="sm:w-48">
                    <select name="status"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md bg-white dark:bg-darker dark:border-primary-darker dark:text-white focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                        <option value="">Semua Status</option>
                        <option value="kosong" {{ ($filters['status'] ?? '') === 'kosong' ? 'selected' : '' }}>Kosong
                        </option>
                        <option value="bertelur" {{ ($filters['status'] ?? '') === 'bertelur' ? 'selected' : '' }}>
                            Bertelur</option>
                        <option value="mengeram" {{ ($filters['status'] ?? '') === 'mengeram' ? 'selected' : '' }}>
                            Mengeram</option>
                    </select>
                </div>

                <!-- Sort Options -->
                {{-- <div class="sm:w-48">
                    <select name="sort_by"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md bg-white dark:bg-darker dark:border-primary-darker dark:text-white focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                        <option value="created_at"
                            {{ ($filters['sort_by'] ?? '') === 'created_at' ? 'selected' : '' }}>Terbaru</option>
                        <option value="nomor_kandang"
                            {{ ($filters['sort_by'] ?? '') === 'nomor_kandang' ? 'selected' : '' }}>Nomor Kandang
                        </option>
                        <option value="status" {{ ($filters['sort_by'] ?? '') === 'status' ? 'selected' : '' }}>Status
                        </option>
                    </select>
                </div> --}}

                <!-- Search Button -->
                <button type="submit"
                    class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 
           rounded-lg
           focus:outline-none focus:ring focus:ring-blue-600 
           focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark">
                    Cari
                </button>

            </form>
        </div>

        <!-- Kandang List -->
        <div class="px-4 py-6">
            @if ($kandangs->count() > 0)
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach ($kandangs as $kandang)
                        <div
                            class="bg-white dark:bg-darker rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                            <!-- Card Header -->
                            <div class="p-4 border-b dark:border-primary-darker">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-text-primary dark:text-white">
                                        {{ $kandang->nomor_kandang }}
                                    </h3>
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
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="p-4">
                                <div class="space-y-3">
                                    @if ($kandang->indukanJantan)
                                        <div class="flex items-center text-sm text-text-secondary dark:text-gray-400">
                                            <svg class="h-4 w-4 mr-2 text-blue-500" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span class="font-medium">Jantan:</span>
                                            @if ($kandang->indukanJantan)
                                                {{ $kandang->indukanJantan->nomor_ring }}
                                                <span class="text-gray-500 dark:text-gray-400">
                                                    ({{ $kandang->indukanJantan->nama }})
                                                </span>
                                            @else
                                                <span class="italic text-gray-400">Belum ditentukan</span>
                                            @endif

                                        </div>
                                    @endif

                                    @if ($kandang->indukanBetina)
                                        <div class="flex items-center text-sm text-text-secondary dark:text-gray-400">
                                            <svg class="h-4 w-4 mr-2 text-pink-500" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span class="font-medium">Betina:</span>
                                            @if ($kandang->indukanBetina)
                                                {{ $kandang->indukanBetina->nomor_ring }}
                                                <span class="text-gray-500 dark:text-gray-400">
                                                    ({{ $kandang->indukanBetina->nama }})
                                                </span>
                                            @else
                                                <span class="italic text-gray-400">Belum ditentukan</span>
                                            @endif

                                        </div>
                                    @endif

                                    <div class="mt-2">
                                        @if ($kandang->anakansAktif->count() > 0)
                                            <div
                                                class="flex items-center text-sm font-medium text-green-700 dark:text-green-300 bg-green-50 dark:bg-green-900/30 px-3 py-1.5 rounded-md">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 mr-2 text-green-600 dark:text-green-400"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                {{ $kandang->anakansAktif->count() }} anakan aktif dari pasangan saat
                                                ini
                                            </div>
                                        @else
                                            <div
                                                class="flex items-center text-sm text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800 px-3 py-1.5 rounded-md italic">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 mr-2 text-gray-400 dark:text-gray-500"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M9 13h6m2 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Belum ada anakan aktif dari pasangan ini
                                            </div>
                                        @endif
                                    </div>

                                </div>

                                @if ($kandang->deskripsi_kandang)
                                    <div class="mt-3">
                                        <p class="text-sm text-text-secondary dark:text-gray-400 line-clamp-2">
                                            {{ $kandang->deskripsi_kandang }}</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Card Footer -->
                            <div class="px-4 py-3 bg-gray-50 dark:bg-primary-darker rounded-b-lg">
                                <div class="flex space-x-2">
                                    <a href="{{ route('peternak.kandang.show', $kandang) }}"
                                        class="flex-1 text-center px-3 py-2 text-sm font-medium
                                         text-white bg-indigo-600 
                                        hover:bg-indigo-700
                                        dark:bg-indigo-500 dark:hover:bg-indigo-600
                                        rounded-md focus:outline-none">
                                        Detail
                                    </a>


                                    <a href="{{ route('peternak.kandang.edit', $kandang) }}"
                                        class="flex-1 text-center px-3 py-2 text-sm font-medium
                                        text-indigo-700 bg-indigo-300 
                                        hover:bg-indigo-200
                                        dark:text-indigo-100 dark:bg-indigo-700 
                                        dark:hover:bg-indigo-600
                                        rounded-md focus:outline-none">
                                        Edit
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $kandangs->appends(request()->query())->links() }}

                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-text-primary dark:text-white">Tidak ada kandang</h3>
                    <p class="mt-1 text-sm text-text-tertiary dark:text-gray-400">Mulai dengan menambahkan kandang
                        pertama Anda.</p>
                    <div class="mt-6">
                        <a href="{{ route('peternak.kandang.create') }}"
                            class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 
          rounded-lg
          focus:outline-none focus:ring focus:ring-blue-600 
          focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark">
                            Tambah Kandang
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </main>
</x-layout>
