<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perkawinan', function (Blueprint $table) {
            $table->string('nomor_trip', 50)->change();
        });
    }

    public function down(): void
    {
        Schema::table('perkawinan', function (Blueprint $table) {
            $table->integer('nomor_trip')->change();
        });
    }
};
