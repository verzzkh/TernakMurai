<x-layout>
    <main class="px-4 py-6">
        <div class="max-w-xl mx-auto bg-white dark:bg-darker rounded-lg shadow p-6">

            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                Catat Trip Gagal
            </h2>

            <form method="POST"
                  action="{{ route('peternak.kandang.storeGagal', $kandang->id) }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm text-gray-700 dark:text-gray-300">
                        Tanggal Gagal <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="tanggal_gagal"
                           name="tanggal_gagal"
                           value="{{ old('tanggal_gagal', $kandang->perkawinans()->latest('tanggal_kawin')->first()?->tanggal_kawin?->toDateString() ?? now()->format('Y-m-d')) }}"
                           placeholder="dd/mm/yyyy"
                           autocomplete="off"
                           required
                           class="custom-datepicker mt-1 w-full px-3 py-2 border rounded-md dark:bg-darker dark:border-gray-600">
                </div>

                <div class="mb-4">
                    <label class="block text-sm text-gray-700 dark:text-gray-300">
                        Catatan (Opsional)
                    </label>
                    <textarea name="catatan"
                              rows="3"
                              class="mt-1 w-full px-3 py-2 border rounded-md dark:bg-darker dark:border-gray-600"
                              placeholder="Contoh: Telur tidak menetas, dimakan induk, dsb..."></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('peternak.kandang.show', $kandang) }}"
                       class="px-4 py-2 text-sm bg-gray-200 rounded-md">
                        Batal
                    </a>

                    <button type="submit"
                            class="px-4 py-2 text-sm text-white bg-red-600 rounded-md hover:bg-red-700">
                        Simpan Trip Gagal
                    </button>
                </div>
            </form>

        </div>
    </main>
</x-layout>
