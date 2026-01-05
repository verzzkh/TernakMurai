<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk menambah kolom indukan pada tabel anakan.
     */
    public function up(): void
    {
        Schema::table('anakan', function (Blueprint $table) {
            // Tambahkan dua kolom indukan (nullable untuk menjaga data lama tetap aman)
            $table->foreignId('indukan_jantan_id')
                ->nullable()
                ->after('perkawinan_id')
                ->constrained('indukan')
                ->nullOnDelete();

            $table->foreignId('indukan_betina_id')
                ->nullable()
                ->after('indukan_jantan_id')
                ->constrained('indukan')
                ->nullOnDelete();
        });
    }

    /**
     * Batalkan perubahan migrasi ini (rollback).
     */
    public function down(): void
    {
        Schema::table('anakan', function (Blueprint $table) {
            // Hapus relasi dan kolom jika rollback dilakukan
            $table->dropForeign(['indukan_jantan_id']);
            $table->dropForeign(['indukan_betina_id']);
            $table->dropColumn(['indukan_jantan_id', 'indukan_betina_id']);
        });
    }
};
