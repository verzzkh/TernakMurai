<x-admin.layout title="Paket & Kuota">
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <h3 class="text-lg font-semibold mb-3">Pengaturan Paket</h3>
        <div class="flex items-center gap-4 mb-3">
            <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 rounded">
                <div class="text-xs text-gray-500">Pro Users</div>
                <div class="font-semibold text-lg">{{ number_format($summary['pro'] ?? 0) }}</div>
            </div>
            <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 rounded">
                <div class="text-xs text-gray-500">Free Users</div>
                <div class="font-semibold text-lg">{{ number_format($summary['free'] ?? 0) }}</div>
            </div>
        </div>
        <div class="space-y-2 text-sm text-gray-500">
            <div>Batas kandang/anakan FREE: <strong>30</strong></div>
            <div>Kuota deteksi FREE: <strong>100</strong></div>
            <div>Kuota deteksi PRO: <strong>unlimited</strong></div>
            <div>Masa aktif PRO: <strong>30 hari</strong></div>
            <div>Harga PRO: <strong>Rp xxx</strong></div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <h3 class="text-lg font-semibold mb-3">Kuota User</h3>

        <div class="overflow-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="text-xs text-gray-500 uppercase">
                        <th class="px-3 py-2">Nama</th>
                        <th class="px-3 py-2">Email</th>
                        <th class="px-3 py-2">Paket</th>
                        <th class="px-3 py-2">Masa Aktif</th>
                        <th class="px-3 py-2">Deteksi Dipakai</th>
                        <th class="px-3 py-2">Sisa Kuota</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peternaks as $p)
                        <tr>
                            <td class="px-3 py-2">{{ $p->user?->name ?? $p->nama_peternakan }}</td>
                            <td class="px-3 py-2">{{ $p->user?->email ?? '-' }}</td>
                            <td class="px-3 py-2">{{ ucfirst($p->jenis_akun) }}</td>
                            <td class="px-3 py-2">{{ optional($p->pro_berlaku_hingga)->format('d M Y') ?? '-' }}</td>
                            <td class="px-3 py-2">{{ $p->deteksi_terpakai ?? 0 }}</td>
                            <td class="px-3 py-2">
                                @if($p->jenis_akun === 'pro') Unlimited @else {{ max(0, 100 - ($p->deteksi_terpakai ?? 0)) }} / 100 @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-3 py-4 text-center text-gray-500">No peternak data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">{{ $peternaks->links() }}</div>
        </div>
    </div>
</div>

</x-admin.layout>
