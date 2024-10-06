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
        Schema::create('tbl_form_bebas_kompen', function (Blueprint $table) {
            $table->id('id_bebas_kompen');
            $table->bigInteger('id_pengajuan')->nullable();
            $table->string('kode_user', 50)->nullable();
            $table->string('nama_user', 50)->nullable();
            $table->string('kelas', 50)->nullable();
            $table->string('prodi', 50)->nullable();
            $table->string('semester', 50)->nullable();
            $table->string('dosen_pembimbing_akademik', 50)->nullable();
            $table->string('form_bebas_kompen')->nullable();
            $table->string('status_approval1', 50)->nullable(); // First approval status
            $table->string('approval1_by', 50)->nullable(); // First approval by
            $table->string('status_approval2', 50)->nullable(); // First approval status
            $table->string('approval2_by', 50)->nullable(); // First approval by
            $table->string('status_approval3', 50)->nullable(); // First approval status
            $table->string('approval3_by', 50)->nullable(); // First approval by

            $table->uuid('uid')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_form_bebas_kompen');
    }
};
