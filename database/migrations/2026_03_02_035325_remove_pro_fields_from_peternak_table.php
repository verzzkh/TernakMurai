<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peternak', function (Blueprint $table) {

            $table->dropColumn([
                'jenis_akun',
                'pro_berlaku_hingga',
                'periode_deteksi',
                'deteksi_terpakai'
            ]);

        });
    }

    public function down(): void
    {
        Schema::table('peternak', function (Blueprint $table) {

            $table->string('jenis_akun')->nullable();
            $table->date('pro_berlaku_hingga')->nullable();
            $table->string('periode_deteksi')->nullable();
            $table->integer('deteksi_terpakai')->default(0);

        });
    }
};