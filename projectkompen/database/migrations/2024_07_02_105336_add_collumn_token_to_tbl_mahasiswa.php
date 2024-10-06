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
            $table->string('token', 50)->after('edit_password')->nullable();
            $table->integer('status_token')->default('0')->after('token');
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
