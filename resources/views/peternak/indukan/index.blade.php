<x-layout>
    <main>
        <!-- Content header -->
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <h1 class="text-2xl font-semibold">Manajemen Indukan</h1>
            <div class="flex items-center space-x-4">
                <a href="{{ route('peternak.indukan.create') }}"
                    class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 
          rounded-lg
          focus:outline-none focus:ring focus:ring-blue-600 
          focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark">
                    Tambah Indukan
                </a>

            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="px-4 py-6">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Total Indukan -->
                <div class="bg-white dark:bg-darker rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-text-tertiary dark:text-gray-400">Total Indukan</p>
                            <p class="text-2xl font-semibold text-text-primary dark:text-white">{{ $stats['total'] }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Indukan Jantan -->
                <div class="bg-white dark:bg-darker rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-text-tertiary dark:text-gray-400">Jantan</p>
                            <p class="text-2xl font-semibold text-text-primary dark:text-white">{{ $stats['jantan'] }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Indukan Betina -->
                <div class="bg-white dark:bg-darker rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-pink-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-text-tertiary dark:text-gray-400">Betina</p>
                            <p class="text-2xl font-semibold text-text-primary dark:text-white">{{ $stats['betina'] }}
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
                            placeholder="Cari nomor ring atau nama indukan..."
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white dark:bg-darker dark:border-primary-darker dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm">
                    </div>
                </div>

                <!-- Jenis Kelamin Filter -->
                <div class="sm:w-48">
                    <select name="jenis_kelamin"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md bg-white dark:bg-darker dark:border-primary-darker dark:text-white focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                        <option value="">Semua Jenis Kelamin</option>
                        <option value="jantan" {{ ($filters['jenis_kelamin'] ?? '') === 'jantan' ? 'selected' : '' }}>
                            Jantan</option>
                        <option value="betina" {{ ($filters['jenis_kelamin'] ?? '') === 'betina' ? 'selected' : '' }}>
                            Betina</option>
                    </select>
                </div>

                <!-- Sort Options -->
                <div class="sm:w-48">
                    <select name="sort_by"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md bg-white dark:bg-darker dark:border-primary-darker dark:text-white focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                        <option value="created_at" {{ ($filters['sort_by'] ?? '') === 'created_at' ? 'selected' : '' }}>
                            Terbaru</option>
                        <option value="nomor_ring" {{ ($filters['sort_by'] ?? '') === 'nomor_ring' ? 'selected' : '' }}>
                            Nomor Ring</option>
                        <option value="nama" {{ ($filters['sort_by'] ?? '') === 'nama' ? 'selected' : '' }}>Nama
                        </option>
                        <option value="tanggal_lahir"
                            {{ ($filters['sort_by'] ?? '') === 'tanggal_lahir' ? 'selected' : '' }}>Tanggal Lahir
                        </option>
                    </select>
                </div>

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

        <!-- Indukan List -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 p-4">
            @forelse($indukans as $indukan)
                <div class="bg-white rounded-md shadow-md overflow-hidden dark:bg-darker cursor-pointer hover:shadow-lg transition duration-300"
                    onclick="window.location.href = '{{ route('peternak.indukan.show', $indukan->id) }}'">

                    <!-- FOTO -->
                    <div class="h-40 bg-gray-200 dark:bg-gray-700 relative overflow-hidden">
                        <img src="{{ $indukan->foto_url }}" alt="Foto Indukan" class="w-full h-full object-cover">

                        <!-- Badge jenis kelamin -->
                        <div
                            class="absolute top-2 right-2 
                    {{ $indukan->isJantan() ? 'bg-blue-600' : 'bg-pink-600' }} 
                    text-white px-2 py-1 rounded-md text-xs">
                            {{ ucfirst($indukan->jenis_kelamin) }}
                        </div>
                    </div>

                    <!-- INFORMASI -->
                    <div class="p-4">
                        <h3 class="text-lg font-semibold">
                            {{ $indukan->nomor_ring }}
                        </h3>

                        @if ($indukan->nama)
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                {{ $indukan->nama }}
                            </p>
                        @endif

                        <div class="flex justify-between mt-3 text-sm text-text-secondary dark:text-gray-300">
                            <p><span class="font-medium">Umur:</span> {{ $indukan->age['formatted'] }}</p>
                            <p><span class="font-medium">Anakan:</span> {{ $indukan->anakans->count() }}</p>
                        </div>

                        <!-- ACTION BUTTONS -->
                        <div class="mt-4 flex justify-between">
                            <!-- DETAIL (Primary Button) -->
                            <a href="{{ route('peternak.indukan.show', $indukan->id) }}"
                                class="px-2 py-1 text-xs font-medium
               text-white bg-indigo-600 
               hover:bg-indigo-700
               dark:bg-indigo-500 dark:hover:bg-indigo-600
               rounded">
                                Detail
                            </a>

                            <div class="flex space-x-2">
                                <!-- EDIT (Secondary Button) -->
                                <a href="{{ route('peternak.indukan.edit', $indukan->id) }}"
                                    onclick="event.stopPropagation()"
                                    class="px-2 py-1 text-xs font-medium
                   text-indigo-700 bg-indigo-300
                   hover:bg-indigo-400
                   dark:text-indigo-100 dark:bg-indigo-700 
                   dark:hover:bg-indigo-600
                   rounded">
                                    Edit
                                </a>

                             <form id="deleteIndukanForm-{{ $indukan->id }}"
      action="{{ route('peternak.indukan.destroy', $indukan->id) }}"
      method="POST">
    @csrf
    @method('DELETE')

    <button type="button"
        onclick="event.stopPropagation(); confirmDeleteIndukan({{ $indukan->id }})"
        class="px-2 py-1 bg-red-600 text-white text-xs rounded">
        Hapus
    </button>
</form>




                            </div>
                        </div>


                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-text-primary dark:text-gray-100">Tidak ada data indukan
                    </h3>
                    <p class="mt-1 text-sm text-text-tertiary dark:text-gray-400">Mulai dengan menambahkan indukan
                        baru.</p>
                    <div class="mt-6">
                        <a href="{{ route('peternak.indukan.create') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-primary-dark">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Indukan
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="px-4 py-6">
            {{ $indukans->appends(request()->query())->links() }}

        </div>

    </main>
    {{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmDeleteIndukan(id) {
    Swal.fire({
        title: "Hapus indukan?",
        text: "Data tidak dapat dikembalikan.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus",
        cancelButtonText: "Batal",
    }).then(result => {
        if (result.isConfirmed) {
            document.getElementById("deleteIndukanForm-" + id).submit();
        }
    });
}
</script>

</x-layout>
