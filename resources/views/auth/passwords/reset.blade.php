<!DOCTYPE html>
<html lang="en" x-data="{ isDark: false }" :class="{ 'dark': isDark }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password - Ternak Murai</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.3/dist/alpine.min.js" defer></script>
</head>

<body
    class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-slate-900 dark:to-slate-800 flex items-center justify-center p-4">
    <div
        class="max-w-md w-full bg-white dark:bg-darker rounded-2xl shadow-xl p-8 border border-gray-200 dark:border-primary-darker">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-text-primary mb-2">Ganti Password</h1>
            <p class="text-sm text-text-secondary dark:text-light">
                Masukkan password baru untuk akun Anda.
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

        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email" class="block text-sm font-medium text-text-primary mb-2">
                    Email akun
                </label>
                <input id="email" name="email" type="email" readonly
                    value="{{ $email }}"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-primary-darker dark:bg-darker dark:text-light rounded-lg bg-gray-100 dark:bg-slate-700 cursor-not-allowed">
                <p class="mt-1 text-xs text-text-secondary dark:text-gray-400">
                    Email ini digunakan untuk verifikasi token reset password.
                </p>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-text-primary mb-2">
                    Password baru
                </label>
                <input id="password" name="password" type="password" required
                    autocomplete="new-password"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-primary-darker dark:bg-darker dark:text-light rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-text-primary mb-2">
                    Konfirmasi password baru
                </label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    autocomplete="new-password"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-primary-darker dark:bg-darker dark:text-light rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white font-semibold py-3 px-4 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none transition duration-200 transform hover:scale-[1.02] active:scale-[0.98]">
                Simpan Password Baru
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

