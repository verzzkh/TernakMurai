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
        Schema::table('anakan', function (Blueprint $table) {
            // Tambahkan harga beli setelah kolom harga
            $table->integer('harga_beli')->nullable()->after('harga');

            // Tambahkan asal penjual setelah harga_beli
            $table->string('asal_penjual')->nullable()->after('harga_beli');

            // Tambahkan info indukan jantan dan betina setelah perkawinan_id
            $table->string('indukan_jantan_info')->nullable()->after('perkawinan_id');
            $table->string('indukan_betina_info')->nullable()->after('indukan_jantan_info');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anakan', function (Blueprint $table) {
            // Hapus kolom-kolom yang baru ditambahkan
            $table->dropColumn([
                'harga_beli', 
                'asal_penjual', 
                'indukan_jantan_info', 
                'indukan_betina_info'
            ]);
        });
    }
};
