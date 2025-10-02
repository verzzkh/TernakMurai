<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencatatan Keuangan - Peternakan Murai Batu</title>
    <!-- Use CDN for Tailwind CSS since we don't have the build version -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Additional custom styles if needed -->
    <style>
        /* Custom styles can go here */
        .hidden {
            display: none;
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
    <x-layout>
    <main>
        <!-- Content header -->
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <h1 class="text-2xl font-semibold">Pencatatan Keuangan</h1>
            <div class="flex space-x-2">
                <button id="addIncomeBtn" class="px-4 py-2 text-sm text-white rounded-md bg-green-600 hover:bg-green-700 focus:outline-none focus:ring focus:ring-green-600 focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark">
                    <i class="fas fa-plus-circle mr-1"></i> Tambah Pemasukan
                </button>
                <button id="addExpenseBtn" class="px-4 py-2 text-sm text-white rounded-md bg-red-600 hover:bg-red-700 focus:outline-none focus:ring focus:ring-red-600 focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark">
                    <i class="fas fa-minus-circle mr-1"></i> Tambah Pengeluaran
                </button>
            </div>
        </div>

        <!-- Content -->
        <div class="mt-2">
            <!-- State cards -->
            <div class="grid grid-cols-1 gap-8 p-4 lg:grid-cols-2 xl:grid-cols-4">
                <!-- Pemasukan Bulan Ini -->
                <div class="flex items-center justify-between p-4 bg-white rounded-md dark:bg-darker">
                    <div>
                        <h6 class="text-xs font-medium leading-none tracking-wider text-gray-500 uppercase dark:text-primary-light">
                            Pemasukan Bulan Ini
                        </h6>
                        <span class="text-xl font-semibold text-green-600">Rp 12,500,000</span>
                        <span class="inline-block px-2 py-px ml-2 text-xs text-green-500 bg-green-100 rounded-md">
                            +4.4%
                        </span>
                    </div>
                    <div>
                        <span>
                            <svg class="w-12 h-12 text-gray-300 dark:text-primary-dark" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                    </div>
                </div>

                <!-- Pengeluaran Bulan Ini -->
                <div class="flex items-center justify-between p-4 bg-white rounded-md dark:bg-darker">
                    <div>
                        <h6 class="text-xs font-medium leading-none tracking-wider text-gray-500 uppercase dark:text-primary-light">
                            Pengeluaran Bulan Ini
                        </h6>
                        <span class="text-xl font-semibold text-red-600">Rp 5,824,000</span>
                        <span class="inline-block px-2 py-px ml-2 text-xs text-red-500 bg-red-100 rounded-md">
                            +2.6%
                        </span>
                    </div>
                    <div>
                        <span>
                            <svg class="w-12 h-12 text-gray-300 dark:text-primary-dark" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </span>
                    </div>
                </div>

                <!-- Keuntungan Bulan Ini -->
                <div class="flex items-center justify-between p-4 bg-white rounded-md dark:bg-darker">
                    <div>
                        <h6 class="text-xs font-medium leading-none tracking-wider text-gray-500 uppercase dark:text-primary-light">
                            Keuntungan (Bersih)
                        </h6>
                        <span class="text-xl font-semibold text-blue-600">Rp 6,676,000</span>
                        <span class="inline-block px-2 py-px ml-2 text-xs text-blue-500 bg-blue-100 rounded-md">
                            +3.1%
                        </span>
                    </div>
                    <div>
                        <span>
                            <svg class="w-12 h-12 text-gray-300 dark:text-primary-dark" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </span>
                    </div>
                </div>

                <!-- Penjualan Anakan -->
                <div class="flex items-center justify-between p-4 bg-white rounded-md dark:bg-darker">
                    <div>
                        <h6 class="text-xs font-medium leading-none tracking-wider text-gray-500 uppercase dark:text-primary-light">
                            Penjualan Anakan
                        </h6>
                        <span class="text-xl font-semibold">5 Ekor</span>
                        <span class="inline-block px-2 py-px ml-2 text-xs text-green-500 bg-green-100 rounded-md">
                            +1 minggu ini
                        </span>
                    </div>
                    <div>
                        <span>
                            <svg class="w-12 h-12 text-gray-300 dark:text-primary-dark" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 p-4 space-y-8 lg:gap-8 lg:space-y-0 lg:grid-cols-3">
                <!-- Line chart -->
                <div class="col-span-2 bg-white rounded-md dark:bg-darker" x-data="{ isMonth: true }">
                    <!-- Card header -->
                    <div class="flex items-center justify-between p-4 border-b dark:border-primary">
                        <h4 class="text-lg font-semibold text-gray-500 dark:text-light">Tren Keuangan</h4>
                        <div class="flex items-center space-x-2">
                            <button @click="isMonth = true" class="px-3 py-1 text-sm rounded-md" :class="isMonth ? 'bg-primary text-white' : 'text-gray-500 dark:text-light'">
                                Bulanan
                            </button>
                            <button @click="isMonth = false" class="px-3 py-1 text-sm rounded-md" :class="!isMonth ? 'bg-primary text-white' : 'text-gray-500 dark:text-light'">
                                Tahunan
                            </button>
                        </div>
                    </div>
                    <!-- Chart -->
                    <div class="relative p-4 h-72">
                        <canvas id="financeChart"></canvas>
                    </div>
                </div>

                <!-- Pie chart -->
                <div class="bg-white rounded-md dark:bg-darker">
                    <!-- Card header -->
                    <div class="flex items-center justify-between p-4 border-b dark:border-primary">
                        <h4 class="text-lg font-semibold text-gray-500 dark:text-light">Distribusi Pengeluaran</h4>
                    </div>
                    <!-- Chart -->
                    <div class="relative p-4 h-72">
                        <canvas id="expenseDistributionChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="p-4">
                <div class="bg-white rounded-md dark:bg-darker">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between p-4 border-b dark:border-primary">
                        <h4 class="text-lg font-semibold text-gray-500 dark:text-light mb-3 md:mb-0">Daftar Transaksi</h4>
                        <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2 w-full md:w-auto">
                            <div class="relative">
                                <input type="text" id="searchTransaction" placeholder="Cari transaksi..." class="w-full pl-10 pr-4 py-2 rounded-md border dark:border-primary-darker dark:bg-darker focus:outline-none focus:ring focus:ring-primary-light">
                                <span class="absolute left-3 top-2 text-gray-400">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </span>
                            </div>
                            <div class="flex space-x-2">
                                <select id="filterCategory" class="px-4 py-2 rounded-md border dark:border-primary-darker dark:bg-darker focus:outline-none focus:ring focus:ring-primary-light">
                                    <option value="all">Semua Kategori</option>
                                    <option value="penjualan_burung">Penjualan Burung</option>
                                    <option value="penjualan_anakan">Penjualan Anakan</option>
                                    <option value="pakan">Pakan Burung</option>
                                    <option value="vitamin">Vitamin</option>
                                    <option value="peralatan">Peralatan</option>
                                    <option value="obat">Obat-obatan</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                                <select id="filterType" class="px-4 py-2 rounded-md border dark:border-primary-darker dark:bg-darker focus:outline-none focus:ring focus:ring-primary-light">
                                    <option value="all">Semua Tipe</option>
                                    <option value="income">Pemasukan</option>
                                    <option value="expense">Pengeluaran</option>
                                </select>
                                <button id="exportBtn" class="flex items-center px-4 py-2 text-sm text-white rounded-md bg-primary hover:bg-primary-dark focus:outline-none focus:ring focus:ring-primary focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark">
                                    <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Export
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-primary bg-gray-50 dark:text-light dark:bg-darker">
                                    <th class="px-4 py-3">Tanggal</th>
                                    <th class="px-4 py-3">Kategori</th>
                                    <th class="px-4 py-3">Deskripsi</th>
                                    <th class="px-4 py-3">Jumlah</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y dark:divide-primary dark:bg-darker">
                                <!-- Income Row -->
                                <tr class="text-gray-700 dark:text-light">
                                    <td class="px-4 py-3">05/03/2024</td>
                                    <td class="px-4 py-3">Penjualan Anakan</td>
                                    <td class="px-4 py-3">Penjualan Anakan MB-A-002</td>
                                    <td class="px-4 py-3 text-green-600 font-medium">Rp 2,500,000</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                            Aktif
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center space-x-4 text-sm">
                                          
                                            <button class="flex items-center justify-center w-8 h-8 text-blue-500 rounded-full hover:bg-blue-100 dark:hover:bg-blue-900">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </button>
                                            <button class="flex items-center justify-center w-8 h-8 text-red-500 rounded-full hover:bg-red-100 dark:hover:bg-red-900">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Expense Row -->
                                <tr class="text-gray-700 dark:text-light">
                                    <td class="px-4 py-3">03/03/2024</td>
                                    <td class="px-4 py-3">Pakan Burung</td>
                                    <td class="px-4 py-3">Pembelian Kroto Premium</td>
                                    <td class="px-4 py-3 text-red-600 font-medium">Rp 520,000</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                            Aktif
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center space-x-4 text-sm">
                                            <button class="flex items-center justify-center w-8 h-8 text-yellow-500 rounded-full hover:bg-yellow-100 dark:hover:bg-yellow-900">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                            <button class="flex items-center justify-center w-8 h-8 text-blue-500 rounded-full hover:bg-blue-100 dark:hover:bg-blue-900">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </button>
                                            <button class="flex items-center justify-center w-8 h-8 text-red-500 rounded-full hover:bg-red-100 dark:hover:bg-red-900">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Income Row -->
                                <tr class="text-gray-700 dark:text-light">
                                    <td class="px-4 py-3">28/02/2024</td>
                                    <td class="px-4 py-3">Penjualan Anakan</td>
                                    <td class="px-4 py-3">Penjualan Anakan MB-A-001</td>
                                    <td class="px-4 py-3 text-green-600 font-medium">Rp 3,200,000</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                            Aktif
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center space-x-4 text-sm">
                                            <button class="flex items-center justify-center w-8 h-8 text-yellow-500 rounded-full hover:bg-yellow-100 dark:hover:bg-yellow-900">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                            <button class="flex items-center justify-center w-8 h-8 text-blue-500 rounded-full hover:bg-blue-100 dark:hover:bg-blue-900">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </button>
                                            <button class="flex items-center justify-center w-8 h-8 text-red-500 rounded-full hover:bg-red-100 dark:hover:bg-red-900">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Expense Row -->
                                <tr class="text-gray-700 dark:text-light">
                                    <td class="px-4 py-3">25/02/2024</td>
                                    <td class="px-4 py-3">Vitamin</td>
                                    <td class="px-4 py-3">Vitamin Burung Multivitamin Plus</td>
                                    <td class="px-4 py-3 text-red-600 font-medium">Rp 345,000</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                            Aktif
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center space-x-4 text-sm">
                                            <button class="flex items-center justify-center w-8 h-8 text-yellow-500 rounded-full hover:bg-yellow-100 dark:hover:bg-yellow-900">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                            <button class="flex items-center justify-center w-8 h-8 text-blue-500 rounded-full hover:bg-blue-100 dark:hover:bg-blue-900">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </button>
                                            <button class="flex items-center justify-center w-8 h-8 text-red-500 rounded-full hover:bg-red-100 dark:hover:bg-red-900">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Expense Row -->
                                <tr class="text-gray-700 dark:text-light">
                                    <td class="px-4 py-3">22/02/2024</td>
                                    <td class="px-4 py-3">Peralatan</td>
                                    <td class="px-4 py-3">Sangkar Gantung Special Edition</td>
                                    <td class="px-4 py-3 text-red-600 font-medium">Rp 850,000</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                            Aktif
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center space-x-4 text-sm">
                                            <button class="flex items-center justify-center w-8 h-8 text-yellow-500 rounded-full hover:bg-yellow-100 dark:hover:bg-yellow-900">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                            <button class="flex items-center justify-center w-8 h-8 text-blue-500 rounded-full hover:bg-blue-100 dark:hover:bg-blue-900">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </button>
                                            <button class="flex items-center justify-center w-8 h-8 text-red-500 rounded-full hover:bg-red-100 dark:hover:bg-red-900">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="px-4 py-3 border-t dark:border-primary">
                        <div class="flex flex-col md:flex-row justify-between items-center">
                            <div>
                                <span class="text-sm text-gray-700 dark:text-light">
                                    Showing <span class="font-medium">1</span> to <span class="font-medium">5</span> of <span class="font-medium">25</span> results
                                </span>
                            </div>
                            <div class="mt-2 md:mt-0">
                                <nav class="flex items-center">
                                    <button class="px-3 py-1 rounded-md rounded-l-lg focus:outline-none focus:shadow-outline-primary" aria-label="Previous">
                                        <svg class="w-4 h-4 fill-current" aria-hidden="true" viewBox="0 0 20 20">
                                            <path d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" fill-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                    <!-- Pagination buttons will be inserted here by JavaScript -->
                                    <button class="px-3 py-1 rounded-md rounded-r-lg focus:outline-none focus:shadow-outline-primary" aria-label="Next">
                                        <svg class="w-4 h-4 fill-current" aria-hidden="true" viewBox="0 0 20 20">
                                            <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" fill-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Tambah Pemasukan -->
    <div id="incomeModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white dark:bg-darker rounded-lg w-full max-w-md mx-4 overflow-hidden">
            <div class="px-4 py-3 border-b dark:border-primary-darker flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-700 dark:text-light">Tambah Pemasukan</h3>
                <button id="closeIncomeModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <form id="incomeForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-light mb-1">Kategori</label>
                        <select id="incomeCategory" class="w-full px-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary rounded-md dark:bg-darker dark:border-primary">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="penjualan_burung">Penjualan Burung</option>
                            <option value="penjualan_anakan">Penjualan Anakan</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    
                    <div id="anakanField" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 dark:text-light mb-1">Anakan</label>
                        <select id="anakanSelect" class="w-full px-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary rounded-md dark:bg-darker dark:border-primary">
                            <option value="">-- Pilih Anakan --</option>
                            <option value="MB-A-001">MB-A-001 (Rp 3,200,000)</option>
                            <option value="MB-A-002">MB-A-002 (Rp 2,500,000)</option>
                            <option value="MB-A-003">MB-A-003 (Rp 1,800,000)</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-light mb-1">Tanggal</label>
                        <input type="date" id="incomeDate" class="w-full px-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary rounded-md dark:bg-darker dark:border-primary">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-light mb-1">Jumlah (Rp)</label>
                        <input type="text" id="incomeAmount" class="w-full px-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary rounded-md dark:bg-darker dark:border-primary" placeholder="Contoh: 1000000">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-light mb-1">Deskripsi</label>
                        <textarea id="incomeDesc" class="w-full px-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary rounded-md dark:bg-darker dark:border-primary" rows="3" placeholder="Deskripsi pemasukan..."></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-3">
                        <button type="button" id="cancelIncome" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-primary-darker dark:text-light dark:hover:bg-primary-dark">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-dark">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Pengeluaran -->
    <div id="expenseModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white dark:bg-darker rounded-lg w-full max-w-md mx-4 overflow-hidden">
            <div class="px-4 py-3 border-b dark:border-primary-darker flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-700 dark:text-light">Tambah Pengeluaran</h3>
                <button id="closeExpenseModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <form id="expenseForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-light mb-1">Kategori</label>
                        <select id="expenseCategory" class="w-full px-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary rounded-md dark:bg-darker dark:border-primary">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="pakan">Pakan Burung</option>
                            <option value="vitamin">Vitamin</option>
                            <option value="peralatan">Peralatan</option>
                            <option value="obat">Obat-obatan</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-light mb-1">Nama/Merk</label>
                        <input type="text" id="expenseName" class="w-full px-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary rounded-md dark:bg-darker dark:border-primary" placeholder="Nama atau merk produk">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-light mb-1">Tanggal</label>
                        <input type="date" id="expenseDate" class="w-full px-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary rounded-md dark:bg-darker dark:border-primary">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-light mb-1">Jumlah (Rp)</label>
                        <input type="text" id="expenseAmount" class="w-full px-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary rounded-md dark:bg-darker dark:border-primary" placeholder="Contoh: 500000">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-light mb-1">Deskripsi</label>
                        <textarea id="expenseDesc" class="w-full px-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary rounded-md dark:bg-darker dark:border-primary" rows="3" placeholder="Deskripsi pengeluaran..."></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-3">
                        <button type="button" id="cancelExpense" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-primary-darker dark:text-light dark:hover:bg-primary-dark">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-dark">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    
    <script>
 document.addEventListener('DOMContentLoaded', function() {
    // Modal Pemasukan
    const incomeModal = document.getElementById('incomeModal');
    const addIncomeBtn = document.getElementById('addIncomeBtn');
    const closeIncomeModal = document.getElementById('closeIncomeModal');
    const cancelIncome = document.getElementById('cancelIncome');
    const incomeForm = document.getElementById('incomeForm');
    const incomeCategory = document.getElementById('incomeCategory');
    const anakanField = document.getElementById('anakanField');
    const anakanSelect = document.getElementById('anakanSelect');
    
    // Data transaksi
    const transactions = [
        {
            id: 1,
            date: '2024-03-05',
            category: 'Penjualan Anakan',
            description: 'Penjualan Anakan MB-A-002',
            amount: 2500000,
            type: 'income',
            status: 'Aktif'
        },
        {
            id: 2,
            date: '2024-03-03',
            category: 'Pakan Burung',
            description: 'Pembelian Kroto Premium',
            amount: 520000,
            type: 'expense',
            status: 'Aktif'
        },
        {
            id: 3,
            date: '2024-02-28',
            category: 'Penjualan Anakan',
            description: 'Penjualan Anakan MB-A-001',
            amount: 3200000,
            type: 'income',
            status: 'Aktif'
        },
        {
            id: 4,
            date: '2024-02-25',
            category: 'Vitamin',
            description: 'Vitamin Burung Multivitamin Plus',
            amount: 345000,
            type: 'expense',
            status: 'Aktif'
        },
        {
            id: 5,
            date: '2024-02-25',
            category: 'Vitamin',
            description: 'Vitamin Burung Multivitamin Plus',
            amount: 345000,
            type: 'expense',
            status: 'Aktif'
        },
        {
            id: 6,
            date: '2024-02-22',
            category: 'Peralatan',
            description: 'Sangkar Gantung Special Edition',
            amount: 850000,
            type: 'expense',
            status: 'Aktif'
        }
    ];
    
    // Data anakan
    const anakans = [
        { id: 'MB-A-001', price: 3200000, sold: true },
        { id: 'MB-A-002', price: 2500000, sold: true },
        { id: 'MB-A-003', price: 1800000, sold: false },
        { id: 'MB-A-004', price: 2800000, sold: false },
        { id: 'MB-A-005', price: 3500000, sold: false }
    ];
    
    // Formatter untuk mata uang Rupiah
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(angka).replace('IDR', 'Rp');
    }
    
    // Format tanggal ke DD/MM/YYYY
    function formatDate(dateString) {
        const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
        return new Date(dateString).toLocaleDateString('id-ID', options);
    }
    
    // Show Income Modal
    addIncomeBtn.addEventListener('click', function() {
        incomeModal.classList.remove('hidden');
        
        // Set tanggal default ke hari ini
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('incomeDate').value = today;
        
        // Populate anakan dropdown with available anakans
        anakanSelect.innerHTML = '<option value="">-- Pilih Anakan --</option>';
        anakans.filter(a => !a.sold).forEach(anakan => {
            anakanSelect.innerHTML += `<option value="${anakan.id}">${anakan.id} (${formatRupiah(anakan.price)})</option>`;
        });
    });
    
    // Close Income Modal
    function closeIncomeModalFunc() {
        incomeModal.classList.add('hidden');
        incomeForm.reset();
        anakanField.classList.add('hidden');
    }
    
    closeIncomeModal.addEventListener('click', closeIncomeModalFunc);
    cancelIncome.addEventListener('click', closeIncomeModalFunc);
    
    // Show/Hide Anakan Field based on Category
    incomeCategory.addEventListener('change', function() {
        if (this.value === 'penjualan_anakan') {
            anakanField.classList.remove('hidden');
        } else {
            anakanField.classList.add('hidden');
        }
    });
    
    // Auto-fill amount when anakan is selected
    anakanSelect.addEventListener('change', function() {
        if (this.value) {
            const selectedAnakan = anakans.find(a => a.id === this.value);
            if (selectedAnakan) {
                const amountInput = document.getElementById('incomeAmount');
                amountInput.value = selectedAnakan.price.toLocaleString('id-ID');
            }
        }
    });
    
    // Submit Income Form
    incomeForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const category = incomeCategory.value;
        const amountStr = document.getElementById('incomeAmount').value.replace(/\D/g, '');
        const amount = parseInt(amountStr, 10);
        const date = document.getElementById('incomeDate').value;
        const description = document.getElementById('incomeDesc').value;
        
        if (!category || isNaN(amount) || !date) {
            alert('Kategori, jumlah, dan tanggal wajib diisi');
            return;
        }
        
        // Create new transaction object
        const newTransaction = {
            id: transactions.length + 1,
            date: date,
            category: category === 'penjualan_anakan' ? 'Penjualan Anakan' : 
                     category === 'penjualan_burung' ? 'Penjualan Burung' : 'Lainnya',
            description: description || (category === 'penjualan_anakan' && anakanSelect.value ? 
                          `Penjualan Anakan ${anakanSelect.value}` : 'Pemasukan'),
            amount: amount,
            type: 'income',
            status: 'Aktif'
        };
        
        // Add to transactions array (in real app, would be saved to database)
        transactions.unshift(newTransaction);
        
        // Mark anakan as sold if applicable
        if (category === 'penjualan_anakan' && anakanSelect.value) {
            const anakanIdx = anakans.findIndex(a => a.id === anakanSelect.value);
            if (anakanIdx >= 0) {
                anakans[anakanIdx].sold = true;
            }
        }
        
        // Update the table
        renderTransactionTable();
        
        // Update charts
        updateCharts();
        
        // Update summary cards
        updateSummaryCards();
        
        alert('Pemasukan berhasil ditambahkan');
        closeIncomeModalFunc();
    });
    
    // Modal Pengeluaran
    const expenseModal = document.getElementById('expenseModal');
    const addExpenseBtn = document.getElementById('addExpenseBtn');
    const closeExpenseModal = document.getElementById('closeExpenseModal');
    const cancelExpense = document.getElementById('cancelExpense');
    const expenseForm = document.getElementById('expenseForm');
    
    // Show Expense Modal
    addExpenseBtn.addEventListener('click', function() {
        expenseModal.classList.remove('hidden');
        
        // Set tanggal default ke hari ini
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('expenseDate').value = today;
    });
    
    // Close Expense Modal
    function closeExpenseModalFunc() {
        expenseModal.classList.add('hidden');
        expenseForm.reset();
    }
    
    closeExpenseModal.addEventListener('click', closeExpenseModalFunc);
    cancelExpense.addEventListener('click', closeExpenseModalFunc);
    
    // Submit Expense Form
    expenseForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const category = document.getElementById('expenseCategory').value;
        const amountStr = document.getElementById('expenseAmount').value.replace(/\D/g, '');
        const amount = parseInt(amountStr, 10);
        const date = document.getElementById('expenseDate').value;
        const name = document.getElementById('expenseName').value;
        const description = document.getElementById('expenseDesc').value;
        
        if (!category || isNaN(amount) || !date) {
            alert('Kategori, jumlah, dan tanggal wajib diisi');
            return;
        }
        
        // Category mapping
        const categoryMap = {
            'pakan': 'Pakan Burung',
            'vitamin': 'Vitamin',
            'peralatan': 'Peralatan',
            'obat': 'Obat-obatan',
            'lainnya': 'Lainnya'
        };
        
        // Create new transaction object
        const newTransaction = {
            id: transactions.length + 1,
            date: date,
            category: categoryMap[category] || 'Lainnya',
            description: description || name || 'Pengeluaran',
            amount: amount,
            type: 'expense',
            status: 'Aktif'
        };
        
        // Add to transactions array (in real app, would be saved to database)
        transactions.unshift(newTransaction);
        
        // Update the table
        renderTransactionTable();
        
        // Update charts
        updateCharts();
        
        // Update summary cards
        updateSummaryCards();
        
        alert('Pengeluaran berhasil ditambahkan');
        closeExpenseModalFunc();
    });
    
    // Format currency inputs
    const currencyInputs = [document.getElementById('incomeAmount'), document.getElementById('expenseAmount')];
    
    currencyInputs.forEach(input => {
        input.addEventListener('input', function() {
            // Remove non-numeric characters
            let value = this.value.replace(/[^\d]/g, '');
            
            if (value) {
                // Format number with thousand separator
                value = parseInt(value, 10).toLocaleString('id-ID');
            }
            
            this.value = value;
        });
    });
    
    // Variabel untuk pagination
    let currentPage = 1;
    const itemsPerPage = 5;
    
    // Render transaction table
    function renderTransactionTable() {
        const tableBody = document.querySelector('table tbody');
        const searchTerm = document.getElementById('searchTransaction').value.toLowerCase();
        const categoryFilter = document.getElementById('filterCategory').value;
        const typeFilter = document.getElementById('filterType').value;
        
        // Filter transactions
        let filteredTransactions = [...transactions];
        
        // Apply search filter
        if (searchTerm) {
            filteredTransactions = filteredTransactions.filter(tx => 
                tx.description.toLowerCase().includes(searchTerm) || 
                tx.category.toLowerCase().includes(searchTerm)
            );
        }
        
        // Apply category filter
        if (categoryFilter && categoryFilter !== 'all') {
            const categoryMap = {
                'penjualan_burung': 'Penjualan Burung',
                'penjualan_anakan': 'Penjualan Anakan',
                'pakan': 'Pakan Burung',
                'vitamin': 'Vitamin',
                'peralatan': 'Peralatan',
                'obat': 'Obat-obatan',
                'lainnya': 'Lainnya'
            };
            
            filteredTransactions = filteredTransactions.filter(tx => 
                tx.category === categoryMap[categoryFilter]
            );
        }
        
        // Apply type filter
        if (typeFilter && typeFilter !== 'all') {
            filteredTransactions = filteredTransactions.filter(tx => tx.type === typeFilter);
        }
        
        // Clear existing rows
        tableBody.innerHTML = '';
        
        // Calculate pagination
        const totalItems = filteredTransactions.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = Math.min(startIndex + itemsPerPage, totalItems);
        
        // Add rows for filtered and paginated transactions
        filteredTransactions.slice(startIndex, endIndex).forEach(tx => {
            const row = document.createElement('tr');
            row.className = 'text-gray-700 dark:text-light';
            row.setAttribute('data-id', tx.id);
            
            // Format amount without currency symbol - just use plain number formatting
            const formattedAmount = new Intl.NumberFormat('id-ID').format(tx.amount);
            
            row.innerHTML = `
                <td class="px-4 py-3">${formatDate(tx.date)}</td>
                <td class="px-4 py-3">${tx.category}</td>
                <td class="px-4 py-3">${tx.description}</td>
                <td class="px-4 py-3 ${tx.type === 'income' ? 'text-green-600' : 'text-red-600'} font-medium">
                    Rp ${formattedAmount}
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                        ${tx.status}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center space-x-4 text-sm">
                        <button class="edit-btn flex items-center justify-center w-8 h-8 text-blue-500 rounded-full hover:bg-blue-100 dark:hover:bg-blue-900">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </button>
                        <button class="delete-btn flex items-center justify-center w-8 h-8 text-red-500 rounded-full hover:bg-red-100 dark:hover:bg-red-900">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </td>
            `;
            
            tableBody.appendChild(row);
        });
        
        // Setup edit buttons
        setupEditButtons();
        
        // Update pagination controls
        updatePagination(filteredTransactions.length, totalPages);
    }
    
    // Setup edit buttons
    function setupEditButtons() {
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr');
                const id = row.getAttribute('data-id');
                openEditModal(id);
            });
        });
        
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr');
                const id = row.getAttribute('data-id');
                
                if (confirm('Apakah Anda yakin ingin menghapus transaksi ini?')) {
                    deleteTransaction(id);
                }
            });
        });
    }
    
    // Delete transaction
    function deleteTransaction(id) {
        const index = transactions.findIndex(t => t.id == id);
        if (index !== -1) {
            transactions.splice(index, 1);
            renderTransactionTable();
            updateCharts();
            updateSummaryCards();
            alert('Transaksi berhasil dihapus');
        }
    }
    
    // Update pagination
    function updatePagination(totalItems, totalPages) {
        const paginationInfo = document.querySelector('.text-sm.text-gray-700.dark\\:text-light');
        const paginationNav = document.querySelector('nav.flex.items-center');
        
        if (!paginationInfo || !paginationNav) return;
        
        // Calculate start and end item numbers
        const startItem = totalItems > 0 ? (currentPage - 1) * itemsPerPage + 1 : 0;
        const endItem = Math.min(startItem + itemsPerPage - 1, totalItems);
        
        // Update pagination info text
        paginationInfo.innerHTML = `Showing <span class="font-medium">${startItem}</span> to <span class="font-medium">${endItem}</span> of <span class="font-medium">${totalItems}</span> results`;
        
        // Update pagination controls
        const prevButton = paginationNav.querySelector('button:first-child');
        const nextButton = paginationNav.querySelector('button:last-child');
        
        // Clear existing page buttons (except first and last)
        paginationNav.querySelectorAll('button:not(:first-child):not(:last-child)').forEach(btn => {
            btn.remove();
        });
        
        // Add page number buttons
        const maxPageButtons = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxPageButtons / 2));
        let endPage = Math.min(totalPages, startPage + maxPageButtons - 1);
        
        if (endPage - startPage + 1 < maxPageButtons) {
            startPage = Math.max(1, endPage - maxPageButtons + 1);
        }
        
        for (let i = startPage; i <= endPage; i++) {
            const pageButton = document.createElement('button');
            pageButton.className = `px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-primary ${
                i === currentPage 
                ? 'bg-primary text-white' 
                : 'text-gray-700 dark:text-light'
            }`;
            pageButton.textContent = i;
            pageButton.addEventListener('click', () => {
                currentPage = i;
                renderTransactionTable();
            });
            
            nextButton.parentNode.insertBefore(pageButton, nextButton);
        }
        
        // Enable/disable prev/next buttons
        prevButton.disabled = currentPage <= 1;
        nextButton.disabled = currentPage >= totalPages;
        
        // Style disabled buttons
        if (prevButton.disabled) {
            prevButton.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            prevButton.classList.remove('opacity-50', 'cursor-not-allowed');
            prevButton.addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    renderTransactionTable();
                }
            });
        }
        
        if (nextButton.disabled) {
            nextButton.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            nextButton.classList.remove('opacity-50', 'cursor-not-allowed');
            nextButton.addEventListener('click', () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    renderTransactionTable();
                }
            });
        }
    }
    
    // Add Edit Transaction Modal
    function addEditTransactionModal() {
        // Check if modal already exists
        if (document.getElementById('editTransactionModal')) return;
        
        const modalHTML = `
        <div id="editTransactionModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
            <div class="bg-white dark:bg-darker rounded-lg w-full max-w-md mx-4 overflow-hidden">
                <div class="px-4 py-3 border-b dark:border-primary-darker flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-700 dark:text-light">Edit Transaksi</h3>
                    <button id="closeEditModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-4">
                    <form id="editTransactionForm" class="space-y-4">
                        <input type="hidden" id="editTransactionId">
                        <input type="hidden" id="editTransactionType">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-light mb-1">Kategori</label>
                            <select id="editCategory" class="w-full px-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary rounded-md dark:bg-darker dark:border-primary">
                                <option value="">-- Pilih Kategori --</option>
                                <!-- Kategori Pemasukan -->
                                <option value="Penjualan Burung">Penjualan Burung</option>
                                <option value="Penjualan Anakan">Penjualan Anakan</option>
                                <option value="Lainnya">Lainnya (Pemasukan)</option>
                                <!-- Kategori Pengeluaran -->
                                <option value="Pakan Burung">Pakan Burung</option>
                                <option value="Vitamin">Vitamin</option>
                                <option value="Peralatan">Peralatan</option>
                                <option value="Obat-obatan">Obat-obatan</option>
                                <option value="Lainnya">Lainnya (Pengeluaran)</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-light mb-1">Tanggal</label>
                            <input type="date" id="editDate" class="w-full px-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary rounded-md dark:bg-darker dark:border-primary">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-light mb-1">Jumlah (Rp)</label>
                            <input type="text" id="editAmount" class="w-full px-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary rounded-md dark:bg-darker dark:border-primary" placeholder="Contoh: 1000000">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-light mb-1">Deskripsi</label>
                            <textarea id="editDesc" class="w-full px-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary rounded-md dark:bg-darker dark:border-primary" rows="3" placeholder="Deskripsi transaksi..."></textarea>
                        </div>
                        
                        <div class="flex justify-end space-x-3 pt-3">
                            <button type="button" id="cancelEditTransaction" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-primary-darker dark:text-light dark:hover:bg-primary-dark">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-dark dark:focus:ring-offset-dark">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>`;
        
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        
        // Setup event listeners for edit modal
        document.getElementById('closeEditModal').addEventListener('click', closeEditModal);
        document.getElementById('cancelEditTransaction').addEventListener('click', closeEditModal);
        document.getElementById('editTransactionForm').addEventListener('submit', handleEditFormSubmit);
        
        // Format currency input
        const editAmountInput = document.getElementById('editAmount');
        editAmountInput.addEventListener('input', function() {
            let value = this.value.replace(/[^\d]/g, '');
            
            if (value) {
                value = parseInt(value, 10).toLocaleString('id-ID');
            }
            
            this.value = value;
        });
    }
    
    // Close edit modal
    function closeEditModal() {
        document.getElementById('editTransactionModal').classList.add('hidden');
        document.getElementById('editTransactionForm').reset();
    }
    
    // Open edit modal with transaction data
    function openEditModal(id) {
        const transaction = transactions.find(t => t.id == id);
        if (!transaction) return;
        
        // Add edit modal if it doesn't exist
        addEditTransactionModal();
        
        // Fill form with transaction data
        document.getElementById('editTransactionId').value = transaction.id;
        document.getElementById('editTransactionType').value = transaction.type;
        document.getElementById('editCategory').value = transaction.category;
        document.getElementById('editDate').value = transaction.date;
        document.getElementById('editAmount').value = transaction.amount.toLocaleString('id-ID');
        document.getElementById('editDesc').value = transaction.description;
        
        // Show modal
        document.getElementById('editTransactionModal').classList.remove('hidden');
    }
    
    // Handle edit form submission
    function handleEditFormSubmit(e) {
        e.preventDefault();
        
        const id = document.getElementById('editTransactionId').value;
        const type = document.getElementById('editTransactionType').value;
        const category = document.getElementById('editCategory').value;
        const amountStr = document.getElementById('editAmount').value.replace(/\D/g, '');
        const amount = parseInt(amountStr, 10);
        const date = document.getElementById('editDate').value;
        const description = document.getElementById('editDesc').value;
        
        if (!category || isNaN(amount) || !date) {
            alert('Kategori, jumlah, dan tanggal wajib diisi');
            return;
        }
        
        // Find transaction and update
        const index = transactions.findIndex(t => t.id == id);
        if (index !== -1) {
            transactions[index] = {
                ...transactions[index],
                category,
                amount,
                date,
                description
            };
            
            renderTransactionTable();
            updateCharts();
            updateSummaryCards();
            
            alert('Transaksi berhasil diperbarui');
            closeEditModal();
        }
    }
    
    // Update charts based on transaction data
    function updateCharts() {
        updateFinanceChart();
        updateExpenseDistributionChart();
    }
    
    // Update finance chart
    function updateFinanceChart() {
        const isMonth = document.querySelector('[x-data="{ isMonth: true }"]').__x.getUnobservedData().isMonth;
        
        let labels, incomeData, expenseData;
        
        if (isMonth) {
            // Monthly view: last 10 days
            const today = new Date();
            labels = [];
            incomeData = [];
            expenseData = [];
            
            // Generate last 10 days
            for (let i = 9; i >= 0; i--) {
                const date = new Date();
                date.setDate(today.getDate() - i);
                const dateStr = `${date.getDate()} ${date.toLocaleString('default', { month: 'short' })}`;
                labels.push(dateStr);
                
                // Find transactions for this day
                const formattedDate = date.toISOString().split('T')[0];
                const dayIncome = transactions
                    .filter(t => t.date === formattedDate && t.type === 'income')
                    .reduce((sum, tx) => sum + tx.amount, 0);
                
                const dayExpense = transactions
                    .filter(t => t.date === formattedDate && t.type === 'expense')
                    .reduce((sum, tx) => sum + tx.amount, 0);
                
                incomeData.push(dayIncome);
                expenseData.push(dayExpense);
            }
        } else {
            // Yearly view: 12 months
            labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            incomeData = Array(12).fill(0);
            expenseData = Array(12).fill(0);
            // Fill with actual data
            transactions.forEach(tx => {
                const date = new Date(tx.date);
                const month = date.getMonth();
                
                if (tx.type === 'income') {
                    incomeData[month] += tx.amount;
                } else {
                    expenseData[month] += tx.amount;
                }
            });
        }
        
        // Update chart
        financeChart.data.labels = labels;
        financeChart.data.datasets[0].data = incomeData;
        financeChart.data.datasets[1].data = expenseData;
        financeChart.update();
    }
    
    // Update expense distribution chart
    function updateExpenseDistributionChart() {
        const categories = ['Pakan Burung', 'Vitamin', 'Peralatan', 'Obat-obatan', 'Lainnya'];
        const categoryData = categories.map(category => 
            transactions
                .filter(t => t.type === 'expense' && t.category === category)
                .reduce((sum, tx) => sum + tx.amount, 0)
        );
        
        expenseDistributionChart.data.datasets[0].data = categoryData;
        expenseDistributionChart.update();
    }
    
    // Update summary cards
    function updateSummaryCards() {
        const now = new Date();
        const currentMonth = now.getMonth();
        const currentYear = now.getFullYear();
        
        // Filter transactions for current month
        const thisMonthTransactions = transactions.filter(tx => {
            const txDate = new Date(tx.date);
            return txDate.getMonth() === currentMonth && txDate.getFullYear() === currentYear;
        });
        
        // Calculate totals
        const monthlyIncome = thisMonthTransactions
            .filter(tx => tx.type === 'income')
            .reduce((sum, tx) => sum + tx.amount, 0);
        
        const monthlyExpense = thisMonthTransactions
            .filter(tx => tx.type === 'expense')
            .reduce((sum, tx) => sum + tx.amount, 0);
        
        const profit = monthlyIncome - monthlyExpense;
        
        // Count anakan sales
        const anakanSales = transactions
            .filter(tx => tx.category === 'Penjualan Anakan' && tx.type === 'income')
            .length;
        
        // Update card values
        document.querySelector('.text-xl.font-semibold.text-green-600').textContent = formatRupiah(monthlyIncome);
        document.querySelector('.text-xl.font-semibold.text-red-600').textContent = formatRupiah(monthlyExpense);
        document.querySelector('.text-xl.font-semibold.text-blue-600').textContent = formatRupiah(profit);
        
        // Count recent anakan sales (last week)
        const lastWeek = new Date();
        lastWeek.setDate(lastWeek.getDate() - 7);
        
        const recentAnakanSales = transactions
            .filter(tx => {
                return tx.category === 'Penjualan Anakan' && 
                       tx.type === 'income' && 
                       new Date(tx.date) >= lastWeek;
            })
            .length;
        
        const anakanSalesElement = document.querySelector('h6.text-xs.font-medium.leading-none.tracking-wider.text-gray-500.uppercase.dark\\:text-primary-light:contains("Penjualan Anakan")');
        if (anakanSalesElement) {
            const nextSibling = anakanSalesElement.nextElementSibling.nextElementSibling;
            if (nextSibling) {
                nextSibling.textContent = `+${recentAnakanSales} minggu ini`;
            }
        }
    }
    
    // Search and filter transactions
    document.getElementById('searchTransaction').addEventListener('input', renderTransactionTable);
    document.getElementById('filterCategory').addEventListener('change', renderTransactionTable);
    document.getElementById('filterType').addEventListener('change', renderTransactionTable);
    
    // Export to Excel
    document.getElementById('exportBtn').addEventListener('click', function() {
        exportToExcel();
    });
    
    // Function to export transactions to Excel
    function exportToExcel() {
        const searchTerm = document.getElementById('searchTransaction').value.toLowerCase();
        const categoryFilter = document.getElementById('filterCategory').value;
        const typeFilter = document.getElementById('filterType').value;
        
        // Filter transactions
        let filteredTransactions = [...transactions];
        
        // Apply search filter
        if (searchTerm) {
            filteredTransactions = filteredTransactions.filter(tx => 
                tx.description.toLowerCase().includes(searchTerm) || 
                tx.category.toLowerCase().includes(searchTerm)
            );
        }
        
        // Apply category filter
        if (categoryFilter && categoryFilter !== 'all') {
            const categoryMap = {
                'penjualan_burung': 'Penjualan Burung',
                'penjualan_anakan': 'Penjualan Anakan',
                'pakan': 'Pakan Burung',
                'vitamin': 'Vitamin',
                'peralatan': 'Peralatan',
                'obat': 'Obat-obatan',
                'lainnya': 'Lainnya'
            };
            
            filteredTransactions = filteredTransactions.filter(tx => 
                tx.category === categoryMap[categoryFilter]
            );
        }
        
        // Apply type filter
        if (typeFilter && typeFilter !== 'all') {
            filteredTransactions = filteredTransactions.filter(tx => tx.type === typeFilter);
        }
        
        // Create CSV content
        let csvContent = "data:text/csv;charset=utf-8,";
        
        // Add headers
        csvContent += "Tanggal,Kategori,Deskripsi,Jumlah,Tipe,Status\n";
        
        // Add transaction data
        filteredTransactions.forEach(tx => {
            const formattedDate = formatDate(tx.date);
            const formattedAmount = tx.amount;
            const type = tx.type === 'income' ? 'Pemasukan' : 'Pengeluaran';
            
            csvContent += `${formattedDate},${tx.category},${tx.description},${formattedAmount},${type},${tx.status}\n`;
        });
        
        // Create download link
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "transaksi_keuangan.csv");
        document.body.appendChild(link);
        
        // Trigger download
        link.click();
        
        // Clean up
        document.body.removeChild(link);
        
        // For real Excel export, you'd use a library like SheetJS or make an AJAX call to server
        // This is a simplified version using CSV which Excel can open
        alert('Data berhasil diekspor ke CSV');
    }
    
    // Global chart objects
    let financeChart, expenseDistributionChart;
    
    // Initialize charts on page load
    function initCharts() {
        // Finance Line Chart
        const ctxFinance = document.getElementById('financeChart').getContext('2d');
        financeChart = new Chart(ctxFinance, {
            type: 'line',
            data: {
                labels: ['1 Mar', '2 Mar', '3 Mar', '4 Mar', '5 Mar', '6 Mar', '7 Mar', '8 Mar', '9 Mar', '10 Mar'],
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: [0, 0, 0, 0, 2500000, 0, 0, 0, 0, 0],
                        borderColor: 'rgba(16, 185, 129, 1)',
                        backgroundColor: 'rgba(16, 185, 129, 0.2)',
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 4,
                        pointBackgroundColor: 'rgba(16, 185, 129, 1)',
                        fill: true
                    },
                    {
                        label: 'Pengeluaran',
                        data: [0, 0, 520000, 0, 0, 0, 0, 0, 0, 0],
                        borderColor: 'rgba(239, 68, 68, 1)',
                        backgroundColor: 'rgba(239, 68, 68, 0.2)',
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 4,
                        pointBackgroundColor: 'rgba(239, 68, 68, 1)',
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += formatRupiah(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000).toFixed(1) + ' Jt';
                                } else if (value >= 1000) {
                                    return 'Rp ' + (value / 1000).toFixed(0) + ' Rb';
                                }
                                return 'Rp ' + value;
                            }
                        }
                    }
                }
            }
        });
        
        // Expense Distribution Pie Chart
        const ctxExpenseDistribution = document.getElementById('expenseDistributionChart').getContext('2d');
        expenseDistributionChart = new Chart(ctxExpenseDistribution, {
            type: 'pie',
            data: {
                labels: ['Pakan Burung', 'Vitamin', 'Peralatan', 'Obat-obatan', 'Lainnya'],
                datasets: [
                    {
                        data: [520000, 345000, 850000, 125000, 210000],
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(107, 114, 128, 0.8)'
                        ],
                        borderColor: [
                            'rgba(59, 130, 246, 1)',
                            'rgba(16, 185, 129, 1)',
                            'rgba(245, 158, 11, 1)',
                            'rgba(239, 68, 68, 1)',
                            'rgba(107, 114, 128, 1)'
                        ],
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((acc, data) => acc + data, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${formatRupiah(value)} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Toggle between monthly and yearly view for finance chart
    const chartToggleButtons = document.querySelectorAll('[x-data="{ isMonth: true }"] button');
    if (chartToggleButtons.length >= 2) {
        chartToggleButtons[0].addEventListener('click', function() {
            updateFinanceChart();
        });
        
        chartToggleButtons[1].addEventListener('click', function() {
            updateFinanceChart();
        });
    }
    
    // Initialize Alpine.js for toggle functionality manually (since we might not have Alpine.js loaded)
    const chartContainer = document.querySelector('[x-data="{ isMonth: true }"]');
    if (chartContainer) {
        // Create a simple data store
        chartContainer.__x = {
            getUnobservedData: function() {
                return chartContainer._alpineData || { isMonth: true };
            }
        };
        
        // Store data
        chartContainer._alpineData = { isMonth: true };
        
        // Handle button clicks
        const monthlyBtn = chartContainer.querySelector('button:first-of-type');
        const yearlyBtn = chartContainer.querySelector('button:last-of-type');
        
        monthlyBtn.addEventListener('click', function() {
            chartContainer._alpineData.isMonth = true;
            
            // Update button styles
            monthlyBtn.classList.add('bg-primary', 'text-white');
            monthlyBtn.classList.remove('text-gray-500', 'dark:text-light');
            
            yearlyBtn.classList.remove('bg-primary', 'text-white');
            yearlyBtn.classList.add('text-gray-500', 'dark:text-light');
            
            updateFinanceChart();
        });
        
        yearlyBtn.addEventListener('click', function() {
            chartContainer._alpineData.isMonth = false;
            
            // Update button styles
            yearlyBtn.classList.add('bg-primary', 'text-white');
            yearlyBtn.classList.remove('text-gray-500', 'dark:text-light');
            
            monthlyBtn.classList.remove('bg-primary', 'text-white');
            monthlyBtn.classList.add('text-gray-500', 'dark:text-light');
            
            updateFinanceChart();
        });
    }
    
    // Initialize the page
    initCharts();
    renderTransactionTable();
});
    </script>
</x-layout>