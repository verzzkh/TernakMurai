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
        Schema::create('peternak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('nama_peternakan', 100);
            $table->text('alamat')->nullable();
            $table->string('nomor_handphone', 30)->nullable();
            $table->string('foto_profil')->nullable();
            $table->enum('jenis_akun', ['free', 'pro'])->default('free');
            $table->date('pro_berlaku_hingga')->nullable();
            $table->char('periode_deteksi', 7)->default('2025-10');
            $table->integer('deteksi_terpakai')->default(0);
            $table->timestamps();

            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peternak');
    }
};
