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
        Schema::create('rekomendasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('ekstrakurikuler_id')->constrained();
            $table->decimal('skor_minat', 5, 2); // skor minat (50%)
            $table->decimal('skor_akademik', 5, 2); // skor akademik (30%)
            $table->decimal('skor_jadwal', 5, 2); // skor jadwal (20%)
            $table->decimal('total_skor', 5, 2); // total weighted score
            $table->text('alasan'); // alasan rekomendasi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekomendasis');
    }
};
