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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surveyors_id')->constrained('surveyors')->cascadeOnDelete();
            $table->foreignId('surveys_id')->constrained()->cascadeOnDelete();
            $table->string('pemberi_tugas');
            $table->foreignId('industries_id')->constrained('industries')->cascadeOnDelete();
            $table->foreignId('contract_types_id')->constrained('contract_types')->cascadeOnDelete();
            $table->string('lokasi_proyek');
            $table->date('tanggal_proyek');
            $table->date('selesai_proyek')->nullable();
            $table->string('status_proyek')->nullable();
            $table->integer('durasi_proyek')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
