<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeteksiPenyakitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deteksi_penyakit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('burung_id')->nullable()->constrained('burung')->onDelete('set null');
            $table->text('images'); // JSON encoded array of image paths
            $table->text('symptoms');
            $table->text('behaviors')->nullable(); // JSON encoded array of behaviors
            $table->text('diet_info')->nullable();
            $table->text('environment_info')->nullable();
            $table->text('health_history')->nullable();
            $table->longText('analysis_result'); // JSON encoded analysis result
            $table->string('primary_diagnosis');
            $table->integer('probability');
            $table->boolean('is_saved')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deteksi_penyakit');
    }
}