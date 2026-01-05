<aside class="w-64 bg-white dark:bg-gray-850 border-r border-gray-200 dark:border-gray-800 hidden md:flex flex-col">
    <div class="px-6 py-6 flex items-center justify-between border-b border-gray-100 dark:border-gray-800">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-md bg-primary flex items-center justify-center text-white font-bold">TJ</div>
            <div>
                <div class="text-sm font-semibold">Ternak Jawara</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Admin Panel</div>
            </div>
        </div>
        <button @click="dark = !dark; localStorage.setItem('dark', dark ? '1' : '0')" class="p-2 rounded-md bg-gray-100 dark:bg-gray-700/40">
            <svg class="w-5 h-5 text-gray-700 dark:text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-12.66l-.7.7M4.34 19.66l-.7.7M21 12h1M2 12H1m17.66 4.66l-.7-.7M6.34 4.34l-.7-.7"/></svg>
        </button>
    </div>

    <nav class="flex-1 overflow-auto px-2 py-4 space-y-1">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2 rounded-md text-sm hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
            <svg class="w-5 h-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 6h18M3 18h18"/></svg>
            Dashboard
        </a>

        <a href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.users.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11c1.657 0 3-1.567 3-3.5S17.657 4 16 4s-3 1.567-3 3.5S14.343 11 16 11zM8 22v-2a4 4 0 014-4h0a4 4 0 014 4v2"/></svg>
            User Management
        </a>

        <a href="{{ route('admin.deteksi.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.deteksi.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
            <svg class="w-5 h-5 mr-2 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6M12 9v6M5 12a7 7 0 1114 0 7 7 0 01-14 0z"/></svg>
            Riwayat Deteksi
        </a>

        <a href="{{ route('admin.paket.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.paket.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/></svg>
            Paket & Kuota
        </a>

        <a href="{{ route('admin.settings.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.settings.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
            <svg class="w-5 h-5 mr-2 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/></svg>
            Pengaturan Sistem
        </a>
    </nav>

    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-md">Logout</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
    </div>
</aside>
