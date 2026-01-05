<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk menambahkan kolom peternak_id ke tabel perkawinan.
     */
    public function up(): void
    {
        Schema::table('perkawinan', function (Blueprint $table) {
            // Tambahkan kolom peternak_id (nullable dulu supaya aman untuk data lama)
            if (!Schema::hasColumn('perkawinan', 'peternak_id')) {
                $table->foreignId('peternak_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('peternak')
                    ->cascadeOnDelete();
            }
        });
    }

    /**
     * Rollback migrasi (hapus kolom peternak_id).
     */
    public function down(): void
    {
        Schema::table('perkawinan', function (Blueprint $table) {
            if (Schema::hasColumn('perkawinan', 'peternak_id')) {
                $table->dropForeign(['peternak_id']);
                $table->dropColumn('peternak_id');
            }
        });
    }
};
