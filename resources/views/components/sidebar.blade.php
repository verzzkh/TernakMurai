<aside class="flex-shrink-0 hidden w-64 bg-white border-r dark:border-primary-darker dark:bg-darker md:block"
    id="sidebar" x-data="{ isDark: false }" @theme-changed.window="isDark = $event.detail.isDark">
    <div class="flex flex-col h-full">
        <!-- Sidebar header -->
        <div class="flex items-center justify-between px-4 py-3 border-b dark:border-primary-darker">

    <!-- Logo + Brand -->
    <div class="flex items-center gap-3">
   <img 
    src="{{ asset('images/logo/LogoMurai.png') }}"
    class="w-14 h-14 object-cover"
    alt="Ternak Jawara"
>


        <span class="text-lg font-bold whitespace-nowrap">
            Ternak Jawara
        </span>
    </div>

    <!-- Close button (mobile) -->
    <button id="toggle-sidebar-mobile"
        class="md:hidden p-2 rounded-md text-text-primary dark:text-light 
               hover:bg-gray-100 dark:hover:bg-primary-dark">
        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

</div>


        <!-- User profile -->
        <div class="flex items-center px-4 py-3 border-b dark:border-primary-darker">
            <div class="ml-3">
                <h4 class="text-sm font-semibold text-text-primary dark:text-light">
                    {{ auth()->user()->name ?? 'Peternak' }}</h4>
                
            </div>
        </div>

        <!-- Sidebar links -->
        <a href="{{ route('dashboard') }}"
            class="flex items-center p-2 rounded-md transition-colors
          @if (request()->routeIs('dashboard') || request()->routeIs('peternak.dashboard')) bg-primary-100 text-primary-dark dark:bg-primary dark:text-primary-100
          @else
               text-text-tertiary dark:text-light hover:bg-primary-100 dark:hover:bg-primary @endif">
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
            class="flex items-center p-2 rounded-md transition-colors
          @if (request()->routeIs('peternak.indukan.*')) bg-primary-100 text-primary-dark dark:bg-primary dark:text-primary-100
          @else
               text-text-tertiary dark:text-light hover:bg-primary-100 dark:hover:bg-primary @endif">

            <!-- ICON -->
            <span aria-hidden="true">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 11c-1.657 0-3-1.79-3-4s1.343-4 3-4 3 1.79 3 4-1.343 4-3 4zm10 0c-1.657 0-3-1.79-3-4s1.343-4 3-4 3 1.79 3 4-1.343 4-3 4zm-10 2c-2.5 0-5 1.5-5 4v2h10v-2c0-2.5-2.5-4-5-4zm10 0c-2.5 0-5 1.5-5 4v2h10v-2c0-2.5-2.5-4-5-4z" />
                </svg>
            </span>


            <span class="ml-2 text-sm">Indukan</span>

            <span
                class="ml-auto bg-primary-100 text-primary-dark dark:bg-primary dark:text-primary-100 
                 px-2 py-0.5 rounded-full text-xs">
                {{ auth()->user()->peternak->indukans()->count() }}
            </span>
        </a>


        <!-- Kandang link -->
        <a href="{{ route('peternak.kandang.index') }}"
            class="flex items-center p-2 rounded-md transition-colors
          @if (request()->routeIs('peternak.kandang.*')) bg-primary-100 text-primary-dark dark:bg-primary dark:text-primary-100
          @else
               text-text-tertiary dark:text-light hover:bg-primary-100 dark:hover:bg-primary @endif">

            <!-- Icon -->
            <span aria-hidden="true">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
            </span>

            <!-- Text -->
            <span class="ml-2 text-sm">Kandang</span>

            <!-- Badge -->
            <span
                class="ml-auto bg-primary-100 text-primary-dark dark:bg-primary dark:text-primary-100
                 px-2 py-0.5 rounded-full text-xs">
                {{ auth()->user()->peternak->kandangs()->count() }}
            </span>

        </a>

        <!-- Anakan link -->
        <a href="{{ route('peternak.anakan.index') }}"
            class="flex items-center p-2 rounded-md transition-colors
          @if (request()->routeIs('peternak.anakan.*')) bg-primary-100 text-primary-dark dark:bg-primary dark:text-primary-100
          @else
               text-text-tertiary dark:text-light hover:bg-primary-100 dark:hover:bg-primary @endif">

            <!-- Icon -->
            <span aria-hidden="true">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4c-2.761 0-5 2.015-5 4.5S9.239 13 12 13s5-2.015 5-4.5S14.761 4 12 4zm0 9c-3.5 0-7 1.8-7 4.5V20h14v-2.5c0-2.7-3.5-4.5-7-4.5z" />
                    <circle cx="10" cy="8.5" r="0.7" fill="currentColor" />
                    <circle cx="14" cy="8.5" r="0.7" fill="currentColor" />
                </svg>
            </span>


            <!-- Text -->
            <span class="ml-2 text-sm">Anakan</span>

            <!-- Badge -->
            <span
                class="ml-auto bg-primary-100 text-primary-dark dark:bg-primary dark:text-primary-100
                 px-2 py-0.5 rounded-full text-xs">
                {{ auth()->user()->peternak->anakans()->count() }}
            </span>
        </a>


        <!-- keuangan link -->
        <a href="{{ route('peternak.keuangan.index') }}"
            class="flex items-center p-2 rounded-md transition-colors
          @if (request()->routeIs('peternak.keuangan.*')) bg-primary-100 text-primary-dark dark:bg-primary dark:text-primary-100
          @else
               text-text-tertiary dark:text-light hover:bg-primary-100 dark:hover:bg-primary @endif">

            <!-- Icon -->
            <span aria-hidden="true">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5z
                   M4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6z
                   M16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                </svg>
            </span>

            <!-- Label -->
            <span class="ml-2 text-sm">Pencatatan Keuangan</span>
        </a>


        <!-- Divider -->
        <hr class="my-3 dark:border-primary-darker">

        <!-- Additional links -->
        <h5 class="px-2 text-xs font-semibold text-text-secondary uppercase dark:text-primary-light">Lainnya</h5>

       <!-- Riwayat Analisa Breeding link -->
<a href="{{ route('peternak.analisaBreeding.riwayat') }}"
   class="flex items-center p-2 rounded-md transition-colors
   @if (request()->routeIs('peternak.analisaBreeding.*'))
       bg-primary-100 text-primary-dark dark:bg-primary dark:text-primary-100
   @else
       text-text-tertiary dark:text-light hover:bg-primary-100 dark:hover:bg-primary
   @endif">

    <!-- Icon -->
    <span aria-hidden="true">
        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
    </span>

    <!-- Label -->
    <span class="ml-2 text-sm">Riwayat Analisa Breeding</span>
</a>


        </nav>

        <!-- Sidebar footer -->

    </div>
</aside>
