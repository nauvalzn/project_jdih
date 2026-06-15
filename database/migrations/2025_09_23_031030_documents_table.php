<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('documents', function (Blueprint $table) {
        $table->id();
        $table->string('pdf_file');
        $table->string('tipe_dokumen')->nullable();
        $table->string('bidang_hukum')->nullable();
        $table->string('jenis_hukum')->nullable();
        $table->string('jenis_dokumen')->nullable();
        $table->string('tahun')->nullable();
        $table->string('judul');
        $table->string('tempat_penetapan')->nullable();
        $table->date('tanggal_penetapan')->nullable();
        $table->date('tanggal_pengundangan')->nullable();
        $table->string('sumber')->nullable();
        $table->string('subjek')->nullable();
        $table->string('bahasa')->nullable();
        $table->string('lokasi')->nullable();
        $table->string('urusan_pemerintahan')->nullable();
        $table->string('penandatanganan')->nullable();
        $table->string('pemrakarsa')->nullable();
        $table->tinyInteger('status')->default(2); 
// 2 = berlaku, 0 = tidak berlaku, 1 = berlaku sebagian
        $table->string('keterangan')->nullable();
        $table->string('keterangan_dokumen')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::dropIfExists('documents');
}

};
