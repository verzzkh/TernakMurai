<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Deteksi Penyakit</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }
        h1 {
            text-align: center;
            margin-bottom: 10px;
            font-size: 20px;
        }
        h2 {
            margin-top: 20px;
            margin-bottom: 8px;
            padding-bottom: 3px;
            border-bottom: 1px solid #888;
            font-size: 16px;
        }
        .label { font-weight: bold; }
        .photos img {
            width: 150px;
            height: auto;
            margin-right: 8px;
            margin-bottom: 8px;
            border: 1px solid #ccc;
            padding: 2px;
        }
        .section { margin-bottom: 12px; }
    </style>
</head>
<body>

    <h1>Laporan Deteksi Penyakit Murai Batu</h1>

    <p><strong>ID Laporan:</strong> #{{ $deteksi->id }}</p>
    <p><strong>Tanggal:</strong> {{ $deteksi->created_at->format('d M Y') }}</p>

    {{-- Foto Burung removed --}}

    <h2>Informasi Dasar</h2>
    <p><span class="label">Nama Burung:</span> {{ $deteksi->nama_burung ?? '-' }}</p>
    <p><span class="label">Gejala:</span> {{ $deteksi->gejala }}</p>
    <p><span class="label">Perilaku:</span>
        {{ implode(', ', json_decode($deteksi->perilaku ?? '[]', true)) }}
    </p>
    <p><span class="label">Pakan:</span> {{ $deteksi->makanan }}</p>
    <p><span class="label">Lingkungan:</span> {{ $deteksi->lingkungan }}</p>
    <p><span class="label">Riwayat Kesehatan:</span> {{ $deteksi->riwayat_kesehatan }}</p>

    <h2>Diagnosis</h2>
    <p><span class="label">Diagnosis Utama:</span> {{ $deteksi->diagnosis_utama }}</p>
    <p><span class="label">Tingkat Kepercayaan:</span> {{ $deteksi->tingkat_kepercayaan }}%</p>

    <h2>Analisis Lengkap</h2>
    <p>{!! nl2br(e($deteksi->hasil_analisis)) !!}</p>

</body>
</html>
