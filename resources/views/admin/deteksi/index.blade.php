<x-admin.layout title="Riwayat Evaluasi Breeding">
<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
    <div class="overflow-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="text-xs text-gray-500 uppercase">
                    <th class="px-3 py-2">Peternak</th>
                    <th class="px-3 py-2">Jantan</th>
                    <th class="px-3 py-2">Betina</th>
                    <th class="px-3 py-2">Rekomendasi</th>
                    <th class="px-3 py-2">Tanggal</th>
                    <th class="px-3 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deteksi as $row)
                    <tr>
                        <td class="px-3 py-2">{{ $row->peternak?->nama_peternakan ?? 'Anonymous' }}</td>
                        <td class="px-3 py-2">{{ $row->jantan?->nomor_ring ?? $row->jantan_id }}</td>
                        <td class="px-3 py-2">{{ $row->betina?->nomor_ring ?? $row->betina_id }}</td>
                        <td class="px-3 py-2">{{ strtoupper($row->rekomendasi ?? '-') }}</td>
                        <td class="px-3 py-2">{{ optional($row->tanggal_analisa ?? $row->created_at)->format('d M Y') }}</td>
                        <td class="px-3 py-2">
                            <a href="{{ route('admin.deteksi.show', $row->id) }}" class="px-2 py-1 text-sm text-primary">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-3 py-4 text-center text-gray-500">Tidak ada data evaluasi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

    <div class="mt-3">{{ $deteksi->links() }}</div>
</x-admin.layout>
