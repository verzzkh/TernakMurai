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
        Schema::create('deteksi_penyakit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peternak_id')->constrained('peternak')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nama_burung', 100)->nullable();
            $table->string('foto_path')->nullable();
            $table->text('gejala')->nullable();
            $table->text('perilaku')->nullable();
            $table->text('riwayat_kesehatan')->nullable();
            $table->text('lingkungan')->nullable();
            $table->text('makanan')->nullable();
            $table->longText('hasil_analisis')->nullable();
            $table->string('diagnosis_utama')->nullable();
            $table->integer('tingkat_kepercayaan')->nullable();
            $table->text('rekomendasi')->nullable();
            $table->boolean('is_saved')->default(false);
            $table->timestamps();

            $table->index(['peternak_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deteksi_penyakit');
    }
};
