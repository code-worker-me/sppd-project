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
            // drop unique index on sppd_id (Laravel default name: data_perjalanan_sppd_id_unique)
            if (Schema::hasColumn('data_perjalanan', 'sppd_id')) {
                try {
                    $table->dropUnique('data_perjalanan_sppd_id_unique');
                } catch (\Throwable $e) {
                    // fallback: try dropping by column array (works for some drivers)
                    try {
                        $table->dropUnique(['sppd_id']);
                    } catch (\Throwable $e) {
                        // ignore if index does not exist
                    }
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_perjalanan', function (Blueprint $table) {
            if (Schema::hasColumn('data_perjalanan', 'sppd_id')) {
                $table->unique('sppd_id');
            }
        });
    }
};
