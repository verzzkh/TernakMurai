<!DOCTYPE html>
<html lang="en" x-data="{ isDark: false }" :class="{ 'dark': isDark }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Lupa Password - Ternak Murai</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.3/dist/alpine.min.js" defer></script>
</head>

<body
    class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-slate-900 dark:to-slate-800 flex items-center justify-center p-4">
    <div
        class="max-w-md w-full bg-white dark:bg-darker rounded-2xl shadow-xl p-8 border border-gray-200 dark:border-primary-darker space-y-5">
        <div class="text-center">
            <h1 class="text-2xl font-bold text-text-primary mb-2">Konfirmasi Data Akun</h1>
            <p class="text-sm text-text-secondary dark:text-light">
                Periksa kembali data berikut sebelum menghubungi admin via WhatsApp.
            </p>
        </div>

        <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4 space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="font-medium text-text-secondary dark:text-gray-300">Nama</span>
                <span class="text-text-primary dark:text-light">{{ $data['name'] }}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-text-secondary dark:text-gray-300">Login</span>
                <span class="text-text-primary dark:text-light">{{ $data['login'] }}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-text-secondary dark:text-gray-300">No WhatsApp terdaftar</span>
                <span class="text-text-primary dark:text-light">{{ $data['nomor_handphone'] }}</span>
            </div>
        </div>

        @if ($whatsappUrl)
            <div class="space-y-3">
                <p class="text-xs text-text-secondary dark:text-gray-400">
                    Dengan menekan tombol di bawah ini, WhatsApp akan terbuka dengan pesan otomatis yang sudah diisi.
                    Anda hanya perlu mengirimkan pesan tersebut ke admin.
                </p>
                <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener"
                    class="w-full inline-flex items-center justify-center bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-4 rounded-lg focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:outline-none transition duration-200 transform hover:scale-[1.02] active:scale-[0.98]">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M20.52 3.48A11.8 11.8 0 0012.01.25C6.39.25 1.75 4.89 1.75 10.5c0 1.85.48 3.65 1.39 5.24L1 23l7.44-2.08a10.5 10.5 0 004.06.8h.01c5.62 0 10.25-4.64 10.25-10.25 0-2.74-1.07-5.32-3.04-7.29zM12.51 20.5h-.01a8.7 8.7 0 01-3.73-.86l-.27-.13-4.42 1.23 1.18-4.3-.14-.28a8.73 8.73 0 01-1.2-4.43c0-4.82 3.93-8.75 8.76-8.75 2.34 0 4.54.91 6.19 2.56a8.7 8.7 0 012.56 6.2c0 4.83-3.93 8.76-8.72 8.76zm4.79-6.53c-.26-.13-1.53-.76-1.77-.85-.24-.09-.42-.13-.6.13-.18.26-.69.85-.84 1.03-.15.17-.31.2-.57.07-.26-.13-1.1-.4-2.1-1.27-.78-.69-1.31-1.54-1.46-1.8-.15-.26-.02-.4.11-.53.12-.12.26-.31.39-.46.13-.15.17-.26.26-.43.09-.17.04-.32-.02-.45-.07-.13-.6-1.44-.82-1.96-.22-.52-.44-.45-.6-.46h-.51c-.17 0-.45.06-.69.32-.24.26-.9.88-.9 2.15 0 1.26.92 2.48 1.05 2.65.13.17 1.82 2.78 4.41 3.9.62.27 1.1.43 1.48.55.62.2 1.19.17 1.64.1.5-.08 1.53-.63 1.75-1.24.22-.61.22-1.13.15-1.24-.07-.11-.24-.18-.5-.31z">
                        </path>
                    </svg>
                    Hubungi Admin via WhatsApp
                </a>
            </div>
        @else
            <div class="bg-yellow-50 dark:bg-yellow-900/40 border border-yellow-300 dark:border-yellow-700 text-xs text-yellow-800 dark:text-yellow-100 p-3 rounded-lg">
                Nomor WhatsApp admin belum dikonfigurasi di sistem. Mohon hubungi admin secara manual untuk
                reset password.
            </div>
        @endif

        <div class="pt-2 text-center">
            <a href="{{ route('login') }}"
                class="text-sm text-text-secondary dark:text-light hover:text-blue-700 hover:underline transition duration-200">
                Kembali ke halaman login
            </a>
        </div>
    </div>
</body>

</html>

