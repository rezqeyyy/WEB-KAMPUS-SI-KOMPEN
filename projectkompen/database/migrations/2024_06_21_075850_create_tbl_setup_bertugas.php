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
        Schema::create('tbl_setup_bertugas', function (Blueprint $table) {
            $table->id('id_setup_bertugas');
            $table->bigInteger('id_user')->nullable();
            $table->string('kode_user', 50)->nullable();
            $table->string('nama_user', 50)->nullable();
            $table->string('role', 50)->nullable();
            $table->dateTime('tgl_bertugas')->nullable();

            $table->string('user_create')->nullable();
            $table->string('user_update')->nullable();
            $table->uuid('uid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_setup_bertugas');
    }
};
