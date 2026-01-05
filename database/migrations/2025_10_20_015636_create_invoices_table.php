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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peternak_id')->constrained('peternak')->onDelete('cascade')->onUpdate('cascade');
            $table->date('periode_mulai');
            $table->date('periode_selesai');
            $table->integer('harga')->default(25000);
            $table->enum('status', ['pending', 'paid', 'rejected'])->default('pending');
            $table->enum('metode', ['manual', 'midtrans'])->default('manual');
            $table->string('bukti_path')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['peternak_id', 'status']);
            $table->index(['peternak_id', 'periode_mulai', 'periode_selesai']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
