<x-admin.layout title="User Management">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Users</h3>
            <form method="GET" class="flex items-center space-x-2">
                <input name="q" value="{{ $q ?? '' }}" type="text" placeholder="Search users or farm name..." class="px-3 py-2 border rounded-md text-sm">
                <button class="px-3 py-2 bg-primary text-white rounded-md text-sm">Filter</button>
            </form>
        </div>

        <div class="overflow-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="text-xs text-gray-500 uppercase">
                        <th class="px-3 py-2">Nama</th>
                        <th class="px-3 py-2">Username</th>
                        <th class="px-3 py-2">Total Kandang</th>
                        <th class="px-3 py-2">Total Indukan</th>
                        <th class="px-3 py-2">Total Anakan</th>
                        
                        <th class="px-3 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                        @forelse($peternaks as $p)
                            <tr>
                                <td class="px-3 py-2">
                                    <div class="font-medium">{{ $p->user?->name ?? '—' }}</div>
                                    <div class="text-xs text-gray-500">{{ $p->nama_peternakan ?? '-' }}</div>
                                </td>
                                <td class="px-3 py-2">{{ $p->user?->username ?? $p->user?->email ?? '-' }}</td>
                                <td class="px-3 py-2">{{ number_format($p->kandangs_count ?? 0) }}</td>
                                <td class="px-3 py-2">{{ number_format($p->indukans_count ?? 0) }}</td>
                                <td class="px-3 py-2">{{ number_format($p->anakans_count ?? 0) }}</td>
                                
                                <td class="px-3 py-2">
                                    <a href="#" class="px-2 py-1 text-sm text-primary">View</a>
                                    <a href="#" class="px-2 py-1 text-sm text-red-500">Suspend</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-3 py-4 text-center text-gray-500">No users found.</td>
                            </tr>
                        @endforelse
                </tbody>
            </table>
        </div>

            <div class="mt-3">{{ $peternaks->links() }}</div>
    </div>

</x-admin.layout>
