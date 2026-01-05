    <header class="border-b border-gray-200 dark:border-gray-800 px-6 py-4 bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold">{{ $title ?? 'Dashboard' }}</h1>
                @isset($subtitle)
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $subtitle }}</p>
                @endisset
            </div>

            <div class="flex items-center space-x-4">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ now()->translatedFormat('d F Y') }}

                </div>

                <!-- Avatar + Dropdown -->
                <div class="relative" x-data="{ open:false }">

                    <!-- Avatar Button -->
                    <button 
                        @click="open = !open"
                        class="transition-opacity rounded-full focus:outline-none focus:ring"
                    >
                        <img class="w-10 h-10 rounded-full" src="/build/images/avatar.jpg" alt="avatar"/>
                    </button>

                    <!-- Dropdown -->
                    <div 
                        x-cloak
                        x-show="open"
                        @click.away="open=false"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-1"
                        class="absolute right-0 mt-2 w-48 py-1 bg-white dark:bg-dark rounded-md shadow-lg"
                    >
                        <form id="logout-form-admin" action="{{ route('logout') }}" method="POST">
                            @csrf
                        </form>

                        <a 
                            onclick="event.preventDefault(); document.getElementById('logout-form-admin').submit()"
                            class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer"
                        >
                            Logout
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </header>
