<x-admin.layout title="Dashboard">
    <div class="mt-2">

        <div class="grid grid-cols-1 gap-6 p-4 md:grid-cols-2 xl:grid-cols-4">

            <!-- Total User -->
            <div class="group flex items-center justify-between p-5 bg-white rounded-xl shadow-md dark:bg-darker transition-all hover:-translate-y-1 hover:shadow-lg">
                <div>
                    <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-primary-light">Total User</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-light">{{ number_format($totalUsers) }}</p>
                </div>
                <div class="flex items-center justify-center w-14 h-14 rounded-full bg-indigo-50 dark:bg-indigo-900/30">
                    <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11c1.657 0 3-1.567 3-3.5S17.657 4 16 4s-3 1.567-3 3.5S14.343 11 16 11zM8 22v-2a4 4 0 014-4h0a4 4 0 014 4v2"/></svg>
                </div>
            </div>

            <!-- User PRO -->
            <div class="group flex items-center justify-between p-5 bg-white rounded-xl shadow-md dark:bg-darker transition-all hover:-translate-y-1 hover:shadow-lg">
                <div>
                    <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-primary-light">User PRO</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-light">{{ number_format($userPro) }}</p>
                </div>
                <div class="flex items-center justify-center w-14 h-14 rounded-full bg-green-50 dark:bg-green-900/30">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
            </div>

            <!-- User FREE -->
            <div class="group flex items-center justify-between p-5 bg-white rounded-xl shadow-md dark:bg-darker transition-all hover:-translate-y-1 hover:shadow-lg">
                <div>
                    <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-primary-light">User FREE</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-light">{{ number_format($userFree) }}</p>
                </div>
                <div class="flex items-center justify-center w-14 h-14 rounded-full bg-blue-50 dark:bg-blue-900/30">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 6h18M3 18h18"/></svg>
                </div>
            </div>

            <!-- Total Kandang -->
            <div class="group flex items-center justify-between p-5 bg-white rounded-xl shadow-md dark:bg-darker transition-all hover:-translate-y-1 hover:shadow-lg">
                <div>
                    <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-primary-light">Total Kandang</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-light">{{ number_format($totalKandang) }}</p>
                </div>
                <div class="flex items-center justify-center w-14 h-14 rounded-full bg-sky-50 dark:bg-sky-900/30">
                    <svg class="w-8 h-8 text-sky-600 dark:text-sky-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M4 10v10h6V14h4v6h6V10"/></svg>
                </div>
            </div>

            <!-- Total Indukan -->
            <div class="group flex items-center justify-between p-5 bg-white rounded-xl shadow-md dark:bg-darker transition-all hover:-translate-y-1 hover:shadow-lg">
                <div>
                    <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-primary-light">Total Indukan</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-light">{{ number_format($totalIndukan) }}</p>
                </div>
                <div class="flex items-center justify-center w-14 h-14 rounded-full bg-yellow-50 dark:bg-yellow-900/30">
                    <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-1a4 4 0 00-4-4h-1m-4 5v-1a4 4 0 014-4h1m-7 5H3v-1a4 4 0 014-4h1m3-4a4 4 0 110-8 4 4 0 010 8z"/></svg>
                </div>
            </div>

            <!-- Total Anakan -->
            <div class="group flex items-center justify-between p-5 bg-white rounded-xl shadow-md dark:bg-darker transition-all hover:-translate-y-1 hover:shadow-lg">
                <div>
                    <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-primary-light">Total Anakan</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-light">{{ number_format($totalAnakan) }}</p>
                </div>
                <div class="flex items-center justify-center w-14 h-14 rounded-full bg-purple-50 dark:bg-purple-900/30">
                    <svg class="w-8 h-8 text-purple-600 dark:text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v4m0 0a4 4 0 100 8m0-8a4 4 0 110 8m0 4v-4"/></svg>
                </div>
            </div>

        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 p-4 space-y-8 lg:gap-8 lg:space-y-0 lg:grid-cols-3">

            <div class="col-span-2 bg-white rounded-md shadow dark:bg-darker p-4">
                <h4 class="text-lg font-semibold mb-2">Pertumbuhan pengguna per bulan</h4>
                <div class="h-72"><canvas id="usersChart"></canvas></div>
            </div>

            <div class="bg-white rounded-md shadow dark:bg-darker p-4">
                    <h4 class="text-lg font-semibold mb-2">Jumlah evaluasi breeding per bulan</h4>
                <div class="h-72"><canvas id="deteksiChart"></canvas></div>
            </div>

        </div>

        <!-- Latest lists -->
        <div class="p-4">
            <div class="bg-white rounded-md shadow dark:bg-darker p-4">
                <div class="flex items-center justify-between p-2 border-b dark:border-primary">
                    <h4 class="text-lg font-semibold">5 User Terbaru</h4>
                </div>

                <ul class="divide-y dark:divide-primary">
                    @forelse($latestUsers as $u)
                        <li class="p-4 flex justify-between items-center">
                            <div>
                                <div class="font-medium">{{ $u->name }}</div>
                                <div class="text-xs text-gray-500">{{ $u->email }}</div>
                            </div>
                            <div class="text-xs text-gray-400">{{ $u->created_at->format('d M Y') }}</div>
                        </li>
                    @empty
                        <li class="p-4 text-center text-gray-500">Tidak ada user terbaru</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="p-4">
            <div class="bg-white rounded-md shadow dark:bg-darker p-4">
                <div class="flex items-center justify-between p-2 border-b dark:border-primary">
                        <h4 class="text-lg font-semibold">5 Evaluasi Terbaru</h4>
                </div>

                <ul class="divide-y dark:divide-primary">
                    @forelse($latestDeteksi as $d)
                        <li class="p-4 flex justify-between items-center">
                            <div>
                                <div class="font-medium">{{ $d->peternak?->nama_peternakan ?? 'Anonymous' }}</div>
                                <div class="text-xs text-gray-500">{{ \Illuminate\Support\Str::limit(strip_tags($d->hasil_ai ?? ''), 80) }}</div>
                            </div>
                            <div class="text-xs text-gray-400">{{ optional($d->tanggal_analisa)->format('d M Y') }}</div>
                        </li>
                    @empty
                        <li class="p-4 text-center text-gray-500">Tidak ada evaluasi terbaru</li>
                    @endforelse
                </ul>
            </div>
        </div>

    </div>

</x-admin.layout>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = {!! json_encode($labels) !!};
    const userData = {!! json_encode($userGrowth) !!};
    const deteksiData = {!! json_encode($deteksiGrowth) !!};

    const usersCtx = document.getElementById('usersChart').getContext('2d');
    new Chart(usersCtx, {
        type: 'line',
        data: { labels, datasets: [{ label: 'User growth', data: userData, borderColor: '#4F46E5', backgroundColor: 'rgba(79,70,229,0.08)' }] },
        options: { responsive: true, maintainAspectRatio: false }
    });

    const deteksiCtx = document.getElementById('deteksiChart').getContext('2d');
    new Chart(deteksiCtx, {
        type: 'bar',
        data: { labels, datasets: [{ label: 'Evaluasi per bulan', data: deteksiData, backgroundColor: '#F59E0B' }] },
        options: { responsive: true, maintainAspectRatio: false }
    });
</script>
@endpush
