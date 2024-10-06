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
        Schema::create('tbl_pengajuan_detail', function (Blueprint $table) {
            $table->id('id_pengajuan_detail');
            $table->string('kode_kegiatan', 50)->nullable(); // Ubah tipe data menjadi string
            $table->string('kode_pekerjaan', 50)->nullable(); // Job code
            $table->string('nama_pekerjaan', 50)->nullable(); // Job name
            $table->string('jam_pekerjaan', 50)->nullable(); // jumlah jam pekerjaan
            $table->string('batas_pekerja', 50)->nullable(); // limit pekerja
            $table->string('before_pekerjaan', 50)->nullable(); // Before status
            $table->string('after_pekerjaan', 50)->nullable(); // After status
            $table->string('bukti_tambahan', 50)->nullable(); // After status
            $table->string('user_create')->nullable();
            $table->string('user_update')->nullable();
            $table->uuid('uid')->nullable();
            $table->timestamps();


            $table->foreign('kode_kegiatan')->references('kode_kegiatan')->on('tbl_pengajuan'); // Merujuk kode_kegiatan ke kode_kegiatan di tbl_pengajuan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_pengajuan_detail');
    }
};
