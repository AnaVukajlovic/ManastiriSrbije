<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KtitorsCsvSeeder extends Seeder
{
    public function run(): void
    {
        $path = storage_path('app/import/ktitors.csv');
        
        if (!file_exists($path)) {
            $this->command?->error("Fajl nije pronađen.");
            return;
        }

        // RESTART: Čistimo samo ktitore i slike na početku
        DB::statement('PRAGMA foreign_keys = OFF;');
        DB::table('ktitor_images')->truncate();
        DB::table('ktitors')->truncate();
        DB::statement('PRAGMA foreign_keys = ON;');

        $file = new \SplFileObject($path);
        $file->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);
        $file->setCsvControl(',');

        $isFirstLine = true;
        $inserted = 0;

        foreach ($file as $row) {
            if (empty($row) || !isset($row[1])) continue;
            
            if ($isFirstLine) {
                $isFirstLine = false;
                continue;
            }

            $name = trim($row[0]);
            $slug = trim($row[1]);

            if ($slug === '') continue;

            $ktitorId = DB::table('ktitors')->insertGetId([
                'name'         => $name,
                'slug'         => $slug,
                'born_year'    => is_numeric(trim($row[2])) ? (int)trim($row[2]) : null,
                'died_year'    => is_numeric(trim($row[3])) ? (int)trim($row[3]) : null,
                'bio'          => $row[4] ?? '',
                'title'        => $row[5] ?? '',
                'dynasty'      => $row[6] ?? 'Nemanjići',
                'is_saint'     => isset($row[7]) ? (bool)trim($row[7]) : false,
                'burial_place' => $row[8] ?? '',
                'updated_at'   => now(),
                'created_at'   => now(),
            ]);

            // Slike na osnovu sluga
            DB::table('ktitor_images')->insert([
                'ktitor_id'  => $ktitorId,
                'path'       => "images/ktitors/{$slug}.jpg",
                'caption'    => $name,
                'source'     => 'Local',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $inserted++;
        }

        $this->command?->info("Uvezeno: {$inserted} ktitora.");
    }
}