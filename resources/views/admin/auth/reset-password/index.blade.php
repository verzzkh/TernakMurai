<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password via Admin - Ternak Murai</title>
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-slate-50 dark:bg-slate-900">
    <div class="max-w-6xl mx-auto py-8 px-4">
        <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-4">
            Reset Password Pengguna via WhatsApp
        </h1>
        <p class="text-sm text-slate-600 dark:text-slate-300 mb-6">
            Cari akun peternak lalu generate link reset password. Kirimkan link tersebut ke pengguna melalui WhatsApp.
        </p>

        @if (session('reset_link_url'))
            <div
                class="mb-6 bg-green-50 border border-green-200 text-green-800 dark:bg-green-900/40 dark:border-green-800 dark:text-green-100 rounded-lg p-4 text-sm space-y-2">
                <p class="font-semibold">Link reset password berhasil dibuat.</p>
                <p class="break-all">
                    <span class="font-mono text-xs">{{ session('reset_link_url') }}</span>
                </p>
                <p class="text-xs text-green-900/70 dark:text-green-100/80">
                    Salin link di atas dan kirimkan secara manual kepada pengguna melalui WhatsApp.
                </p>
            </div>
        @endif

        <form method="GET" action="{{ route('admin.reset-password.index') }}"
            class="mb-6 flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
            <input type="text" name="q" value="{{ $search }}"
                placeholder="Cari nama, email, nama peternakan, atau nomor WhatsApp"
                class="flex-1 px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                Cari
            </button>
        </form>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
            <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-100">Hasil Pencarian</h2>
                <span class="text-xs text-slate-500 dark:text-slate-400">
                    {{ $users->count() }} akun ditemukan
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-900/70">
                        <tr>
                            <th class="px-4 py-2 text-left font-medium text-slate-600 dark:text-slate-300">Nama</th>
                            <th class="px-4 py-2 text-left font-medium text-slate-600 dark:text-slate-300">Email</th>
                            <th class="px-4 py-2 text-left font-medium text-slate-600 dark:text-slate-300">
                                Peternakan / No WhatsApp
                            </th>
                            <th class="px-4 py-2 text-right font-medium text-slate-600 dark:text-slate-300">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @forelse ($users as $user)
                            <tr>
                                <td class="px-4 py-2 text-slate-800 dark:text-slate-100">
                                    {{ $user->name }}
                                </td>
                                <td class="px-4 py-2 text-slate-700 dark:text-slate-200">
                                    {{ $user->email ?: '-' }}
                                </td>
                                <td class="px-4 py-2 text-slate-700 dark:text-slate-200">
                                    <div class="flex flex-col">
                                        <span>{{ optional($user->peternak)->nama_peternakan ?? '-' }}</span>
                                        <span class="text-xs text-slate-500">
                                            WA: {{ optional($user->peternak)->nomor_handphone ?? '-' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-2 text-right">
                                    <form method="POST"
                                        action="{{ route('admin.reset-password.generate', ['user' => $user->id, 'q' => $search]) }}"
                                        class="inline">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-lg bg-amber-500 hover:bg-amber-600 text-white focus:outline-none focus:ring-2 focus:ring-amber-500">
                                            Generate Link Reset
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-center text-sm text-slate-500 dark:text-slate-400">
                                    Belum ada hasil. Silakan masukkan kata kunci pencarian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>

