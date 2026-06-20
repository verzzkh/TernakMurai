<x-layout>
  <main>
      <!-- Content header -->
      <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
          <h1 class="text-2xl font-semibold">Edit Kandang - {{ $kandang->nomor_kandang }}</h1>
          <div class="flex items-center space-x-3">
              <a href="{{ route('peternak.kandang.show', $kandang) }}" 
                 class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring focus:ring-gray-500 focus:ring-offset-1">
                  Batal
              </a>
           <a href="{{ route('peternak.kandang.index') }}"
   class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 
          rounded-lg
          focus:outline-none focus:ring focus:ring-blue-600 
          focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark">
    Kembali
</a>

          </div>
      </div>

      <!-- Form -->
      <div class="px-4 py-6">
          <div class="max-w-2xl mx-auto">
              <form action="{{ route('peternak.kandang.update', $kandang) }}" method="POST" class="space-y-6">
                  @csrf
                  @method('PUT')
                  
                  <div class="bg-white dark:bg-darker rounded-lg shadow p-6">
                      <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Dasar</h2>
                      
                      <div class="space-y-6">
                          <!-- Nomor Kandang -->
                          <div>
                              <label for="nomor_kandang" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                  Nomor Kandang <span class="text-red-500">*</span>
                              </label>
                              <input type="text" name="nomor_kandang" id="nomor_kandang" value="{{ old('nomor_kandang', $kandang->nomor_kandang) }}" required
                                     class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-darker dark:border-primary-darker dark:text-white sm:text-sm @error('nomor_kandang') border-red-300 @enderror">
                              @error('nomor_kandang')
                                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                              @enderror
                          </div>

                          <!-- Status -->
                          <div>
                              <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                  Status Kandang <span class="text-red-500">*</span>
                              </label>

                              @if($currentPairing && $currentPairing->status === 'dihentikan')
                                  <p class="mt-1 text-sm text-yellow-700 dark:text-yellow-300">
                                      Pairing ini sedang dihentikan. Status breeding baru tidak dapat dimulai sampai pairing diaktifkan kembali.
                                  </p>
                              @endif

                              <select name="status" id="status" required
                                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-darker dark:border-primary-darker dark:text-white sm:text-sm @error('status') border-red-300 @enderror">
                                  <option value="{{ $kandang->status }}" {{ old('status', $kandang->status) === $kandang->status ? 'selected' : '' }}>
                                      Saat ini: {{ $statusLabels[$kandang->status] ?? ucfirst($kandang->status) }}
                                  </option>
                                  @foreach($validNextStatuses as $status)
                                      <option value="{{ $status }}" {{ old('status') === $status ? 'selected' : '' }}>
                                          {{ $statusLabels[$status] ?? ucfirst($status) }}
                                      </option>
                                  @endforeach
                              </select>
                              @error('status')
                                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                              @enderror
                          </div>

                          <!-- Deskripsi Kandang -->
                          <div>
                              <label for="deskripsi_kandang" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                  Deskripsi Kandang
                              </label>
                              <textarea name="deskripsi_kandang" id="deskripsi_kandang" rows="3"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-darker dark:border-primary-darker dark:text-white sm:text-sm @error('deskripsi_kandang') border-red-300 @enderror">{{ old('deskripsi_kandang', $kandang->deskripsi_kandang) }}</textarea>
                              @error('deskripsi_kandang')
                                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                              @enderror
                          </div>
                      </div>
                  </div>

                  <!-- Indukan Assignment -->
<div class="bg-white dark:bg-darker rounded-lg shadow p-6">
    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Penugasan Indukan</h2>

    @php
        $isLocked = in_array($kandang->status, ['bertelur', 'mengeram']);
    @endphp

    @if($isLocked)
        <div class="p-4 mb-4 bg-yellow-50 border border-yellow-200 rounded-md dark:bg-yellow-900/20 dark:border-yellow-800">
            <p class="text-sm text-yellow-800 dark:text-yellow-200">
                ⚠️ Kandang ini sedang <strong>{{ ucfirst($kandang->status) }}</strong>. 
                Perubahan pasangan indukan tidak diizinkan hingga proses ini selesai.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Indukan Jantan</label>
                <input type="text" readonly
                       value="{{ $kandang->indukanJantan?->nomor_ring ?? 'Tidak ada' }}"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 sm:text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Indukan Betina</label>
                <input type="text" readonly
                       value="{{ $kandang->indukanBetina?->nomor_ring ?? 'Tidak ada' }}"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 sm:text-sm">
            </div>
        </div>
    @else
        {{-- ✅ Form aktif jika kandang kosong --}}
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <!-- Indukan Jantan -->
            <div>
                <label for="indukan_jantan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Indukan Jantan
                </label>
                <select name="indukan_jantan_id" id="indukan_jantan_id"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-darker dark:border-primary-darker dark:text-white sm:text-sm @error('indukan_jantan_id') border-red-300 @enderror">
                    <option value="">Pilih Indukan Jantan</option>
                    @foreach($indukan['jantan'] as $jantan)
                        <option value="{{ $jantan->id }}" {{ old('indukan_jantan_id', $kandang->indukan_jantan_id) == $jantan->id ? 'selected' : '' }}>
                            {{ $jantan->nomor_ring }} @if($jantan->nama) - {{ $jantan->nama }} @endif
                        </option>
                    @endforeach
                </select>
                @error('indukan_jantan_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Indukan Betina -->
            <div>
                <label for="indukan_betina_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Indukan Betina
                </label>
                <select name="indukan_betina_id" id="indukan_betina_id"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-darker dark:border-primary-darker dark:text-white sm:text-sm @error('indukan_betina_id') border-red-300 @enderror">
                    <option value="">Pilih Indukan Betina</option>
                    @foreach($indukan['betina'] as $betina)
                        <option value="{{ $betina->id }}" {{ old('indukan_betina_id', $kandang->indukan_betina_id) == $betina->id ? 'selected' : '' }}>
                            {{ $betina->nomor_ring }} @if($betina->nama) - {{ $betina->nama }} @endif
                        </option>
                    @endforeach
                </select>
                @error('indukan_betina_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        @if($indukan['jantan']->count() == 0 && $indukan['betina']->count() == 0)
            <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-md dark:bg-yellow-900/20 dark:border-yellow-800">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                            Belum Ada Indukan
                        </h3>
                        <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                            <p>Anda belum memiliki indukan. <a href="{{ route('peternak.indukan.create') }}" class="font-medium underline text-yellow-800 dark:text-yellow-200">Tambah indukan terlebih dahulu</a> untuk dapat menugaskannya ke kandang.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>


                  <!-- Form Actions -->
                  <div class="flex justify-end space-x-3">
                      <a href="{{ route('peternak.kandang.show', $kandang) }}" 
                         class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring focus:ring-gray-500 focus:ring-offset-1">
                          Batal
                      </a>
                      <button type="submit"
    class="px-4 py-2 text-white bg-cyan-700 hover:bg-cyan-800 
           rounded-lg
           focus:outline-none focus:ring focus:ring-cyan-700 
           focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark">
    Update Kandang
</button>

                  </div>
              </form>
          </div>
      </div>
  </main>
</x-layout>
