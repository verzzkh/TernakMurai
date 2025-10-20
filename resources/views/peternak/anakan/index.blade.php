<x-layout>
  <main>
      <!-- Content header -->
      <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
          <h1 class="text-2xl font-semibold">Manajemen Anakan</h1>
          <div class="flex items-center space-x-4">
              @if(!$peternak->isPro())
                  <div class="text-sm text-gray-600 dark:text-gray-400">
                      Anakan: {{ $peternak->getActiveAnakanCount() }}/20
                  </div>
              @endif
              <a href="{{ route('peternak.anakan.create') }}" 
                 class="px-4 py-2 text-white bg-primary rounded-md hover:bg-primary-dark focus:outline-none focus:ring focus:ring-primary focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark">
                  Tambah Anakan
              </a>
          </div>
      </div>

      <!-- Account limit warning for free accounts -->
      @if(!$peternak->isPro() && $peternak->getActiveAnakanCount() >= 18)
          <div class="mx-4 mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-md dark:bg-yellow-900/20 dark:border-yellow-800">
              <div class="flex">
                  <div class="flex-shrink-0">
                      <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                      </svg>
                  </div>
                  <div class="ml-3">
                      <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                          Batas Anakan Mendekati Limit
                      </h3>
                      <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                          <p>Anda memiliki {{ $peternak->getActiveAnakanCount() }} anakan aktif dari 20 anakan yang diizinkan untuk akun gratis. 
                          <a href="#" class="font-medium underline text-yellow-800 dark:text-yellow-200">Upgrade ke Pro</a> untuk menambah anakan tanpa batas.</p>
                      </div>
                  </div>
              </div>
          </div>
      @endif

      <!-- Tab Navigation -->
      <div class="px-4 border-b dark:border-primary-darker">
          <div class="flex space-x-8">
              <button id="tab-jantan" class="py-4 text-primary border-b-2 border-primary dark:text-primary-light dark:border-primary-light font-medium">
                  Anakan Jantan ({{ $anakans->where('jenis_kelamin', 'jantan')->count() }})
              </button>
              <button id="tab-betina" class="py-4 text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-primary-light font-medium">
                  Anakan Betina ({{ $anakans->where('jenis_kelamin', 'betina')->count() }})
              </button>
          </div>
      </div>

      <!-- Content -->
      <div class="mt-2">
          <!-- Search and filter section -->
          <form method="GET" class="flex flex-col md:flex-row items-center justify-between p-4 space-y-3 md:space-y-0">
              <div class="w-full md:w-1/3">
                  <div class="relative">
                      <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari anakan..." class="w-full pl-10 pr-4 py-2 rounded-md border dark:border-primary-darker dark:bg-darker focus:outline-none focus:ring focus:ring-primary-light">
                      <span class="absolute left-3 top-2 text-gray-400">
                          <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                          </svg>
                      </span>
                  </div>
              </div>
              <div class="flex space-x-3">
                  <select name="status_pertumbuhan" class="px-4 py-2 rounded-md border dark:border-primary-darker dark:bg-darker focus:outline-none focus:ring focus:ring-primary-light">
                      <option value="">Semua Status</option>
                      <option value="trotol" {{ request('status_pertumbuhan') == 'trotol' ? 'selected' : '' }}>Trotol</option>
                      <option value="pastol" {{ request('status_pertumbuhan') == 'pastol' ? 'selected' : '' }}>Pastol</option>
                      <option value="lomba" {{ request('status_pertumbuhan') == 'lomba' ? 'selected' : '' }}>Lomba</option>
                  </select>
                  <select name="status_penjualan" class="px-4 py-2 rounded-md border dark:border-primary-darker dark:bg-darker focus:outline-none focus:ring focus:ring-primary-light">
                      <option value="">Semua</option>
                      <option value="belum_dijual" {{ request('status_penjualan') == 'belum_dijual' ? 'selected' : '' }}>Aktif</option>
                      <option value="terjual" {{ request('status_penjualan') == 'terjual' ? 'selected' : '' }}>Terjual</option>
                  </select>
                  <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark">
                      Filter
                  </button>
              </div>
          </form>

          <!-- Anakan cards grid -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 p-4">
              @forelse($anakans as $anakan)
                  <div class="bg-white rounded-md shadow-md overflow-hidden dark:bg-darker cursor-pointer hover:shadow-lg transition duration-300" 
                       onclick="window.location.href = '{{ route('peternak.anakan.show', $anakan->id) }}'">
                      <div class="h-40 bg-gray-200 dark:bg-gray-700 relative overflow-hidden">
                          @if($anakan->foto_path)
                              <img src="{{ Storage::url($anakan->foto_path) }}" alt="Anakan" class="w-full h-full object-cover">
                          @else
                              <div class="w-full h-full flex items-center justify-center">
                                  <svg class="w-16 h-16 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                  </svg>
                              </div>
                          @endif
                          <div class="absolute top-2 right-2 {{ $anakan->status_pertumbuhan == 'trotol' ? 'bg-blue-500' : ($anakan->status_pertumbuhan == 'pastol' ? 'bg-purple-500' : 'bg-green-500') }} text-white px-2 py-1 rounded-md text-xs">
                              {{ ucfirst($anakan->status_pertumbuhan) }}
                          </div>
                          @if($anakan->status_penjualan === 'terjual')
                              <div class="absolute bottom-0 left-0 right-0 bg-gray-800 bg-opacity-75 text-white text-center py-1 text-sm">
                                  Terjual
                              </div>
                          @endif
                      </div>
                      <div class="p-4">
                          <h3 class="text-lg font-semibold">{{ $anakan->nomor_ring }}</h3>
                          <div class="flex justify-between mt-2">
                              <p class="text-gray-600 dark:text-gray-300">
                                  <span class="font-medium">Umur:</span> {{ $anakan->age['formatted'] }}
                              </p>
                              <p class="text-gray-600 dark:text-gray-300">
                                  <span class="font-medium">Harga:</span> Rp {{ number_format($anakan->harga ?? 0, 0, ',', '.') }}
                              </p>
                          </div>
                          @if($anakan->perkawinan && $anakan->perkawinan->indukanJantan)
                              <p class="text-gray-600 dark:text-gray-300 mt-1">
                                  <span class="font-medium">Jantan:</span> {{ $anakan->perkawinan->indukanJantan->nomor_ring }}
                              </p>
                          @endif
                          
                          <div class="mt-3 flex justify-between">
                              @if($anakan->status_penjualan === 'belum_dijual')
                                  <div class="flex space-x-2">
                                      <button class="px-2 py-1 text-xs bg-gray-200 text-gray-700 rounded hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600" 
                                              onclick="event.stopPropagation(); editPrice({{ $anakan->id }})">
                                          Edit Harga
                                      </button>
                                      <button class="px-2 py-1 text-xs bg-primary-100 text-primary-dark rounded hover:bg-primary-200 dark:bg-primary dark:text-primary-100 dark:hover:bg-primary-dark"
                                              onclick="event.stopPropagation(); updateStatus({{ $anakan->id }})">
                                          Update Status
                                      </button>
                                  </div>
                                  <button class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded hover:bg-green-200 dark:bg-green-800 dark:text-green-100 dark:hover:bg-green-700"
                                          onclick="event.stopPropagation(); sellAnakan({{ $anakan->id }})">
                                      Jual
                                  </button>
                              @else
                                  <span class="text-xs text-gray-500 dark:text-gray-400">
                                      Terjual pada {{ $anakan->tanggal_jual ? $anakan->tanggal_jual->format('d/m/Y') : '' }}
                                  </span>
                              @endif
                          </div>
                      </div>
                  </div>
              @empty
                  <div class="col-span-full text-center py-12">
                      <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                      <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Tidak ada anakan</h3>
                      <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai dengan menambahkan anakan baru.</p>
                      <div class="mt-6">
                          <a href="{{ route('peternak.anakan.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-primary-dark">
                              <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                              </svg>
                              Tambah Anakan
                          </a>
                      </div>
                  </div>
              @endforelse
          </div>

          <!-- Pagination -->
          @if($anakans->hasPages())
              <div class="px-4 py-3">
                  {{ $anakans->links() }}
              </div>
          @endif
      </div>
  </main>

  <!-- JavaScript for AJAX operations -->
  <script>
      // Edit price function
      function editPrice(anakanId) {
          const newPrice = prompt('Masukkan harga baru:');
          if (newPrice && !isNaN(newPrice)) {
              fetch(`/peternak/anakan/${anakanId}`, {
                  method: 'PUT',
                  headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                  },
                  body: JSON.stringify({
                      jenis_kelamin: 'jantan', // Keep existing
                      deskripsi_karakteristik: '', // Keep existing
                      harga: parseInt(newPrice)
                  })
              })
              .then(response => response.json())
              .then(data => {
                  if (data.success) {
                      alert('Harga berhasil diperbarui');
                      location.reload();
                  } else {
                      alert('Gagal memperbarui harga: ' + data.message);
                  }
              })
              .catch(error => {
                  console.error('Error:', error);
                  alert('Terjadi kesalahan saat memperbarui harga');
              });
          }
      }

      // Update status function
      function updateStatus(anakanId) {
          const newStatus = prompt('Pilih status baru (trotol/pastol/lomba):');
          const newPrice = prompt('Masukkan harga baru:');
          
          if (newStatus && newPrice && !isNaN(newPrice)) {
              fetch(`/peternak/anakan/${anakanId}/status`, {
                  method: 'PUT',
                  headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                  },
                  body: JSON.stringify({
                      status_pertumbuhan: newStatus,
                      harga: parseInt(newPrice),
                      catatan_perubahan: ''
                  })
              })
              .then(response => response.json())
              .then(data => {
                  if (data.success) {
                      alert('Status berhasil diperbarui');
                      location.reload();
                  } else {
                      alert('Gagal memperbarui status: ' + data.message);
                  }
              })
              .catch(error => {
                  console.error('Error:', error);
                  alert('Terjadi kesalahan saat memperbarui status');
              });
          }
      }

      // Sell anakan function
      function sellAnakan(anakanId) {
          const hargaJual = prompt('Masukkan harga jual:');
          const tanggalJual = prompt('Masukkan tanggal jual (YYYY-MM-DD):');
          const catatan = prompt('Catatan penjualan (opsional):');
          
          if (hargaJual && tanggalJual && !isNaN(hargaJual)) {
              fetch(`/peternak/anakan/${anakanId}/sell`, {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                  },
                  body: JSON.stringify({
                      harga_jual: parseInt(hargaJual),
                      tanggal_jual: tanggalJual,
                      catatan_penjualan: catatan || ''
                  })
              })
              .then(response => {
                  if (response.ok) {
                      alert('Anakan berhasil dijual dan transaksi telah dicatat');
                      location.reload();
                  } else {
                      return response.json().then(data => {
                          alert('Gagal menjual anakan: ' + (data.message || 'Terjadi kesalahan'));
                      });
                  }
              })
              .catch(error => {
                  console.error('Error:', error);
                  alert('Terjadi kesalahan saat menjual anakan');
              });
          }
      }
  </script>
</x-layout>