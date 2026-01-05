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
    Schema::create('hasil_analisa_breeding', function (Blueprint $table) {
        $table->bigIncrements('id');

        $table->unsignedBigInteger('peternak_id');
        $table->unsignedBigInteger('jantan_id');
        $table->unsignedBigInteger('betina_id');

        $table->longText('hasil_ai');                    // Markdown hasil analisa lengkap
        $table->enum('rekomendasi', ['uji_coba','lanjut','stop'])->default('uji_coba');

        $table->text('catatan_user')->nullable();        // Validasi lapangan setelah breeding
        $table->timestamp('tanggal_analisa')->useCurrent();

        $table->foreign('peternak_id')->references('id')->on('peternak')->onDelete('cascade');
        $table->foreign('jantan_id')->references('id')->on('indukan')->onDelete('cascade');
        $table->foreign('betina_id')->references('id')->on('indukan')->onDelete('cascade');

        $table->index(['peternak_id','jantan_id','betina_id']);
    });
}

public function down(): void
{
    Schema::dropIfExists('hasil_analisa_breeding');
}

};
