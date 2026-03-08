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
        Schema::create('data_sppd', function (Blueprint $table) {
            $table->id();
            $table->string('st')->nullable();
            $table->string('kota')->nullable();
            $table->text('deskripsi')->nullable();
            $table->text('angkutan')->nullable();
            $table->date('tg_berangkat')->nullable();
            $table->date('tg_pulang')->nullable();
            $table->enum('jenis_st', ['umum', 'pu'])->default('umum');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_sppd');
    }
};
