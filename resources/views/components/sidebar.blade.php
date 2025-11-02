<aside class="flex-shrink-0 hidden w-64 bg-white border-r dark:border-primary-darker dark:bg-darker md:block" id="sidebar" x-data="{ isDark: false }" @theme-changed.window="isDark = $event.detail.isDark">
    <div class="flex flex-col h-full">
        <!-- Sidebar header -->
        <div class="flex items-center justify-between px-4 py-3 border-b dark:border-primary-darker">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-primary-dark dark:text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="ml-2 text-lg font-semibold text-text-primary dark:text-light">Murai Batu</span>
            </div>
            <button id="toggle-sidebar-mobile" class="md:hidden p-2 rounded-md text-text-primary dark:text-light hover:bg-gray-100 dark:hover:bg-primary-dark">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- User profile -->
        <div class="flex items-center px-4 py-3 border-b dark:border-primary-darker">
            <div class="w-10 h-10 overflow-hidden rounded-full bg-gray-200 dark:bg-primary-darker flex items-center justify-center">
                <svg class="w-6 h-6 text-text-tertiary dark:text-primary-light" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-semibold text-text-primary dark:text-light">{{ auth()->user()->name ?? 'Peternak' }}</h4>
                <p class="text-xs text-text-tertiary dark:text-primary-light">{{ auth()->user()->peternak->jenis_akun === 'pro' ? 'Peternak Pro' : 'Peternak Free' }}</p>
            </div>
        </div>

        <!-- Sidebar links -->
        <nav aria-label="Main" class="flex-1 px-2 py-4 space-y-2 overflow-y-hidden hover:overflow-y-auto">
            <!-- Dashboard link -->
            <a href="{{ route('dashboard') }}"
                class="flex items-center p-2 text-text-tertiary transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary" data-page="dashboard">
                <span aria-hidden="true">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </span>
                <span class="ml-2 text-sm"> Dashboard </span>
            </a>

            <!-- Indukan link -->
            <a href="{{ route('peternak.indukan.index') }}"
                class="flex items-center p-2 text-text-tertiary transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary" data-page="indukan">
                <span aria-hidden="true">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </span>
                <span class="ml-2 text-sm"> Indukan </span>
                <span class="ml-auto bg-primary-100 text-primary-dark dark:bg-primary dark:text-primary-100 px-2 py-0.5 rounded-full text-xs">{{ auth()->user()->peternak->indukans()->count() }}</span>
            </a>

            <!-- Kandang link -->
            <a href="{{ route('peternak.kandang.index') }}"
                class="flex items-center p-2 text-text-tertiary transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary" data-page="kandang">
                <span aria-hidden="true">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                </span>
                <span class="ml-2 text-sm"> Kandang </span>
                <span class="ml-auto bg-primary-100 text-primary-dark dark:bg-primary dark:text-primary-100 px-2 py-0.5 rounded-full text-xs">{{ auth()->user()->peternak->kandangs()->count() }}</span>
            </a>

            <!-- Anakan link -->
            <a href="{{ route('peternak.anakan.index') }}"
                class="flex items-center p-2 text-text-tertiary transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary" data-page="anakan">
                <span aria-hidden="true">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </span>
                <span class="ml-2 text-sm"> Anakan </span>
                <span class="ml-auto bg-primary-100 text-primary-dark dark:bg-primary dark:text-primary-100 px-2 py-0.5 rounded-full text-xs">8</span>
            </a>
            
            <!-- Pencatatan link -->
            <a href="{{ route('peternak.pencatatan') }}"
                class="flex items-center p-2 text-text-tertiary transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary" data-page="pencatatan">
                <span aria-hidden="true">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                    </svg>
                </span>
                <span class="ml-2 text-sm"> Pencatatan Keuangan </span>
            </a>

            <!-- Divider -->
            <hr class="my-3 dark:border-primary-darker">

            <!-- Additional links -->
            <h5 class="px-2 text-xs font-semibold text-text-secondary uppercase dark:text-primary-light">Lainnya</h5>
            
            <!-- Deteksi Penyakit link -->
            <a href="{{ route('peternak.deteksi-penyakit.index') }}"
                class="flex items-center p-2 text-text-tertiary transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary" data-page="reports">
                <span aria-hidden="true">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </span>
                <span class="ml-2 text-sm"> Deteksi Penyakit </span>
            </a>
        </nav>

        <!-- Sidebar footer -->
        <div class="flex-shrink-0 px-2 py-4 space-y-2">
            <div class="flex items-center justify-between px-2 py-2 text-sm text-text-secondary dark:text-light">
                <span>Dark Mode</span>
                <button id="theme-toggle" class="relative inline-flex items-center w-10 h-5 rounded-full bg-gray-200 dark:bg-primary-dark focus:outline-none" @click="$parent.toggleTheme()">
                    <span class="absolute w-4 h-4 transition-transform duration-300 transform bg-white rounded-full translate-x-0.5 dark:translate-x-5" :class="{ 'translate-x-5': isDark, 'translate-x-0.5': !isDark }"></span>
                </button>
            </div>
            <button id="settings-panel-btn" type="button"
                class="flex items-center justify-center w-full px-4 py-2 text-sm text-white rounded-md bg-primary hover:bg-primary-dark focus:outline-none focus:ring focus:ring-primary-dark focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark">
                <span aria-hidden="true">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                </span>
                <span>Pengaturan</span>
            </button>
        </div>
    </div>
</aside>

