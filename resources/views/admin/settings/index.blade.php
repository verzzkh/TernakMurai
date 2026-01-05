<x-admin.layout title="Pengaturan Sistem">
<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 max-w-3xl">
    <form class="space-y-4" method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        <div>
            <label class="text-sm">Nama Aplikasi</label>
            <input name="app_name" type="text" class="w-full px-3 py-2 border rounded-md" value="{{ $settings['app_name'] ?? config('app.name') }}">
        </div>

        <div>
            <label class="text-sm">API Key OpenAI</label>
            <input name="openai_key" type="text" class="w-full px-3 py-2 border rounded-md" value="{{ $settings['openai_key'] ?? '' }}">
        </div>

        <div>
            <label class="text-sm">Email Admin</label>
            <input name="admin_email" type="email" class="w-full px-3 py-2 border rounded-md" value="{{ $settings['admin_email'] ?? config('mail.from.address') }}">
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md">Simpan</button>
        </div>
    </form>

    @if(session('success'))
        <div class="mt-3 p-3 rounded bg-green-50 text-green-700">{{ session('success') }}</div>
    @endif
</div>

</x-admin.layout>
