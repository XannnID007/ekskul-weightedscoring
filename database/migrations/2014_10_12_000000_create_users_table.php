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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->enum('role', ['admin', 'pembina', 'siswa'])->default('siswa');
            $table->string('nis')->nullable();
            $table->string('telepon')->nullable();
            $table->text('alamat')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->decimal('nilai_rata_rata', 5, 2)->nullable(); // nilai rata-rata
            $table->text('minat')->nullable(); // JSON array minat
            $table->text('prestasi')->nullable(); // prestasi
            $table->boolean('is_active')->default(true);
            $table->timestamp('email_verified_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'nis',
                'telepon',
                'alamat',
                'jenis_kelamin',
                'tanggal_lahir',
                'nilai_rata_rata',
                'minat',
                'prestasi',
                'is_active'
            ]);
        });
    }
};
