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
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();

        // Truncate tables in correct order (reverse dependency order)
        $this->truncateTables();

        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();

        // Seed data in correct order
        $this->call([
            UserSeeder::class,           // Users first (no dependencies)
            EkstrakurikulerSeeder::class, // Ekstrakurikuler depends on users (pembina)
        ]);

        $this->command->info('🎉 Database seeded successfully!');
        $this->command->info('📧 Login credentials:');
        $this->command->info('   Admin: admin@miftah.com / admin123');
        $this->command->info('   Pembina: budisantoso@miftah.com / pembina123');
        $this->command->info('   Siswa: muhammadiqbal@student.com / siswa123');
    }

    /**
     * Truncate tables in correct order
     */
    private function truncateTables(): void
    {
        // Order matters - truncate tables with foreign keys first
        $tables = [
            'rekomendasis',
            'absensis',
            'pendaftarans',
            'galeris',
            'pengumumans',
            'ekstrakurikulers',
            'users',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
                $this->command->info("✓ Truncated {$table} table");
            }
        }
    }
}