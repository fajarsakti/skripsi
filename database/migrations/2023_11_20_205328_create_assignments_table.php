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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contracts_id')->constrained('contracts')->cascadeOnDelete();
            $table->string('no_penugasan')->unique();
            $table->foreignId('surveyors_id')->constrained('surveyors')->cascadeOnDelete();
            $table->date('tanggal_penugasan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
