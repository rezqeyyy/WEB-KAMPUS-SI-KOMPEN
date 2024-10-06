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
        Schema::table('tbl_mahasiswa', function (Blueprint $table) {
            $table->string('semester', 50)->nullable()->after('kelas'); // Adds the semester column after kelas
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_mahasiswa', function (Blueprint $table) {
            //
        });
    }
};
