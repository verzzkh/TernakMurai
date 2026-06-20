<!DOCTYPE html>
<html lang="en" x-data="{ isDark: false }" :class="{ 'dark': isDark }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Ternak Murai</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.3/dist/alpine.min.js" defer></script>
</head>

<body
    class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-slate-900 dark:to-slate-800 flex items-center justify-center p-4">
    <div
        class="max-w-md w-full bg-white dark:bg-darker rounded-2xl shadow-xl p-8 border border-gray-200 dark:border-primary-darker">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-text-primary mb-2">Lupa Password</h1>
            <p class="text-sm text-text-secondary dark:text-light">
                Isi data akun Anda. Sistem akan menyiapkan pesan otomatis untuk dikirim ke admin via WhatsApp.
            </p>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 text-sm p-3 rounded-lg mb-4">
                <div class="flex">
                    <svg class="w-4 h-4 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('password.whatsapp.submit') }}" class="space-y-5" x-data="{ loading: false }" @submit="loading = true">
            @csrf

            <div>
                <label for="login" class="block text-sm font-medium text-text-primary mb-2">
                    Email atau Nama Akun
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                            </path>
                        </svg>
                    </div>
                    <input id="login" name="login" type="text" required
                        placeholder="Masukkan email atau nama akun"
                        value="{{ old('login') }}"
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-primary-darker dark:bg-darker dark:text-light rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition duration-200">
                </div>
            </div>

            <div>
                <label for="nomor_handphone" class="block text-sm font-medium text-text-primary mb-2">
                    Nomor WhatsApp terdaftar
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M20.52 3.48A11.8 11.8 0 0012.01.25C6.39.25 1.75 4.89 1.75 10.5c0 1.85.48 3.65 1.39 5.24L1 23l7.44-2.08a10.5 10.5 0 004.06.8h.01c5.62 0 10.25-4.64 10.25-10.25 0-2.74-1.07-5.32-3.04-7.29zM12.51 20.5h-.01a8.7 8.7 0 01-3.73-.86l-.27-.13-4.42 1.23 1.18-4.3-.14-.28a8.73 8.73 0 01-1.2-4.43c0-4.82 3.93-8.75 8.76-8.75 2.34 0 4.54.91 6.19 2.56a8.7 8.7 0 012.56 6.2c0 4.83-3.93 8.76-8.72 8.76zm4.79-6.53c-.26-.13-1.53-.76-1.77-.85-.24-.09-.42-.13-.6.13-.18.26-.69.85-.84 1.03-.15.17-.31.2-.57.07-.26-.13-1.1-.4-2.1-1.27-.78-.69-1.31-1.54-1.46-1.8-.15-.26-.02-.4.11-.53.12-.12.26-.31.39-.46.13-.15.17-.26.26-.43.09-.17.04-.32-.02-.45-.07-.13-.6-1.44-.82-1.96-.22-.52-.44-.45-.6-.46h-.51c-.17 0-.45.06-.69.32-.24.26-.9.88-.9 2.15 0 1.26.92 2.48 1.05 2.65.13.17 1.82 2.78 4.41 3.9.62.27 1.1.43 1.48.55.62.2 1.19.17 1.64.1.5-.08 1.53-.63 1.75-1.24.22-.61.22-1.13.15-1.24-.07-.11-.24-.18-.5-.31z">
                            </path>
                        </svg>
                    </div>
                    <input id="nomor_handphone" name="nomor_handphone" type="text" required
                        placeholder="Contoh: 62812xxxxxxx"
                        value="{{ old('nomor_handphone') }}"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-primary-darker dark:bg-darker dark:text-light rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition duration-200">
                </div>
                <p class="mt-1 text-xs text-text-secondary dark:text-gray-400">
                    Hanya masukkan angka. Pastikan nomor sama persis dengan yang digunakan saat registrasi.
                </p>
            </div>

            <button type="submit" :disabled="loading"
                class="w-full bg-blue-600 text-white font-semibold py-3 px-4 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none transition duration-200 transform hover:scale-[1.02] active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed">
                <span x-show="!loading">Lanjutkan ke WhatsApp Admin</span>
                <span x-show="loading" class="flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memproses...
                </span>
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('login') }}"
                class="text-sm text-text-secondary dark:text-light hover:text-blue-700 hover:underline transition duration-200">
                Kembali ke halaman login
            </a>
        </div>
    </div>
</body>

</html>

