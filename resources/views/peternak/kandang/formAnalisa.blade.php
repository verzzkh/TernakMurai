<x-layout>
    <main>
        <!-- HEADER -->
       <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
    <h1 class="text-2xl font-semibold">Analisis Kecocokan Indukan</h1>

    <div class="flex gap-3">
        <a href="{{ route('peternak.analisaBreeding.riwayat') }}"
            class="px-4 py-2 text-sm text-white bg-green-600 hover:bg-green-700 rounded-lg">
            Riwayat Analisa
        </a>

        <a href="{{ route('peternak.kandang.index') }}"
            class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg">
            Kembali
        </a>
    </div>
</div>

        <!-- FORM -->
        <div class="p-4 max-w-3xl mx-auto">
            <div class="bg-white dark:bg-darker rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-700 dark:text-light">
                    Pilih Pasangan Indukan
                </h2>

               <form action="{{ route('peternak.analisaBreeding.analisa') }}" method="POST">
                    @csrf

                    <!-- JANTAN -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Indukan Jantan
                        </label>
                        <select name="jantan_id" required
                            class="w-full px-3 py-2 border-gray-300 rounded-md dark:bg-darker dark:border-primary">
                            <option value="">-- Pilih Jantan --</option>

                            @foreach ($jantan as $j)
                                <option value="{{ $j->id }}">
                                    {{ $j->nomor_ring }} 
                                    @if($j->nama)
                                        - {{ $j->nama }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- BETINA -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Indukan Betina
                        </label>
                        <select name="betina_id" required
                            class="w-full px-3 py-2 border-gray-300 rounded-md dark:bg-darker dark:border-primary">
                            <option value="">-- Pilih Betina --</option>

                            @foreach ($betina as $b)
                                <option value="{{ $b->id }}">
                                    {{ $b->nomor_ring }} 
                                    @if($b->nama)
                                        - {{ $b->nama }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- SUBMIT -->
                 <button type="submit"
    class="w-full px-4 py-2 
           text-white 
           bg-cyan-600 rounded-md 
           hover:bg-cyan-700
           focus:outline-none focus:ring focus:ring-cyan-600 focus:ring-offset-1
           dark:bg-cyan-500 dark:hover:bg-cyan-600"
           onclick="this.disabled=true; this.innerText='Memproses...'; this.form.submit();">
              
    Proses Analisis
</button>

                </form>
            </div>
        </div>
    </main>
</x-layout>
