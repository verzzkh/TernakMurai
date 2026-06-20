<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Hapus tabel-tabel yang hanya dipakai fitur deteksi penyakit (fitur sudah dihapus dari sistem).
        Schema::dropIfExists('deteksi_foto');
        Schema::dropIfExists('deteksi_penyakit');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Intentionally left empty: this migration is a cleanup for removed features.
        // Re-creating full schemas should be done by re-introducing the original migrations.
    }
};
