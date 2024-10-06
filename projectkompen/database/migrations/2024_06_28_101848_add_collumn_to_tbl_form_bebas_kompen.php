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
        Schema::table('tbl_form_bebas_kompen', function (Blueprint $table) {
            $table->string('jumlah_terlambat', '50')->nullable()->after('semester');
            $table->string('jumlah_alfa', '50')->nullable()->after('jumlah_terlambat');
            $table->string('total', '50')->nullable()->after('jumlah_alfa');
            $table->string('sisa', '50')->nullable()->after('total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_form_bebas_kompen', function (Blueprint $table) {
            //
        });
    }
};
