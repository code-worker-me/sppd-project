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
        Schema::create('lampiran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sppd_id')->unique()->constrained('data_sppd')->onDelete('cascade');
            $table->json('laporan_perjalanan')->nullable();
            $table->json('foto_kegiatan')->nullable();
            $table->json('blanko_sppd')->nullable();
            $table->json('surat_tugas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lampiran');
    }
};
