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
            $table->enum('tipe_perjalanan', ['darat', 'laut', 'udara'])->nullable();
            $table->string('tiket')->nullable();
            $table->string('hotel')->nullable();
            $table->integer('uang_saku')->default(0);
            $table->integer('transport')->default(0);
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
