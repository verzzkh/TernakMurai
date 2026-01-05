<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peternak_id')->constrained('peternak')->onDelete('cascade')->onUpdate('cascade');
            $table->date('tanggal');
            $table->enum('tipe', ['pemasukan', 'pengeluaran']);
            $table->enum('kategori', [
                'penjualan_anakan',
                'penjualan_indukan',
                'pemasukan_lainnya',
                'pakan',
                'vitamin',
                'perawatan',
                'pengeluaran_lainnya',
            ]);
            $table->decimal('jumlah', 12, 2);
            $table->string('nama_item', 120)->nullable();
            $table->string('deskripsi', 255)->nullable();
            $table->foreignId('anakan_id')->nullable()->constrained('anakan')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('indukan_id')->nullable()->constrained('indukan')->onDelete('set null')->onUpdate('cascade');
            $table->string('ring_referensi', 50)->nullable();
            $table->timestamps();

            $table->index(['peternak_id', 'tanggal']);
            $table->index(['peternak_id', 'tipe', 'kategori']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
