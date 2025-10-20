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
        Schema::create('kandang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peternak_id')->constrained('peternak')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nomor_kandang', 30);
            $table->text('deskripsi_kandang')->nullable();
            $table->enum('status', ['kosong', 'bertelur', 'mengeram', 'menetas'])->default('kosong');
            $table->foreignId('indukan_jantan_id')->nullable()->constrained('indukan')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('indukan_betina_id')->nullable()->constrained('indukan')->onDelete('set null')->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['peternak_id', 'nomor_kandang']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kandang');
    }
};
