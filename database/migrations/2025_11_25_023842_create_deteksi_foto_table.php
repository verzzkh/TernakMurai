<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deteksi_foto', function (Blueprint $table) {
            $table->id();

            $table->foreignId('deteksi_id')
                  ->constrained('deteksi_penyakit')
                  ->onDelete('cascade');

            $table->string('foto_path'); // storage/deteksi/xxxx.jpg

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deteksi_foto');
    }
};
