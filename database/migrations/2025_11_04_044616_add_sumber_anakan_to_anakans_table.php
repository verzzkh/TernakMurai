<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('anakan', function (Blueprint $table) {
            $table->enum('sumber_anakan', ['internal', 'eksternal'])
                ->default('internal')
                ->comment('Menandakan asal anakan: internal = hasil ternak sendiri, eksternal = dari luar peternakan')
                ->after('perkawinan_id');
        });
    }

    public function down(): void
    {
        Schema::table('anakan', function (Blueprint $table) {
            $table->dropColumn('sumber_anakan');
        });
    }
};
