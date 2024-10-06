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
        Schema::create('tbl_pekerjaan', function (Blueprint $table) {
            $table->id('id_pekerjaan');
            $table->string('kode_pekerjaan', 50)->nullable();
            $table->string('nama_pekerjaan')->nullable();
            $table->string('jam_pekerjaan')->length(50)->nullable();

            $table->string('user_create')->nullable();
            $table->string('user_update')->nullable();
            $table->uuid('uid');

            $table->timestamps();
            $table->unique(['id_pekerjaan', 'kode_pekerjaan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_pekerjaan');
    }
};
