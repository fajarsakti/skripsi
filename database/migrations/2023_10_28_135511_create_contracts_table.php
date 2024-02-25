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
            $table->foreignId('surveyors_id')->constrained('surveyors')->cascadeOnDelete();
            $table->foreignId('surveys_id')->constrained('surveys')->cascadeOnDelete();
            $table->string('pemberi_tugas');
            $table->foreignId('industries_id')->constrained('industries')->cascadeOnDelete();
            $table->foreignId('contract_types_id')->constrained('contract_types')->cascadeOnDelete();
            $table->foreignId('assets_id')->constrained('assets')->cascadeOnDelete();
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
