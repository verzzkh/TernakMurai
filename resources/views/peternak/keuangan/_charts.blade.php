<div class="grid grid-cols-1 p-4 space-y-8 lg:gap-8 lg:space-y-0 lg:grid-cols-3">
    <!-- Chart Keuangan -->
    <div class="col-span-2 bg-white rounded-md shadow dark:bg-darker" x-data="{ mode: 'month' }">
        <div class="flex items-center justify-between p-4 border-b dark:border-primary">
            <h4 class="text-lg font-semibold text-gray-500 dark:text-light">Tren Keuangan</h4>

            <!-- Tombol filter -->
            <div class="flex items-center space-x-2">
                <button id="btnMonth" class="px-3 py-1 text-sm rounded-md bg-primary text-white focus:outline-none">
                    Bulanan
                </button>
                <button id="btnYear" class="px-3 py-1 text-sm rounded-md bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-light hover:bg-primary hover:text-white focus:outline-none">
                    Tahunan
                </button>
            </div>
        </div>

        <div class="relative p-4 h-72">
            <canvas id="financeChart"></canvas>
        </div>
    </div>

    <!-- Chart Distribusi Pengeluaran -->
    <div class="bg-white rounded-md shadow dark:bg-darker">
        <div class="flex items-center justify-between p-4 border-b dark:border-primary">
            <h4 class="text-lg font-semibold text-gray-500 dark:text-light">Distribusi Pengeluaran</h4>
        </div>
        <div class="relative p-4 h-72">
            <canvas id="expenseDistributionChart"></canvas>
        </div>
    </div>
</div>
