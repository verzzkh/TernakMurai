<!doctype html>
<html lang="en" class="antialiased" 
      x-data="{ dark: localStorage.getItem('dark')==='1' }"
      x-bind:class="{ 'dark': dark }">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Panel' }} - Ternak Jawara</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
    </style>

    <!-- 🟢 Tambahkan Alpine.js DI SINI -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <div class="flex h-screen">

        {{-- admin sidebar --}}
        <x-admin.sidebar />

        <div class="flex-1 overflow-auto">
            <x-admin.header :title="($title ?? 'Dashboard')" :subtitle="($subtitle ?? null)" />

            <main class="p-6 max-w-7xl mx-auto">
                {{ $slot }}
            </main>

            <x-admin.footer />
        </div>
    </div>

    @stack('scripts')
</body>
</html>
