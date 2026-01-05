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
        Schema::create('perkawinan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kandang_id')->constrained('kandang')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('indukan_jantan_id')->nullable()->constrained('indukan')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('indukan_betina_id')->nullable()->constrained('indukan')->onDelete('set null')->onUpdate('cascade');
            $table->integer('nomor_trip');
            $table->date('tanggal_kawin')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['kandang_id', 'nomor_trip']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perkawinan');
    }
};
