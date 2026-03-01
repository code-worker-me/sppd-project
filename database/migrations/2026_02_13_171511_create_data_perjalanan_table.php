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
        Schema::create('data_perjalanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sppd_id')->unique()->constrained('data_sppd')->onDelete('cascade');
            $table->integer('tiket_pergi')->default(0);
            $table->integer('tiket_pulang')->default(0);
            $table->integer('hotel')->default(0);
            $table->integer('uang_harian')->default(0);
            $table->integer('uang_representasi')->default(0);
            $table->integer('transport_lokal_pergi')->default(0);
            $table->integer('transport_lokal_pulang')->default(0);
            $table->integer('bbm_tol')->default(0);
            $table->integer('jumlah_sppd')->default(0);
            $table->integer('saldo_umum')->default(0);
            $table->integer('saldo_pengembangan')->default(0);
            $table->integer('panjar_kerja')->default(0);
            $table->integer('keterangan')->default(0);
            $table->json('lampiran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_perjalanan');
    }
};
