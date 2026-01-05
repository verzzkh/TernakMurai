<x-admin.layout title="Deteksi #{{ $deteksi->id }}">

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="font-semibold text-lg">{{ $deteksi->nama_burung ?? 'Nama Burung' }}</h3>
                <div class="text-sm text-gray-500">Pelapor: {{ $deteksi->peternak?->nama_peternakan ?? 'Anonymous' }}</div>
                <div class="text-xs text-gray-400">{{ $deteksi->created_at->format('d M Y H:i') }}</div>
            </div>
            <div>
                <a href="{{ route('admin.deteksi.index') }}" class="text-sm text-primary">Back to list</a>
            </div>
        </div>

        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2 space-y-2">
                <h4 class="font-semibold">Diagnosis</h4>
                <div class="text-sm text-gray-600 dark:text-gray-300">{{ $deteksi->diagnosis_utama ?? \Illuminate\Support\Str::limit(implode(', ', (array) $deteksi->hasil_analisis ?? []), 400) }}</div>

                <h4 class="mt-4 font-semibold">Gejala & Riwayat</h4>
                <div class="text-sm text-gray-600 dark:text-gray-300">
                    <p><b>Gejala:</b> {{ $deteksi->gejala ?? '-' }}</p>
                    <p><b>Perilaku:</b> {{ is_array($deteksi->perilaku) ? implode(', ', $deteksi->perilaku) : ($deteksi->perilaku ?? '-') }}</p>
                    <p><b>Riwayat:</b> {{ $deteksi->riwayat_kesehatan ?? '-' }}</p>
                </div>
            </div>

            <div class="space-y-4">
                <h4 class="font-semibold">Foto</h4>
                @if($deteksi->fotos->isNotEmpty())
                    <div class="space-y-2">
                        @foreach($deteksi->fotos as $foto)
                            <img src="{{ asset('storage/' . ($foto->foto_path ?? '') ) }}" class="w-full rounded" alt="deteksi foto"/>
                        @endforeach
                    </div>
                @else
                    <div class="text-sm text-gray-500">No photos</div>
                @endif
            </div>
        </div>

    </div>

</x-admin.layout>
