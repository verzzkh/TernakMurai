<x-layout>
    <main>
        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <h1 class="text-2xl font-semibold text-text-primary">Dashboard</h1>
        </div>

        <div class="mt-2">

            <!-- ========================================================= -->
<!-- 1️⃣ STATISTIK UTAMA (Versi Premium) -->
<!-- ========================================================= -->
<div class="grid grid-cols-1 gap-6 p-4 md:grid-cols-2 xl:grid-cols-4">

    <!-- Keuntungan Bulan Ini -->
    <div class="group flex items-center justify-between p-5 bg-white rounded-xl shadow-md dark:bg-darker transition-all hover:-translate-y-1 hover:shadow-lg">
        <div>
            <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-primary-light">
                Keuntungan Bulan Ini
            </p>
            <p class="text-2xl font-bold text-gray-800 dark:text-light">
                Rp {{ number_format($profitMonth, 0, ',', '.') }}
            </p>
        </div>

        <div class="flex items-center justify-center w-14 h-14 rounded-full bg-green-100 dark:bg-green-900/30">
            <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                 viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2
                      m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1
                      M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
    </div>

    <!-- Total Kandang -->
    <div class="group flex items-center justify-between p-5 bg-white rounded-xl shadow-md dark:bg-darker transition-all hover:-translate-y-1 hover:shadow-lg">
        <div>
            <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-primary-light">
                Total Kandang
            </p>
            <p class="text-2xl font-bold text-gray-800 dark:text-light">
                {{ $totalKandang }}
            </p>
        </div>

        <div class="flex items-center justify-center w-14 h-14 rounded-full bg-blue-100 dark:bg-blue-900/30">
           <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round"
    d="M3 12l9-9 9 9M4 10v10h6V14h4v6h6V10" />
</svg>

        </div>
    </div>

    <!-- Total Indukan -->
    <div class="group flex items-center justify-between p-5 bg-white rounded-xl shadow-md dark:bg-darker transition-all hover:-translate-y-1 hover:shadow-lg">
        <div>
            <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-primary-light">
                Total Indukan
            </p>
            <p class="text-2xl font-bold text-gray-800 dark:text-light">{{ $totalIndukan }}</p>
        </div>

        <div class="flex items-center justify-center w-14 h-14 rounded-full bg-yellow-100 dark:bg-yellow-900/30">
          <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round"
    d="M17 20h5v-1a4 4 0 00-4-4h-1m-4 5v-1a4 4 0 014-4h1m-7 5H3v-1a4 4 0 014-4h1m3-4a4 4 0 110-8 4 4 0 010 8z" />
</svg>

        </div>
    </div>

    <!-- Total Anakan -->
    <div class="group flex items-center justify-between p-5 bg-white rounded-xl shadow-md dark:bg-darker transition-all hover:-translate-y-1 hover:shadow-lg">
        <div>
            <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-primary-light">
                Total Anakan
            </p>
            <p class="text-2xl font-bold text-gray-800 dark:text-light">{{ $totalAnakan }}</p>
        </div>

        <div class="flex items-center justify-center w-14 h-14 rounded-full bg-purple-100 dark:bg-purple-900/30">
       <div class="flex items-center justify-center w-14 h-14 rounded-full bg-purple-100 dark:bg-purple-900/30">
    <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 3v4m0 0a4 4 0 100 8m0-8a4 4 0 110 8m0 4v-4m8-4h-4m-4 0H4" />
    </svg>
</div>


        </div>
    </div>

</div>


            <!-- ========================================================= -->
            <!-- 2️⃣ GRAFIK -->
            <!-- ========================================================= -->
            <div class="grid grid-cols-1 p-4 space-y-8 lg:gap-8 lg:space-y-0 lg:grid-cols-3">

                <!-- Bar Chart: Populasi per kandang -->
                <div class="col-span-2 bg-white rounded-md shadow dark:bg-darker p-4">
                    <h4 class="text-lg font-semibold mb-2">Jumlah Populasi per Kandang</h4>
                    <div class="h-72"><canvas id="barKandangChart"></canvas></div>
                </div>

                <!-- Pie Chart: Status Kandang -->
                <div class="bg-white rounded-md shadow dark:bg-darker p-4">
                    <h4 class="text-lg font-semibold mb-2">Status Kandang</h4>
                    <div class="h-72"><canvas id="pieKandangChart"></canvas></div>
                </div>

            </div>



            <!-- ========================================================= -->
            <!-- 3️⃣ AKTIVITAS TERBARU -->
            <!-- ========================================================= -->
            <div class="p-4">
                <div class="bg-white rounded-md shadow dark:bg-darker">
                    <div class="flex items-center justify-between p-4 border-b dark:border-primary">
                        <h4 class="text-lg font-semibold">Aktivitas Terbaru</h4>
                    </div>

                    <ul class="divide-y dark:divide-primary">
                        @forelse ($recentActivities as $a)
                            <li class="p-4 flex justify-between">
                                <div>
                                 <p class="font-medium">
    {{ ucfirst($a->tipe) }} — {{ $a->kategori_label }}
</p>
                                    <p class="text-sm text-gray-500">
                                        {{ $a->tanggal->format('d M Y') }}
                                    </p>
                                </div>
                                <span class="font-semibold {{ $a->tipe == 'pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                                    Rp {{ number_format($a->jumlah, 0, ',', '.') }}
                                </span>
                            </li>
                        @empty
                            <li class="p-4 text-center text-gray-500">Belum ada aktivitas</li>
                        @endforelse
                    </ul>
                </div>
            </div>

        </div>
    </main>

    <!-- Chart Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <script>
        window.kandangChart = @json($kandangChart);
        window.kandangStatus = @json($kandangStatus);
    </script>
  @push('scripts')
    @vite('resources/js/dashboard-charts.js')
@endpush



</x-layout>
