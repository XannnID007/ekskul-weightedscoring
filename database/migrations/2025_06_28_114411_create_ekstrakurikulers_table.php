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
        Schema::create('ekstrakurikulers', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi');
            $table->string('gambar')->nullable();
            $table->integer('kapasitas_maksimal');
            $table->integer('peserta_saat_ini')->default(0);
            $table->json('jadwal'); // {hari: 'senin', waktu: '15:00-17:00'}
            $table->json('kategori'); // ['olahraga', 'seni', 'akademik']
            $table->decimal('nilai_minimal', 5, 2)->default(0); // nilai minimal
            $table->foreignId('pembina_id')->constrained('users');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ekstrakurikulers');
    }
};
