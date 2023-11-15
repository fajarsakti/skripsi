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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('pemberi_tugas');
            $table->foreignId('industry_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contracttype_id')->constrained()->cascadeOnDelete();
            $table->string('lokasi_proyek');
            $table->date('tanggal_kontrak');
            $table->date('selesai_kontrak');
            $table->string('status_kontrak');
            $table->integer('durasi_kontrak');
            $table->boolean('is_available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
