<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('indukan', function (Blueprint $table) {
            $table->boolean('aktif_kicau')->nullable()->after('foto_path');
            $table->boolean('mendekati_betina')->nullable()->after('aktif_kicau');
            $table->boolean('nafsu_makan_meningkat')->nullable()->after('mendekati_betina');
            $table->boolean('aktif_buat_sarang')->nullable()->after('nafsu_makan_meningkat');
            $table->enum('temperamen', ['jinak','sedang','fighter'])->nullable()->after('aktif_buat_sarang');
        });
    }

    public function down(): void
    {
        Schema::table('indukan', function (Blueprint $table) {
            $table->dropColumn([
                'aktif_kicau',
                'mendekati_betina',
                'nafsu_makan_meningkat',
                'aktif_buat_sarang',
                'temperamen',
            ]);
        });
    }
};
