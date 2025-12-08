<!doctype html>
<html lang="en" class="antialiased" x-data="{ dark: localStorage.getItem('dark')==='1' }" x-bind:class="{'dark': dark}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>@yield('title', 'Admin Panel') - Ternak Jawara</title>
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <script defer src="{{ mix('js/app.js') }}"></script>
    </head>
    <body class="min-h-screen bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
        <div class="flex h-screen">
            {{-- sidebar / nav --}}
            @include('admin.partials.navbar')

            <div class="flex-1 overflow-auto">
                <header class="border-b border-gray-200 dark:border-gray-800 px-6 py-4 bg-white dark:bg-gray-800">
                    <div class="max-w-7xl mx-auto flex items-center justify-between">
                        <div>
                            <h1 class="text-xl font-semibold">@yield('page-title', 'Dashboard')</h1>
                            @isset($pageSubtitle)
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $pageSubtitle }}</p>
                            @endisset
                        </div>
                        <div class="flex items-center space-x-4">
                            {{-- top-right controls if needed --}}
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ now()->toDayDateTimeString() }}</div>
                        </div>
                    </div>
                </header>

                <main class="p-6 max-w-7xl mx-auto">
                    @yield('content')
                </main>

                <footer class="px-6 py-4 border-t border-gray-200 dark:border-gray-800 text-xs text-gray-500 dark:text-gray-400">
                    <div class="max-w-7xl mx-auto text-center">© {{ date('Y') }} Ternak Jawara — Admin Panel</div>
                </footer>
            </div>
        </div>
        @stack('scripts')
    </body>
</html>
