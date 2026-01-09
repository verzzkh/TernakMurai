<!DOCTYPE html>
<html lang="en" x-data="{ isDark: false }" :class="{ 'dark': isDark }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Ternak Murai</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.3/dist/alpine.min.js" defer></script>
</head>

<body
    class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-slate-900 dark:to-slate-800 flex items-center justify-center p-4">
    <div
        class="max-w-lg w-full bg-white dark:bg-darker rounded-2xl shadow-xl p-8 border border-gray-200 dark:border-primary-darker">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-text-primary">Daftar Peternak</h1>
            <p class="text-sm text-text-secondary">Buat akun peternak untuk mengelola kandang dan anakan</p>
        </div>
        <p class="text-xs text-gray-500 mb-4">
            <span class="text-red-500">*</span> wajib diisi
        </p>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 text-sm p-3 rounded-lg mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-text-primary mb-2">
                    Nama Pengguna (login) <span class="text-red-500">*</span>
                </label>

                <input id="name" name="name" type="text" value="" required
                    placeholder="contoh: peternak123"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">

                <p class="text-xs text-gray-500 mt-1">
                    Gunakan huruf dan angka, tanpa spasi
                </p>
            </div>


            <div>
                <label for="email" class="block text-sm font-medium text-text-primary mb-2">Email (opsional)</label>
                <input id="email" name="email" type="email" value=""
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-text-primary mb-2">
                    Password <span class="text-red-500">*</span>
                </label>

                <input id="password" name="password" type="password" required placeholder="Minimal 6 karakter"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">

                <p class="text-xs text-gray-500 mt-1">
                    Minimal 6 karakter
                </p>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-text-primary mb-2">
                    Konfirmasi Password <span class="text-red-500">*</span>
                </label>

                <input id="password_confirmation" name="password_confirmation" type="password" required
                    placeholder="Ulangi password"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <hr class="my-2">

            <div>
                <label for="nama_peternakan" class="block text-sm font-medium text-text-primary mb-2">
                    Nama Peternakan <span class="text-red-500">*</span>
                </label>

                <input id="nama_peternakan" name="nama_peternakan" type="text" value="" required
                    placeholder="contoh: Murai Jaya Farm"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="alamat" class="block text-sm font-medium text-text-primary mb-2">Alamat</label>
                <textarea id="alamat" name="alamat" rows="2"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('alamat') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-text-primary mb-2">
                    Nomor Handphone (WA)<span class="text-red-500">*</span>
                </label>

                <input name="nomor_handphone" value="" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none
           @error('nomor_handphone') border-red-500 @enderror">

                @error('nomor_handphone')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>


            <div>
                <label class="block text-sm font-medium text-text-primary mb-2">
                    Foto Profil (opsional)
                </label>
                <input type="file" name="foto_profil"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">
                    JPG / PNG, maksimal 2MB
                </p>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white font-semibold py-3 px-4 rounded-lg hover:bg-blue-700">
                Daftar Sekarang
            </button>
        </form>

        <div class="mt-4 text-center text-sm">
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Sudah punya akun? Masuk</a>
        </div>

    </div>

    <div class="fixed top-4 right-4">
        <button @click="isDark = !isDark"
            class="p-3 bg-white dark:bg-darker rounded-full shadow-lg border border-gray-200 dark:border-primary-darker hover:shadow-xl transition-all duration-200">
            <svg x-show="!isDark" class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
            <svg x-show="isDark" class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
            </svg>
        </button>
    </div>
</body>

</html>
