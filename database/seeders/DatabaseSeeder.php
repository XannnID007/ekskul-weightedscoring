<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting database seeding...');

        // Disable foreign key checks untuk menghindari constraint errors
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            // Truncate tables dalam urutan yang benar (reverse dependency order)
            $this->truncateTables();

            // Seed data dalam urutan yang benar
            $this->command->info('📝 Seeding users...');
            $this->call(UserSeeder::class);

            $this->command->info('🏫 Seeding ekstrakurikuler...');
            $this->call(EkstrakurikulerSeeder::class);

            $this->command->info('📢 Seeding pengumuman...');
            $this->call(PengumumanSeeder::class);

            $this->command->info('📸 Seeding galeri...');
            $this->call(GaleriSeeder::class);

            $this->command->info('📝 Seeding pendaftaran...');
            $this->call(PendaftaranSeeder::class);
        } catch (\Exception $e) {
            $this->command->error('❌ Error during seeding: ' . $e->getMessage());
            throw $e;
        } finally {
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $this->command->info('🎉 Database seeded successfully!');
        $this->command->info('📧 Login credentials:');
        $this->command->info('   Admin: admin@gmail.com / admin123');
        $this->command->info('   Pembina: budisantoso@gmail.com / pembina123');
        $this->command->info('   Siswa: ahmadrizkipratama@gmail.com / siswa123');
    }

    /**
     * Truncate tables dalam urutan yang benar
     */
    private function truncateTables(): void
    {
        $this->command->info('🧹 Cleaning existing data...');

        // Order matters - truncate tables dengan foreign keys terlebih dahulu
        $tables = [
            'rekomendasis',
            'absensis',
            'pendaftarans',
            'galeris',
            'pengumumans',
            'ekstrakurikulers',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
                $this->command->info("✓ Truncated {$table} table");
            }
        }

        // Reset user data kecuali admin yang mungkin sudah ada
        DB::table('users')->where('role', '!=', 'admin')->delete();
        $this->command->info("✓ Cleaned non-admin users");
    }
}
