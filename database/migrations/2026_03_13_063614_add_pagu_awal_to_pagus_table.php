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
        Schema::table('pagus', function (Blueprint $table) {
            $table->bigInteger('pagu_awal_umum')->default(0)->after('saldo_umum');
            $table->bigInteger('pagu_awal_pu')->default(0)->after('saldo_pu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagus', function (Blueprint $table) {
             $table->dropColumn(['pagu_awal_umum', 'pagu_awal_pu']);
        });
    }
};
