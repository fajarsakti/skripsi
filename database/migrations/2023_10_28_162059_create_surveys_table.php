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
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contracts_id')->constrained('contracts')->cascadeOnDelete();
            $table->foreignId('surveyors_id')->constrained('surveyors')->cascadeOnDelete();
            $table->foreignId('assignments_id')->constrained('assignments')->cascadeOnDelete();
            $table->string('pemilik_aset');
            $table->date('tanggal_survey');
            $table->foreignId('assets_id')->constrained('assets')->cascadeOnDelete();
            $table->char('keterangan_aset');
            $table->string('gambar_aset');
            $table->char('harga_aset');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surveys');
    }
};
