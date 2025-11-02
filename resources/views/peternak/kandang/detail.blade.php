<x-layout>
  <main>
      <!-- Content header -->
      <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
          <div>
              <h1 class="text-2xl font-semibold">{{ $kandang->nomor_kandang }}</h1>
              <p class="text-gray-600 dark:text-gray-400">Kandang</p>
          </div>
          <div class="flex items-center space-x-3">
              <a href="{{ route('peternak.kandang.edit', $kandang) }}" 
                 class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring focus:ring-gray-500 focus:ring-offset-1">
                  Edit
              </a>
              <a href="{{ route('peternak.kandang.index') }}" 
                 class="px-4 py-2 text-white bg-primary rounded-md hover:bg-primary-dark focus:outline-none focus:ring focus:ring-primary focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark">
                  Kembali
              </a>
          </div>
      </div>

      <div class="px-4 py-6">
          <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
              <!-- Informasi Kandang -->
              <div class="lg:col-span-2">
                  <div class="bg-white dark:bg-darker rounded-lg shadow p-6">
                      <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Kandang</h2>
                      
                      <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                          <div>
                              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Kandang</dt>
                              <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $kandang->nomor_kandang }}</dd>
                          </div>

                          <div>
                              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                              <dd class="mt-1">
                                  <span class="px-2 py-1 text-xs font-medium rounded-full {{ 
                                      $kandang->status === 'kosong' ? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200' :
                                      ($kandang->status === 'bertelur' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                      ($kandang->status === 'mengeram' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' :
                                      'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'))
                                  }}">
                                      {{ ucfirst($kandang->status) }}
                                  </span>
                              </dd>
                          </div>

                          @if($kandang->indukanJantan)
                          <div>
                              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Indukan Jantan</dt>
                              <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                  <a href="{{ route('peternak.indukan.show', $kandang->indukanJantan) }}" 
                                     class="text-primary hover:text-primary-dark">
                                      {{ $kandang->indukanJantan->nomor_ring }}
                                      @if($kandang->indukanJantan->nama) - {{ $kandang->indukanJantan->nama }} @endif
                                  </a>
                              </dd>
                          </div>
                          @endif

                          @if($kandang->indukanBetina)
                          <div>
                              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Indukan Betina</dt>
                              <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                  <a href="{{ route('peternak.indukan.show', $kandang->indukanBetina) }}" 
                                     class="text-primary hover:text-primary-dark">
                                      {{ $kandang->indukanBetina->nomor_ring }}
                                      @if($kandang->indukanBetina->nama) - {{ $kandang->indukanBetina->nama }} @endif
                                  </a>
                              </dd>
                          </div>
                          @endif

                          <div>
                              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Anakan</dt>
                              <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $kandang->anakans->count() }} anakan</dd>
                          </div>
                      </div>

                      @if($kandang->deskripsi_kandang)
                      <div class="mt-6">
                          <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Deskripsi</dt>
                          <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $kandang->deskripsi_kandang }}</dd>
                      </div>
                      @endif
                  </div>

                  <!-- Anakan di Kandang ini -->
                  <div class="mt-6 bg-white dark:bg-darker rounded-lg shadow p-6">
                      <div class="flex items-center justify-between mb-4">
                          <h2 class="text-lg font-medium text-gray-900 dark:text-white">Anakan di Kandang ini</h2>
                          <a href="{{ route('peternak.anakan.create') }}?kandang_id={{ $kandang->id }}" 
                             class="px-3 py-1 text-sm text-white bg-primary rounded-md hover:bg-primary-dark focus:outline-none focus:ring focus:ring-primary focus:ring-offset-1">
                              Tambah Anakan
                          </a>
                      </div>

                      @if($kandang->anakans->count() > 0)
                          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                              @foreach($kandang->anakans as $anakan)
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
                              <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kandang ini belum memiliki anakan.</p>
                          </div>
                      @endif
                  </div>

                  <!-- Riwayat Perkawinan -->
                  @if($kandang->perkawinans->count() > 0)
                  <div class="mt-6 bg-white dark:bg-darker rounded-lg shadow p-6">
                      <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Riwayat Perkawinan</h2>
                      
                      <div class="space-y-4">
                          @foreach($kandang->perkawinans as $perkawinan)
                              <div class="border dark:border-primary-darker rounded-lg p-4">
                                  <div class="flex items-center justify-between">
                                      <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                                          Trip #{{ $perkawinan->nomor_trip }}
                                      </h3>
                                      @if($perkawinan->tanggal_kawin)
                                          <span class="text-xs text-gray-500 dark:text-gray-400">
                                              {{ $perkawinan->tanggal_kawin->format('d M Y') }}
                                          </span>
                                      @endif
                                  </div>
                                  
                                  <div class="mt-2 grid grid-cols-1 gap-2 sm:grid-cols-2">
                                      @if($perkawinan->indukanJantan)
                                          <div class="text-sm text-gray-600 dark:text-gray-400">
                                              <span class="font-medium">Jantan:</span> {{ $perkawinan->indukanJantan->nomor_ring }}
                                          </div>
                                      @endif
                                      
                                      @if($perkawinan->indukanBetina)
                                          <div class="text-sm text-gray-600 dark:text-gray-400">
                                              <span class="font-medium">Betina:</span> {{ $perkawinan->indukanBetina->nomor_ring }}
                                          </div>
                                      @endif
                                      
                                      <div class="text-sm text-gray-600 dark:text-gray-400">
                                          <span class="font-medium">Anakan:</span> {{ $perkawinan->getAnakanCount() }} ekor
                                      </div>
                                  </div>
                                  
                                  @if($perkawinan->catatan)
                                      <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                          <span class="font-medium">Catatan:</span> {{ $perkawinan->catatan }}
                                      </div>
                                  @endif
                              </div>
                          @endforeach
                      </div>
                  </div>
                  @endif
              </div>

              <!-- Sidebar -->
              <div class="lg:col-span-1">
                  <!-- Statistik -->
                  <div class="bg-white dark:bg-darker rounded-lg shadow p-6">
                      <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Statistik</h3>
                      
                      <div class="space-y-4">
                          <div class="flex justify-between">
                              <span class="text-sm text-gray-500 dark:text-gray-400">Total Anakan</span>
                              <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $kandang->anakans->count() }}</span>
                          </div>
                          
                          <div class="flex justify-between">
                              <span class="text-sm text-gray-500 dark:text-gray-400">Anakan Jantan</span>
                              <span class="text-sm font-medium text-gray-900 dark:text-white">
                                  {{ $kandang->anakans->where('jenis_kelamin', 'jantan')->count() }}
                              </span>
                          </div>
                          
                          <div class="flex justify-between">
                              <span class="text-sm text-gray-500 dark:text-gray-400">Anakan Betina</span>
                              <span class="text-sm font-medium text-gray-900 dark:text-white">
                                  {{ $kandang->anakans->where('jenis_kelamin', 'betina')->count() }}
                              </span>
                          </div>
                          
                          <div class="flex justify-between">
                              <span class="text-sm text-gray-500 dark:text-gray-400">Anakan Aktif</span>
                              <span class="text-sm font-medium text-gray-900 dark:text-white">
                                  {{ $kandang->anakans->where('status_penjualan', 'belum_dijual')->count() }}
                              </span>
                          </div>

                          <div class="flex justify-between">
                              <span class="text-sm text-gray-500 dark:text-gray-400">Total Perkawinan</span>
                              <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $kandang->perkawinans->count() }}</span>
                          </div>
                      </div>
                  </div>

                  <!-- Status Actions -->
                  <div class="mt-6 bg-white dark:bg-darker rounded-lg shadow p-6">
                      <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Ubah Status</h3>
                      
                      <form action="{{ route('peternak.kandang.update', $kandang) }}" method="POST" class="space-y-3">
                          @csrf
                          @method('PUT')
                          
                          <div>
                              <select name="status" class="block w-full px-3 py-2 border border-gray-300 rounded-md bg-white dark:bg-darker dark:border-primary-darker dark:text-white focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                                  <option value="kosong" {{ $kandang->status === 'kosong' ? 'selected' : '' }}>Kosong</option>
                                  <option value="bertelur" {{ $kandang->status === 'bertelur' ? 'selected' : '' }}>Bertelur</option>
                                  <option value="mengeram" {{ $kandang->status === 'mengeram' ? 'selected' : '' }}>Mengeram</option>
                                  <option value="menetas" {{ $kandang->status === 'menetas' ? 'selected' : '' }}>Menetas</option>
                              </select>
                          </div>
                          
                          <button type="submit" class="w-full px-3 py-2 text-sm text-white bg-primary rounded-md hover:bg-primary-dark focus:outline-none focus:ring focus:ring-primary focus:ring-offset-1">
                              Update Status
                          </button>
                      </form>
                  </div>
              </div>
          </div>
      </div>
  </main>
</x-layout>