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
        Schema::create('indukan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peternak_id')->constrained('peternak')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nomor_ring', 50);
            $table->string('nama', 100)->nullable();
            $table->enum('jenis_kelamin', ['jantan', 'betina']);
            $table->date('tanggal_lahir')->nullable();
            $table->text('catatan')->nullable();
            $table->text('prestasi')->nullable();
            $table->text('karakteristik')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['peternak_id', 'nomor_ring']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indukan');
    }
};
