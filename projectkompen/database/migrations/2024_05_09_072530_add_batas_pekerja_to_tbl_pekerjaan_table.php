<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBatasPekerjaToTblPekerjaanTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tbl_pekerjaan', function (Blueprint $table) {
            $table->integer('batas_pekerja')->nullable()->after('jam_pekerjaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_pekerjaan', function (Blueprint $table) {
            $table->dropColumn('batas_pekerja');
        });
    }
}

