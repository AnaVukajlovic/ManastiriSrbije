<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CalendarDaysSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Guard: ako već imamo 365 redova, preskoči
        try {
            if (Schema::hasTable('calendar_days')) {
                $existing = DB::table('calendar_days')->count();
                if ($existing >= 365) {
                    $this->command?->info("CalendarDaysSeeder: preskačem (već postoji {$existing} redova).");
                    return;
                }
            }
        } catch (\Throwable $e) {
            // ignore, nastavi dalje
        }

        $path = database_path('seeders/data/calendar_days_2026_sr_lat.csv');

        if (!file_exists($path)) {
            $this->command?->error("CSV ne postoji: {$path}");
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES);
        if (!$lines || count($lines) < 2) {
            $this->command?->error("CSV je prazan ili nema podataka (linije=" . count($lines ?? []) . ")");
            return;
        }

        $header = str_getcsv(array_shift($lines));

        $expected = ['date','day_of_year','day_name','old_date','feast_name','saint_name','fasting_type','note','is_red_letter'];

        // mapa "kolona u CSV" => index
        $map = [];
        foreach ($header as $idx => $name) {
            $map[trim((string)$name)] = $idx;
        }

        // fallback: ako header nije očekivan, radi po poziciji expected-a
        $useExpectedPositions = ($header !== $expected);
        if ($useExpectedPositions) {
            $this->command?->warn("Header u CSV nije očekivan.");
            $this->command?->warn("Dobijen: " . implode(',', $header));
            $this->command?->warn("Očekivan: " . implode(',', $expected));
        }

        // koje kolone realno postoje u tabeli
        $hasDayOfYear = Schema::hasColumn('calendar_days', 'day_of_year');
        $hasDayName   = Schema::hasColumn('calendar_days', 'day_name');
        $hasOldDate   = Schema::hasColumn('calendar_days', 'old_date');

        // “čist start” + FK safe
        $driver = DB::getDriverName();
        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
            DB::table('calendar_days')->delete();
            DB::statement('PRAGMA foreign_keys = ON;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('calendar_days')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $rows = [];

        $get = function(array $cols, string $key) use ($map, $expected, $useExpectedPositions) {
            if ($useExpectedPositions) {
                $pos = array_search($key, $expected, true);
                return ($pos !== false && isset($cols[$pos])) ? $cols[$pos] : null;
            }

            return isset($map[$key]) && isset($cols[$map[$key]]) ? $cols[$map[$key]] : null;
        };

        foreach ($lines as $i => $line) {
            if (trim((string)$line) === '') continue;

            $cols = str_getcsv($line);

            if (count($cols) < 9) {
                $this->command?->warn("Preskačem liniju " . ($i + 2) . " (premalo kolona): {$line}");
                continue;
            }

            $date = trim((string) $get($cols, 'date'));
            if ($date === '') {
                $this->command?->warn("Preskačem liniju " . ($i + 2) . " (prazan date).");
                continue;
            }

            $row = [
                'date'          => $date,
                'feast_name'    => ($v = trim((string) $get($cols, 'feast_name')))   !== '' ? $v : null,
                'saint_name'    => ($v = trim((string) $get($cols, 'saint_name')))   !== '' ? $v : null,
                'fasting_type'  => ($v = trim((string) $get($cols, 'fasting_type'))) !== '' ? $v : null,
                'note'          => ($v = trim((string) $get($cols, 'note')))         !== '' ? $v : null,
                'is_red_letter' => (int) ($get($cols, 'is_red_letter') ?? 0),
                'created_at'    => now(),
                'updated_at'    => now(),
            ];

            if ($hasDayOfYear) $row['day_of_year'] = (int) ($get($cols, 'day_of_year') ?? 0);
            if ($hasDayName)   $row['day_name']    = ($v = trim((string) $get($cols, 'day_name'))) !== '' ? $v : null;
            if ($hasOldDate)   $row['old_date']    = ($v = trim((string) $get($cols, 'old_date'))) !== '' ? $v : null;

            $rows[] = $row;
        }

        $total = count($rows);
        $this->command?->info("CSV učitan. Redova spremno za insert: {$total}");

        if ($total === 0) {
            $this->command?->error("Nema nijednog reda za insert.");
            return;
        }

        $inserted = 0;
        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('calendar_days')->insert($chunk);
            $inserted += count($chunk);
        }

        $dbCount = DB::table('calendar_days')->count();
        $this->command?->info("INSERTED={$inserted}, DB_COUNT={$dbCount}");

        // ✅ NEMA više self-call / rekurzije!
    }
}