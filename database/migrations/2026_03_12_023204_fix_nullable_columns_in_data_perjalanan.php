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
        Schema::table('data_perjalanan', function (Blueprint $table) {
            $table->integer('tiket_pergi')->default(0)->change();
            $table->integer('tiket_pulang')->default(0)->change();
            $table->integer('hotel')->default(0)->change();
            $table->integer('uang_harian')->default(0)->change();
            $table->integer('uang_representasi')->default(0)->change();
            $table->integer('transport_lokal_pergi')->default(0)->change();
            $table->integer('transport_lokal_pulang')->default(0)->change();
            $table->integer('bbm_tol')->default(0)->change();
            $table->integer('jumlah_sppd')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_perjalanan', function (Blueprint $table) {
            $table->integer('tiket_pergi')->nullable()->change();
            $table->integer('tiket_pulang')->nullable()->change();
            $table->integer('hotel')->nullable()->change();
            $table->integer('uang_harian')->nullable()->change();
            $table->integer('uang_representasi')->nullable()->change();
            $table->integer('transport_lokal_pergi')->nullable()->change();
            $table->integer('transport_lokal_pulang')->nullable()->change();
            $table->integer('bbm_tol')->nullable()->change();
            $table->integer('jumlah_sppd')->nullable()->change();
        });
    }
};
