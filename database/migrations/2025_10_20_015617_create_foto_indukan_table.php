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
        Schema::create('foto_indukan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peternak_id')->constrained('peternak')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('indukan_id')->constrained('indukan')->onDelete('cascade')->onUpdate('cascade');
            $table->string('path');
            $table->string('caption', 200)->nullable();
            $table->boolean('is_cover')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['peternak_id', 'indukan_id']);
            $table->index(['indukan_id', 'is_cover']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foto_indukan');
    }
};
