<x-admin.layout title="Evaluasi #{{ $deteksi->id }}">

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="font-semibold text-lg">Evaluasi #{{ $deteksi->id }}</h3>
                <div class="text-sm text-gray-500">Peternak: {{ $deteksi->peternak?->nama_peternakan ?? 'Anonymous' }}</div>
                <div class="text-xs text-gray-400">{{ optional($deteksi->tanggal_analisa ?? $deteksi->created_at)->format('d M Y H:i') }}</div>
            </div>
            <div>
                <a href="{{ route('admin.deteksi.index') }}" class="text-sm text-primary">Back to list</a>
            </div>
        </div>

        <div class="mt-4">
            <div class="mb-4">
                <p><strong>Jantan:</strong> {{ $deteksi->jantan?->nomor_ring ?? $deteksi->jantan_id }} - {{ $deteksi->jantan?->nama ?? '-' }}</p>
                <p><strong>Betina:</strong> {{ $deteksi->betina?->nomor_ring ?? $deteksi->betina_id }} - {{ $deteksi->betina?->nama ?? '-' }}</p>
                <p><strong>Rekomendasi:</strong> {{ strtoupper($deteksi->rekomendasi ?? '-') }}</p>
            </div>

            <div class="mb-4">
                <h4 class="font-semibold mb-2">Hasil Analisa AI</h4>
                <div class="prose text-sm">
                    {!! Illuminate\Support\Str::markdown($deteksi->hasil_ai ?? '') !!}
                </div>
            </div>

            <div class="mb-4">
                <h4 class="font-semibold mb-2">Catatan Peternak</h4>
                <div class="text-sm text-gray-700">{{ $deteksi->catatan_user ?? '-' }}</div>
            </div>

            <div class="mb-4">
                <h4 class="font-semibold mb-2">Tindak Lanjut Peternak</h4>
                <div class="text-sm text-gray-700">{{ $deteksi->tindak_lanjut_peternak ?? '-' }}</div>
            </div>
        </div>

    </div>

</x-admin.layout>
