<x-admin.layout title="Riwayat Deteksi Penyakit">
<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
    <div class="overflow-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="text-xs text-gray-500 uppercase">
                    <th class="px-3 py-2">User</th>
                    <th class="px-3 py-2">Tanggal</th>
                    <th class="px-3 py-2">Hasil Deteksi</th>
                    <th class="px-3 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deteksi as $row)
                    <tr>
                        <td class="px-3 py-2">{{ $row->peternak?->nama_peternakan ?? 'Anonymous' }}</td>
                        <td class="px-3 py-2">{{ $row->created_at->format('d M Y') }}</td>
                        <td class="px-3 py-2">{{ \Illuminate\Support\Str::limit(is_array($row->hasil_analisis) ? implode(', ', $row->hasil_analisis) : ($row->diagnosis_utama ?? '-'), 80) }}</td>
                        <td class="px-3 py-2">
                            <a href="{{ route('admin.deteksi.show', $row->id) }}" class="px-2 py-1 text-sm text-primary">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-3 py-4 text-center text-gray-500">No detection records</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

    <div class="mt-3">{{ $deteksi->links() }}</div>
</x-admin.layout>
