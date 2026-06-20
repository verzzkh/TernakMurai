<header class="relative bg-white dark:bg-darker" x-data="{ isMobileMainMenuOpen: false, isMobileSubMenuOpen: false, isDark: false }"
    @theme-changed.window="isDark = $event.detail.isDark">

    <!-- ========== TOP BAR ========== -->
    <div class="flex items-center justify-between p-2 border-b dark:border-primary-darker">

        <!-- Mobile Main Menu Button -->
        <button @click="isMobileMainMenuOpen = !isMobileMainMenuOpen"
            class="p-1 transition-colors duration-200 rounded-md text-primary-lighter bg-primary-50 hover:text-primary hover:bg-primary-100 dark:hover:text-light dark:hover:bg-primary-dark dark:bg-dark md:hidden focus:outline-none focus:ring">
            <span class="sr-only">Open main menu</span>
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- BRAND -->
        <a class="inline-block text-2xl font-bold tracking-wider uppercase text-primary-dark dark:text-light">
            Ternak Jawara
        </a>

        <!-- Desktop Right Buttons -->
        <div class="flex items-center space-x-2">

            <!-- Avatar Mobile -->
            <div class="relative md:hidden" x-data="{ open: false }">
                <button @click="open = !open" class="rounded-full focus:outline-none focus:ring">
                    <img class="w-10 h-10 rounded-full object-cover"
                        src="{{ auth()->user()->peternak && auth()->user()->peternak->foto_profil
                            ? asset('storage/' . auth()->user()->peternak->foto_profil)
                            : asset('build/images/avatar.jpg') }}"
                        alt="Avatar" />

                </button>

                <div x-show="open" @click.away="open=false"
                    class="absolute right-0 z-50 w-48 py-1 mt-2 bg-white dark:bg-dark rounded-md shadow-lg">

                    <a href="{{ route('peternak.profile.show') }}"
                        class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                        Profil Kamu
                    </a>

                    <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST">
                        @csrf
                    </form>

                    <a onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit()"
                        class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                        Logout
                    </a>
                </div>
            </div>

            <!-- Avatar Desktop -->
            <nav class="hidden space-x-2 md:flex md:items-center">
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="transition-opacity rounded-full dark:opacity-75 dark:hover:opacity-100 focus:outline-none focus:ring">
                        <img class="w-10 h-10 rounded-full object-cover"
                            src="{{ auth()->user()->peternak && auth()->user()->peternak->foto_profil
                                ? asset('storage/' . auth()->user()->peternak->foto_profil)
                                : asset('build/images/avatar.jpg') }}"
                            alt="Avatar" />

                    </button>

                    <div x-show="open" @click.away="open=false"
                        class="absolute right-0 w-48 py-1 mt-2 bg-white dark:bg-dark rounded-md shadow-lg">

                        <a href="{{ route('peternak.profile.show') }}"
                            class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                            Profil Kamu
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST">@csrf</form>

                        <a onclick="event.preventDefault(); document.getElementById('logout-form').submit()"
                            class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                            Logout
                        </a>
                    </div>
                </div>
            </nav>

        </div>

    </div>


    <!-- ========== MOBILE MAIN MENU (dipindah ke sini) ========== -->
    <div class="md:hidden border-b dark:border-primary-darker" x-show="isMobileMainMenuOpen"
        @click.away="isMobileMainMenuOpen = false">

        <nav aria-label="Main" class="px-2 py-4 space-y-2">
            <!-- Dashboard link -->
            <div x-data="{ isActive: false, open: false }">
                <a href="{{ route('peternak.dashboard') }}"
                    class="flex items-center p-2 rounded-md transition-colors
        @if (request()->routeIs('peternak.dashboard')) bg-primary-100 text-primary-dark dark:bg-primary dark:text-primary-100
        @else
            text-text-tertiary dark:text-light hover:bg-primary-100 dark:hover:bg-primary @endif">

                    <span aria-hidden="true">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </span>

                    <span class="ml-2 text-sm">Dashboard</span>
                </a>
            </div>

            <!-- Indukan link -->
            <div x-data="{ isActive: false, open: false }">
                <a href="{{ route('peternak.indukan.index') }}"
                    class="flex items-center space-x-3 p-2 rounded-md transition-colors
        @if (request()->routeIs('peternak.indukan.*')) bg-primary-100 text-primary-dark dark:bg-primary dark:text-primary-100
        @else
            text-text-tertiary dark:text-light hover:bg-primary-100 dark:hover:bg-primary @endif">

                    <!-- ICON INDUKAN -->
                    <svg class="w-6 h-6 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 11c-1.657 0-3-1.79-3-4s1.343-4 3-4 3 1.79 3 4-1.343 4-3 4zm10 0c-1.657 0-3-1.79-3-4s1.343-4 3-4 3 1.79 3 4-1.343 4-3 4zm-10 2c-2.5 0-5 1.5-5 4v2h10v-2c0-2.5-2.5-4-5-4zm10 0c-2.5 0-5 1.5-5 4v2h10v-2c0-2.5-2.5-4-5-4z" />
                    </svg>

                    <!-- Label -->
                    <span class="text-sm">Indukan</span>

                    <!-- Badge -->
                    <span
                        class="ml-auto bg-primary-100 text-primary-dark dark:bg-primary dark:text-primary-100 
            px-2 py-0.5 rounded-full text-xs">
                        {{ auth()->user()->peternak->indukans()->count() }}
                    </span>

                </a>
            </div>


            <!-- Kandang link -->
            <div x-data="{ isActive: false, open: false }">
                <a href="{{ route('peternak.kandang.index') }}"
                    class="flex items-center space-x-3 p-2 rounded-md transition-colors
        @if (request()->routeIs('peternak.kandang.*')) bg-primary-100 text-primary-dark dark:bg-primary dark:text-primary-100
        @else
            text-text-tertiary dark:text-light hover:bg-primary-100 dark:hover:bg-primary @endif">

                    <!-- ICON KANDANG -->
                    <svg class="w-6 h-6 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z
                   M14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z
                   M4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2z
                   M14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>

                    <!-- LABEL -->
                    <span class="text-sm">Kandang</span>

                    <!-- BADGE -->
                    <span
                        class="ml-auto bg-primary-100 text-primary-dark dark:bg-primary dark:text-primary-100
            px-2 py-0.5 rounded-full text-xs">
                        {{ auth()->user()->peternak->kandangs()->count() }}
                    </span>
                </a>
            </div>


            <!-- Anakan link -->
            <div x-data="{ isActive: false, open: false }">
                <a href="{{ route('peternak.anakan.index') }}"
                    class="flex items-center space-x-3 p-2 rounded-md transition-colors
        @if (request()->routeIs('peternak.anakan.*')) bg-primary-100 text-primary-dark dark:bg-primary dark:text-primary-100
        @else
            text-text-tertiary dark:text-light hover:bg-primary-100 dark:hover:bg-primary @endif">

                    <!-- ICON ANAKAN (burung kecil) -->
                    <svg class="w-6 h-6 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4c-2.761 0-5 2.015-5 4.5S9.239 13 12 13s5-2.015 5-4.5S14.761 4 12 4zm0 9c-3.5 0-7 1.8-7 4.5V20h14v-2.5c0-2.7-3.5-4.5-7-4.5z" />
                        <circle cx="10" cy="8.5" r="0.7" fill="currentColor" />
                        <circle cx="14" cy="8.5" r="0.7" fill="currentColor" />
                    </svg>

                    <!-- Label -->
                    <span class="text-sm">Anakan</span>

                    <!-- Badge -->
                    <span
                        class="ml-auto bg-primary-100 text-primary-dark dark:bg-primary dark:text-primary-100 
            px-2 py-0.5 rounded-full text-xs">
                        {{ auth()->user()->peternak->anakans()->count() }}
                    </span>

                </a>
            </div>


            <!-- Pencatatan link (Dihide sementara) 
            <div x-data="{ isActive: false, open: false }">
                <a href="{{ route('peternak.keuangan.index') }}"
                    class="flex items-center p-2 rounded-md transition-colors
        @if (request()->routeIs('peternak.keuangan.*')) bg-primary-100 text-primary-dark dark:bg-primary dark:text-primary-100
        @else
            text-text-tertiary dark:text-light hover:bg-primary-100 dark:hover:bg-primary @endif">

                    <span aria-hidden="true">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5z
                   M4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6z
                   M16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                        </svg>
                    </span>

                    <span class="ml-2 text-sm">Pencatatan Keuangan</span>
                </a>

            </div>
            -->

            <!-- Divider -->
            <hr class="my-3 dark:border-primary-darker">

            <!-- Section Label -->
            <h5 class="px-2 text-xs font-semibold text-text-secondary uppercase dark:text-primary-light">
                Lainnya
            </h5>

           <!-- Riwayat Analisa Breeding link -->
<div x-data="{ isActive: false, open: false }">
    <a href="{{ route('peternak.analisaBreeding.riwayat') }}"
        class="flex items-center p-2 rounded-md transition-colors
@if (request()->routeIs('peternak.analisaBreeding.*'))
    bg-primary-100 text-primary-dark dark:bg-primary dark:text-primary-100
@else
    text-text-tertiary dark:text-light hover:bg-primary-100 dark:hover:bg-primary
@endif">

        <span aria-hidden="true">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </span>

        <span class="ml-2 text-sm">Riwayat Analisa Breeding</span>
    </a>
</div>


        </nav>
    </div>

    <!-- ========== MOBILE SUB MENU (tetap absolute) ========== -->


</header>
