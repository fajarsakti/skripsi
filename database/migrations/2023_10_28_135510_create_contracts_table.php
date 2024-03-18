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
            $table->unsignedBigInteger('surveyors_id')->nullable();
            $table->unsignedBigInteger('surveys_id')->nullable();
            $table->string('pemberi_tugas');
            $table->foreignId('industries_id')->constrained('industries')->cascadeOnDelete();
            $table->foreignId('contract_types_id')->constrained('contract_types')->cascadeOnDelete();
            $table->foreignId('assets_id')->constrained('assets')->cascadeOnDelete();
            $table->string('lokasi_proyek');
            $table->date('tanggal_kontrak');
            $table->date('selesai_kontrak')->nullable();
            $table->string('status_kontrak')->default('Pending');
            $table->integer('durasi_kontrak')->nullable();
            $table->boolean('is_available')->default(0);
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
