<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('admin', 'password')) {
            Schema::table('admin', function (Blueprint $table) {
                $table->string('password', 255)->nullable()->after('username');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('admin', 'password')) {
            Schema::table('admin', function (Blueprint $table) {
                $table->dropColumn('password');
            });
        }
    }
};
