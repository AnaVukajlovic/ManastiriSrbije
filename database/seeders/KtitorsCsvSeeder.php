<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KtitorsCsvSeeder extends Seeder
{
    public function run(): void
    {
        $path = storage_path('app/import/ktitors.csv');

        if (!file_exists($path)) {
            $this->command->error("CSV not found: {$path}");
            return;
        }

        $fh = fopen($path, 'r');
        $header = fgetcsv($fh);

        if (!$header) {
            $this->command->error("Empty CSV.");
            return;
        }

        $map = array_flip($header);
        $required = ['name', 'slug', 'bio'];

        foreach ($required as $col) {
            if (!isset($map[$col])) {
                $this->command->error("Missing column: {$col}");
                return;
            }
        }

        $rows = 0;

        while (($row = fgetcsv($fh)) !== false) {
            $name = trim((string)($row[$map['name']] ?? ''));
            $slug = trim((string)($row[$map['slug']] ?? ''));
            $bio  = (string)($row[$map['bio']] ?? '');

            if ($name === '' || $slug === '') {
                continue;
            }

            $bornYear = isset($map['born_year']) ? trim((string)($row[$map['born_year']] ?? '')) : null;
            $diedYear = isset($map['died_year']) ? trim((string)($row[$map['died_year']] ?? '')) : null;

            // Pretvori "\n" u stvarne nove redove
            $bio = str_replace(["\\n", "\\r"], ["\n", "\r"], $bio);

            DB::table('ktitors')->updateOrInsert(
                ['slug' => $slug],
                [
                    'name'       => $name,
                    'born_year'  => $bornYear !== '' ? (int)$bornYear : null,
                    'died_year'  => $diedYear !== '' ? (int)$diedYear : null,
                    'bio'        => $bio,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            $rows++;
        }

        fclose($fh);

        $this->command->info("Imported/updated: {$rows} ktitors.");
    }
}