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
        Schema::create('tbl_mahasiswa', function (Blueprint $table) {
            $table->id('id_mahasiswa');
            $table->string('kode_user', 50)->unique()->nullable();
            $table->string('nama_user', 50)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('prodi', 50)->nullable();
            $table->string('kelas', 50)->nullable();
            $table->string('notelp', 50)->nullable();
            $table->string('password', 255)->nullable();
            $table->string('role', 50)->default('Mahasiswa');
            $table->string('jumlah_terlambat', 50)->nullable();
            $table->string('jumlah_alfa', 50)->nullable();
            $table->string('total')->nullable();
            $table->string('user_create')->nullable();
            $table->string('user_update')->nullable();
            $table->uuid('uid')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_mahasiswa');
    }
};


