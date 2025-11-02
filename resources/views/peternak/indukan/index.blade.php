<x-layout>
  <main>
      <!-- Content header -->
      <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
          <h1 class="text-2xl font-semibold">Manajemen Indukan</h1>
          <div class="flex items-center space-x-4">
              <a href="{{ route('peternak.indukan.create') }}" 
                 class="px-4 py-2 text-white bg-primary rounded-md hover:bg-primary-dark focus:outline-none focus:ring focus:ring-primary focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark">
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
                          <svg class="h-8 w-8 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                          </svg>
                      </div>
                      <div class="ml-4">
                          <p class="text-sm font-medium text-text-tertiary dark:text-gray-400">Total Indukan</p>
                          <p class="text-2xl font-semibold text-text-primary dark:text-white">{{ $stats['total'] }}</p>
                      </div>
                  </div>
              </div>

              <!-- Indukan Jantan -->
              <div class="bg-white dark:bg-darker rounded-lg shadow p-6">
                  <div class="flex items-center">
                      <div class="flex-shrink-0">
                          <svg class="h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                          </svg>
                      </div>
                      <div class="ml-4">
                          <p class="text-sm font-medium text-text-tertiary dark:text-gray-400">Jantan</p>
                          <p class="text-2xl font-semibold text-text-primary dark:text-white">{{ $stats['jantan'] }}</p>
                      </div>
                  </div>
              </div>

              <!-- Indukan Betina -->
              <div class="bg-white dark:bg-darker rounded-lg shadow p-6">
                  <div class="flex items-center">
                      <div class="flex-shrink-0">
                          <svg class="h-8 w-8 text-pink-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                          </svg>
                      </div>
                      <div class="ml-4">
                          <p class="text-sm font-medium text-text-tertiary dark:text-gray-400">Betina</p>
                          <p class="text-2xl font-semibold text-text-primary dark:text-white">{{ $stats['betina'] }}</p>
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
                          <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                          </svg>
                      </div>
                      <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" 
                             placeholder="Cari nomor ring atau nama indukan..."
                             class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white dark:bg-darker dark:border-primary-darker dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm">
                  </div>
              </div>

              <!-- Jenis Kelamin Filter -->
              <div class="sm:w-48">
                  <select name="jenis_kelamin" class="block w-full px-3 py-2 border border-gray-300 rounded-md bg-white dark:bg-darker dark:border-primary-darker dark:text-white focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                      <option value="">Semua Jenis Kelamin</option>
                      <option value="jantan" {{ ($filters['jenis_kelamin'] ?? '') === 'jantan' ? 'selected' : '' }}>Jantan</option>
                      <option value="betina" {{ ($filters['jenis_kelamin'] ?? '') === 'betina' ? 'selected' : '' }}>Betina</option>
                  </select>
              </div>

              <!-- Sort Options -->
              <div class="sm:w-48">
                  <select name="sort_by" class="block w-full px-3 py-2 border border-gray-300 rounded-md bg-white dark:bg-darker dark:border-primary-darker dark:text-white focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                      <option value="created_at" {{ ($filters['sort_by'] ?? '') === 'created_at' ? 'selected' : '' }}>Terbaru</option>
                      <option value="nomor_ring" {{ ($filters['sort_by'] ?? '') === 'nomor_ring' ? 'selected' : '' }}>Nomor Ring</option>
                      <option value="nama" {{ ($filters['sort_by'] ?? '') === 'nama' ? 'selected' : '' }}>Nama</option>
                      <option value="tanggal_lahir" {{ ($filters['sort_by'] ?? '') === 'tanggal_lahir' ? 'selected' : '' }}>Tanggal Lahir</option>
                  </select>
              </div>

              <!-- Sort Direction -->
              <div class="sm:w-32">
                  <select name="sort_direction" class="block w-full px-3 py-2 border border-gray-300 rounded-md bg-white dark:bg-darker dark:border-primary-darker dark:text-white focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                      <option value="desc" {{ ($filters['sort_direction'] ?? '') === 'desc' ? 'selected' : '' }}>Desc</option>
                      <option value="asc" {{ ($filters['sort_direction'] ?? '') === 'asc' ? 'selected' : '' }}>Asc</option>
                  </select>
              </div>

              <!-- Search Button -->
              <button type="submit" class="px-4 py-2 text-white bg-primary rounded-md hover:bg-primary-dark focus:outline-none focus:ring focus:ring-primary focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark">
                  Cari
              </button>
          </form>
      </div>

      <!-- Indukan List -->
      <div class="px-4 py-6">
          @if($indukans->count() > 0)
              <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                  @foreach($indukans as $indukan)
                      <div class="bg-white dark:bg-darker rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                          <!-- Card Header -->
                          <div class="p-4 border-b dark:border-primary-darker">
                              <div class="flex items-center justify-between">
                                  <h3 class="text-lg font-semibold text-text-primary dark:text-white">
                                      {{ $indukan->nomor_ring }}
                                  </h3>
                                  <span class="px-2 py-1 text-xs font-medium rounded-full {{ $indukan->jenis_kelamin === 'jantan' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200' }}">
                                      {{ ucfirst($indukan->jenis_kelamin) }}
                                  </span>
                              </div>
                              @if($indukan->nama)
                                  <p class="text-sm text-text-secondary dark:text-gray-400 mt-1">{{ $indukan->nama }}</p>
                              @endif
                          </div>

                          <!-- Card Body -->
                          <div class="p-4">
                              <div class="space-y-2">
                                  @if($indukan->tanggal_lahir)
                                      <div class="flex items-center text-sm text-text-secondary dark:text-gray-400">
                                          <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                          </svg>
                                          {{ $indukan->tanggal_lahir->format('d M Y') }} ({{ $indukan->age['formatted'] }})
                                      </div>
                                  @endif

                                  <div class="flex items-center text-sm text-text-secondary dark:text-gray-400">
                                      <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                      </svg>
                                      {{ $indukan->getAnakanCount() }} anakan
                                  </div>
                              </div>
                          </div>

                          <!-- Card Footer -->
                          <div class="px-4 py-3 bg-gray-50 dark:bg-primary-darker rounded-b-lg">
                              <div class="flex space-x-2">
                                  <a href="{{ route('peternak.indukan.show', $indukan) }}" 
                                     class="flex-1 text-center px-3 py-2 text-sm font-medium text-primary bg-primary-100 dark:bg-primary dark:text-primary-100 rounded-md hover:bg-primary-200 dark:hover:bg-primary-dark focus:outline-none focus:ring focus:ring-primary focus:ring-offset-1">
                                      Detail
                                  </a>
                                  <a href="{{ route('peternak.indukan.edit', $indukan) }}" 
                                     class="flex-1 text-center px-3 py-2 text-sm font-medium text-text-primary dark:text-gray-300 bg-gray-200 dark:bg-gray-700 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring focus:ring-gray-500 focus:ring-offset-1">
                                      Edit
                                  </a>
                              </div>
                          </div>
                      </div>
                  @endforeach
              </div>

              <!-- Pagination -->
              <div class="mt-6">
                  {{ $indukans->links() }}
              </div>
          @else
              <!-- Empty State -->
              <div class="text-center py-12">
                  <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                  <h3 class="mt-2 text-sm font-medium text-text-primary dark:text-white">Tidak ada indukan</h3>
                  <p class="mt-1 text-sm text-text-tertiary dark:text-gray-400">Mulai dengan menambahkan indukan pertama Anda.</p>
                  <div class="mt-6">
                      <a href="{{ route('peternak.indukan.create') }}" 
                         class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                          <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                          </svg>
                          Tambah Indukan
                      </a>
                  </div>
              </div>
          @endif
      </div>
  </main>
</x-layout>