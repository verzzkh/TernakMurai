<x-layout>
  <main>
      <!-- Content header -->
      <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
          <div>
              <h1 class="text-2xl font-semibold">{{ $indukan->nomor_ring }}</h1>
              @if($indukan->nama)
                  <p class="text-gray-600 dark:text-gray-400">{{ $indukan->nama }}</p>
              @endif
          </div>
          <div class="flex items-center space-x-3">
              <a href="{{ route('peternak.indukan.edit', $indukan) }}" 
                 class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring focus:ring-gray-500 focus:ring-offset-1">
                  Edit
              </a>
              <a href="{{ route('peternak.indukan.index') }}" 
                 class="px-4 py-2 text-white bg-primary rounded-md hover:bg-primary-dark focus:outline-none focus:ring focus:ring-primary focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark">
                  Kembali
              </a>
          </div>
      </div>

      <div class="px-4 py-6">
          <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
              <!-- Informasi Indukan -->
              <div class="lg:col-span-2">
                  <div class="bg-white dark:bg-darker rounded-lg shadow p-6">
                      <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Indukan</h2>
                      
                      <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                          <div>
                              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Ring</dt>
                              <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $indukan->nomor_ring }}</dd>
                          </div>

                          <div>
                              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Kelamin</dt>
                              <dd class="mt-1">
                                  <span class="px-2 py-1 text-xs font-medium rounded-full {{ $indukan->jenis_kelamin === 'jantan' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200' }}">
                                      {{ ucfirst($indukan->jenis_kelamin) }}
                                  </span>
                              </dd>
                          </div>

                          @if($indukan->nama)
                          <div>
                              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama</dt>
                              <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $indukan->nama }}</dd>
                          </div>
                          @endif

                          @if($indukan->tanggal_lahir)
                          <div>
                              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Lahir</dt>
                              <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                  {{ $indukan->tanggal_lahir->format('d M Y') }} ({{ $indukan->age['formatted'] }})
                              </dd>
                          </div>
                          @endif

                          <div>
                              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Anakan</dt>
                              <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $indukan->getAnakanCount() }} anakan</dd>
                          </div>
                      </div>

                      @if($indukan->catatan)
                      <div class="mt-6">
                          <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan</dt>
                          <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $indukan->catatan }}</dd>
                      </div>
                      @endif

                      @if($indukan->prestasi)
                      <div class="mt-6">
                          <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Prestasi</dt>
                          <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $indukan->prestasi }}</dd>
                      </div>
                      @endif

                      @if($indukan->karakteristik)
                      <div class="mt-6">
                          <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Karakteristik</dt>
                          <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $indukan->karakteristik }}</dd>
                      </div>
                      @endif
                  </div>

                  <!-- Anakan dari Indukan ini -->
                  <div class="mt-6 bg-white dark:bg-darker rounded-lg shadow p-6">
                      <div class="flex items-center justify-between mb-4">
                          <h2 class="text-lg font-medium text-gray-900 dark:text-white">Anakan dari Indukan ini</h2>
                          <a href="{{ route('peternak.anakan.create') }}?indukan_id={{ $indukan->id }}" 
                             class="px-3 py-1 text-sm text-white bg-primary rounded-md hover:bg-primary-dark focus:outline-none focus:ring focus:ring-primary focus:ring-offset-1">
                              Tambah Anakan
                          </a>
                      </div>

                      @if($indukan->anakans->count() > 0)
                          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                              @foreach($indukan->anakans as $anakan)
                                  <div class="border dark:border-primary-darker rounded-lg p-4">
                                      <div class="flex items-center justify-between">
                                          <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                                              {{ $anakan->nomor_ring ?? 'Belum ada ring' }}
                                          </h3>
                                          <span class="px-2 py-1 text-xs font-medium rounded-full {{ $anakan->jenis_kelamin === 'jantan' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : ($anakan->jenis_kelamin === 'betina' ? 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200') }}">
                                              {{ ucfirst(str_replace('_', ' ', $anakan->jenis_kelamin)) }}
                                          </span>
                                      </div>
                                      @if($anakan->tanggal_lahir)
                                          <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                              {{ $anakan->tanggal_lahir->format('d M Y') }} ({{ $anakan->age['formatted'] }})
                                          </p>
                                      @endif
                                      <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                          Status: {{ ucfirst(str_replace('_', ' ', $anakan->status_pertumbuhan)) }}
                                      </p>
                                      <div class="mt-2">
                                          <a href="{{ route('peternak.anakan.show', $anakan) }}" 
                                             class="text-xs text-primary hover:text-primary-dark">Lihat Detail</a>
                                      </div>
                                  </div>
                              @endforeach
                          </div>
                      @else
                          <div class="text-center py-8">
                              <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                              </svg>
                              <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada anakan</h3>
                              <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Indukan ini belum memiliki anakan.</p>
                          </div>
                      @endif
                  </div>
              </div>

              <!-- Sidebar -->
              <div class="lg:col-span-1">
                  <!-- Kandang yang menggunakan indukan ini -->
                  @if($indukan->kandangsJantan->count() > 0 || $indukan->kandangsBetina->count() > 0)
                  <div class="bg-white dark:bg-darker rounded-lg shadow p-6 mb-6">
                      <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Kandang</h3>
                      
                      @if($indukan->kandangsJantan->count() > 0)
                          <div class="mb-4">
                              <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sebagai Jantan</h4>
                              <div class="space-y-2">
                                  @foreach($indukan->kandangsJantan as $kandang)
                                      <div class="text-sm">
                                          <a href="{{ route('peternak.kandang.show', $kandang) }}" 
                                             class="text-primary hover:text-primary-dark">{{ $kandang->nomor_kandang }}</a>
                                          <span class="text-gray-500 dark:text-gray-400">({{ ucfirst($kandang->status) }})</span>
                                      </div>
                                  @endforeach
                              </div>
                          </div>
                      @endif

                      @if($indukan->kandangsBetina->count() > 0)
                          <div>
                              <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sebagai Betina</h4>
                              <div class="space-y-2">
                                  @foreach($indukan->kandangsBetina as $kandang)
                                      <div class="text-sm">
                                          <a href="{{ route('peternak.kandang.show', $kandang) }}" 
                                             class="text-primary hover:text-primary-dark">{{ $kandang->nomor_kandang }}</a>
                                          <span class="text-gray-500 dark:text-gray-400">({{ ucfirst($kandang->status) }})</span>
                                      </div>
                                  @endforeach
                              </div>
                          </div>
                      @endif
                  </div>
                  @endif

                  <!-- Statistik -->
                  <div class="bg-white dark:bg-darker rounded-lg shadow p-6">
                      <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Statistik</h3>
                      
                      <div class="space-y-4">
                          <div class="flex justify-between">
                              <span class="text-sm text-gray-500 dark:text-gray-400">Total Anakan</span>
                              <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $indukan->getAnakanCount() }}</span>
                          </div>
                          
                          <div class="flex justify-between">
                              <span class="text-sm text-gray-500 dark:text-gray-400">Anakan Jantan</span>
                              <span class="text-sm font-medium text-gray-900 dark:text-white">
                                  {{ $indukan->anakans->where('jenis_kelamin', 'jantan')->count() }}
                              </span>
                          </div>
                          
                          <div class="flex justify-between">
                              <span class="text-sm text-gray-500 dark:text-gray-400">Anakan Betina</span>
                              <span class="text-sm font-medium text-gray-900 dark:text-white">
                                  {{ $indukan->anakans->where('jenis_kelamin', 'betina')->count() }}
                              </span>
                          </div>
                          
                          <div class="flex justify-between">
                              <span class="text-sm text-gray-500 dark:text-gray-400">Anakan Aktif</span>
                              <span class="text-sm font-medium text-gray-900 dark:text-white">
                                  {{ $indukan->anakans->where('status_penjualan', 'belum_dijual')->count() }}
                              </span>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </main>
</x-layout>