<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hasil_analisa_breeding', function (Blueprint $table) {
            // Tambah kolom timestamps
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('hasil_analisa_breeding', function (Blueprint $table) {
            $table->dropColumn(['created_at','updated_at']);
        });
    }
};
