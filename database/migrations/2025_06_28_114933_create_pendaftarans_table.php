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
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('ekstrakurikuler_id')->constrained();
            $table->text('motivasi'); // motivasi bergabung
            $table->text('pengalaman')->nullable(); // pengalaman terkait
            $table->text('harapan'); // harapan dan tujuan
            $table->enum('tingkat_komitmen', ['tinggi', 'sedang', 'rendah']);
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->text('alasan_penolakan')->nullable();
            $table->timestamp('disetujui_pada')->nullable();
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users');
            $table->timestamps();

            $table->unique(['user_id', 'ekstrakurikuler_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftarans');
    }
};
