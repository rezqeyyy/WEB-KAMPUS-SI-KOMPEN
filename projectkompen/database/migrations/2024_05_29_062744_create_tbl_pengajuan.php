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
        Schema::create('tbl_pengajuan', function (Blueprint $table) {
            $table->id('id_pengajuan'); // Primary key with auto-increment
            $table->string('kode_kegiatan', 50)->nullable();
            $table->string('kode_user', 50)->nullable(); // Unique identifier
            $table->string('nama_user', 50)->nullable(); // Name of the applicant
            $table->string('kelas', 50)->nullable(); // Name of the applicant
            $table->string('semester', 50)->nullable(); // Name of the applicant
            $table->string('jumlah_terlambat', 50)->nullable(); // Number of late hours
            $table->string('jumlah_alfa', 50)->nullable(); // Number of absent hours
            $table->string('total', 50)->nullable(); // Hours worked
            $table->string('sisa', 50)->nullable(); // Remaining hours
            $table->text('keterangan')->nullable(); // Description
            $table->date('tanggal_pengajuan')->nullable(); // Submission date
            $table->string('status_approval1', 50)->default('Belum Disetujui')->nullable(); // First approval status
            $table->string('keterangan_approval1', 255)->nullable(); // First approval description
            $table->string('bukti_tambahan', 50)->nullable(); // Name of the applicant
            $table->string('approval1_by', 50)->nullable(); // First approval by
            $table->string('status_approval2', 50)->default('Belum Disetujui')->nullable(); // Second approval status
            $table->string('keterangan_approval2', 255)->nullable(); // Second approval description
            $table->string('approval2_by', 50)->nullable(); // Second approval by
            $table->string('status_approval3', 50)->default('Belum Disetujui')->nullable(); // Third approval status
            $table->string('keterangan_approval3', 255)->nullable(); // Third approval description
            $table->string('approval3_by', 50)->nullable(); // Third approval by
            $table->string('status', 50)->default('Belum Selesai')->nullable(); // Overall status
            $table->timestamps(); // Created and updated timestamps
            $table->string('user_create')->nullable(); // Created by
            $table->string('user_update')->nullable(); // Updated by
            $table->uuid('uid')->nullable(); // Nullable UUID

            // Index for kode_kegiatan if necessary
            $table->index('kode_kegiatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_pengajuan');
    }
};
