<x-layout>
  <main>
      <!-- Content header -->
      <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
          <h1 class="text-2xl font-semibold">Edit Indukan - {{ $indukan->nomor_ring }}</h1>
          <div class="flex items-center space-x-3">
              <a href="{{ route('peternak.indukan.show', $indukan) }}" 
                 class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring focus:ring-gray-500 focus:ring-offset-1">
                  Batal
              </a>
              <a href="{{ route('peternak.indukan.index') }}" 
                 class="px-4 py-2 text-white bg-primary rounded-md hover:bg-primary-dark focus:outline-none focus:ring focus:ring-primary focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark">
                  Kembali
              </a>
          </div>
      </div>

      <!-- Form -->
      <div class="px-4 py-6">
          <div class="max-w-2xl mx-auto">
              <form action="{{ route('peternak.indukan.update', $indukan) }}" method="POST" class="space-y-6">
                  @csrf
                  @method('PUT')
                  
                  <div class="bg-white dark:bg-darker rounded-lg shadow p-6">
                      <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Dasar</h2>
                      
                      <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                          <!-- Nomor Ring -->
                          <div class="sm:col-span-2">
                              <label for="nomor_ring" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                  Nomor Ring <span class="text-red-500">*</span>
                              </label>
                              <input type="text" name="nomor_ring" id="nomor_ring" value="{{ old('nomor_ring', $indukan->nomor_ring) }}" required
                                     class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-darker dark:border-primary-darker dark:text-white sm:text-sm @error('nomor_ring') border-red-300 @enderror">
                              @error('nomor_ring')
                                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                              @enderror
                          </div>

                          <!-- Nama -->
                          <div class="sm:col-span-2">
                              <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                  Nama (Opsional)
                              </label>
                              <input type="text" name="nama" id="nama" value="{{ old('nama', $indukan->nama) }}"
                                     class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-darker dark:border-primary-darker dark:text-white sm:text-sm @error('nama') border-red-300 @enderror">
                              @error('nama')
                                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                              @enderror
                          </div>

                          <!-- Jenis Kelamin -->
                          <div>
                              <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                  Jenis Kelamin <span class="text-red-500">*</span>
                              </label>
                              <select name="jenis_kelamin" id="jenis_kelamin" required
                                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-darker dark:border-primary-darker dark:text-white sm:text-sm @error('jenis_kelamin') border-red-300 @enderror">
                                  <option value="">Pilih Jenis Kelamin</option>
                                  <option value="jantan" {{ old('jenis_kelamin', $indukan->jenis_kelamin) === 'jantan' ? 'selected' : '' }}>Jantan</option>
                                  <option value="betina" {{ old('jenis_kelamin', $indukan->jenis_kelamin) === 'betina' ? 'selected' : '' }}>Betina</option>
                              </select>
                              @error('jenis_kelamin')
                                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                              @enderror
                          </div>

                          <!-- Tanggal Lahir -->
                          <div>
                              <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                  Tanggal Lahir
                              </label>
                              <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $indukan->tanggal_lahir?->format('Y-m-d')) }}"
                                     max="{{ date('Y-m-d') }}"
                                     class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-darker dark:border-primary-darker dark:text-white sm:text-sm @error('tanggal_lahir') border-red-300 @enderror">
                              @error('tanggal_lahir')
                                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                              @enderror
                          </div>
                      </div>
                  </div>

                  <!-- Informasi Tambahan -->
                  <div class="bg-white dark:bg-darker rounded-lg shadow p-6">
                      <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Tambahan</h2>
                      
                      <div class="space-y-6">
                          <!-- Catatan -->
                          <div>
                              <label for="catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                  Catatan
                              </label>
                              <textarea name="catatan" id="catatan" rows="3"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-darker dark:border-primary-darker dark:text-white sm:text-sm @error('catatan') border-red-300 @enderror">{{ old('catatan', $indukan->catatan) }}</textarea>
                              @error('catatan')
                                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                              @enderror
                          </div>

                          <!-- Prestasi -->
                          <div>
                              <label for="prestasi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                  Prestasi
                              </label>
                              <textarea name="prestasi" id="prestasi" rows="3"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-darker dark:border-primary-darker dark:text-white sm:text-sm @error('prestasi') border-red-300 @enderror">{{ old('prestasi', $indukan->prestasi) }}</textarea>
                              @error('prestasi')
                                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                              @enderror
                          </div>

                          <!-- Karakteristik -->
                          <div>
                              <label for="karakteristik" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                  Karakteristik
                              </label>
                              <textarea name="karakteristik" id="karakteristik" rows="3"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-darker dark:border-primary-darker dark:text-white sm:text-sm @error('karakteristik') border-red-300 @enderror">{{ old('karakteristik', $indukan->karakteristik) }}</textarea>
                              @error('karakteristik')
                                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                              @enderror
                          </div>
                      </div>
                  </div>

                  <!-- Form Actions -->
                  <div class="flex justify-end space-x-3">
                      <a href="{{ route('peternak.indukan.show', $indukan) }}" 
                         class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring focus:ring-gray-500 focus:ring-offset-1">
                          Batal
                      </a>
                      <button type="submit" 
                              class="px-4 py-2 text-white bg-primary rounded-md hover:bg-primary-dark focus:outline-none focus:ring focus:ring-primary focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark">
                          Update Indukan
                      </button>
                  </div>
              </form>
          </div>
      </div>
  </main>
</x-layout>
