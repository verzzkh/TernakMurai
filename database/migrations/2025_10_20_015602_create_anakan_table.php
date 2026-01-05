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
        Schema::create('anakan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peternak_id')->constrained('peternak')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('kandang_id')->constrained('kandang')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('perkawinan_id')->nullable()->constrained('perkawinan')->onDelete('set null')->onUpdate('cascade');
            $table->string('nomor_ring', 50)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['jantan', 'betina', 'tidak_diketahui'])->default('tidak_diketahui');
            $table->enum('status_pertumbuhan', ['trotol', 'pastol', 'lomba'])->default('trotol');
            $table->text('deskripsi_karakteristik')->nullable();
            $table->text('catatan_perubahan')->nullable();
            $table->text('catatan_penjualan')->nullable();
            $table->integer('harga')->nullable();
            $table->enum('status_penjualan', ['belum_dijual', 'terjual'])->default('belum_dijual');
            $table->date('tanggal_jual')->nullable();
            $table->string('foto_path')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['peternak_id', 'nomor_ring']);
            $table->index(['peternak_id', 'status_penjualan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anakan');
    }
};
