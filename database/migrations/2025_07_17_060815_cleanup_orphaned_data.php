<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Hapus data orphan di tabel rekomendasis
        DB::statement('DELETE FROM rekomendasis WHERE ekstrakurikuler_id NOT IN (SELECT id FROM ekstrakurikulers)');

        // Hapus data orphan di tabel pendaftarans
        DB::statement('DELETE FROM pendaftarans WHERE ekstrakurikuler_id NOT IN (SELECT id FROM ekstrakurikulers)');


        // Hapus data orphan di tabel pengumumans (jika ada)
        if (Schema::hasTable('pengumumans')) {
            DB::statement('DELETE FROM pengumumans WHERE ekstrakurikuler_id NOT IN (SELECT id FROM ekstrakurikulers)');
        }

        // Hapus data orphan di tabel galeris (jika ada)
        if (Schema::hasTable('galeris')) {
            DB::statement('DELETE FROM galeris WHERE ekstrakurikuler_id NOT IN (SELECT id FROM ekstrakurikulers)');
        }

        // Reset auto increment jika perlu
        DB::statement('ALTER TABLE ekstrakurikulers AUTO_INCREMENT = 1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak ada rollback untuk cleanup data
    }
};
