<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use App\Models\Monastery;
use App\Models\MonasteryProfile;

class ImportMonasteriesAndProfiles extends Command
{
    protected $signature = 'monasteries:import-all
        {--truncate : Obrisi postojece manastire i profile pre importa}
        {--m=storage/app/import/monasteries.csv : Putanja do monasteries CSV}
        {--p=storage/app/import/monastery_profiles.csv : Putanja do profiles CSV}';

    protected $description = 'Import monasteries.csv + monastery_profiles.csv (updateOrCreate by slug)';

    public function handle(): int
    {
        $mPath = base_path((string)$this->option('m'));
        $pPath = base_path((string)$this->option('p'));

        if (!file_exists($mPath)) { $this->error("Nema fajla: $mPath"); return 1; }
        if (!file_exists($pPath)) { $this->error("Nema fajla: $pPath"); return 1; }

        if ($this->option('truncate')) {
            if (class_exists(MonasteryProfile::class)) {
                MonasteryProfile::query()->delete();
            }
            Monastery::query()->delete();
            $this->warn("Obrisani postojeći manastiri (+ profili ako postoje).");
        }

        // ===== IMPORT MONASTERIES =====
        $importedM = $this->importCsv($mPath, function(array $d) {
            if (empty($d['slug']) || empty($d['name'])) return;

            $payload = [
                'name' => $d['name'],
            ];

            foreach (['region','city','description_short','lat','lng','status'] as $col) {
                if (array_key_exists($col, $d) && Schema::hasColumn('monasteries', $col)) {
                    $payload[$col] = $d[$col] === '' ? null : $d[$col];
                }
            }

            // da ti se ne “sakriju” zbog filtera na home:
            if (Schema::hasColumn('monasteries', 'is_approved')) $payload['is_approved'] = 1;
            if (Schema::hasColumn('monasteries', 'is_spc'))      $payload['is_spc'] = 1;

            Monastery::updateOrCreate(['slug' => $d['slug']], $payload);
        });

        $this->info("Monasteries imported/updated: $importedM");

        // ===== IMPORT PROFILES (ako model/tabela postoje) =====
        $importedP = 0;
        if (class_exists(MonasteryProfile::class)) {
            $importedP = $this->importCsv($pPath, function(array $d) {
                if (empty($d['slug'])) return;

                $m = Monastery::where('slug', $d['slug'])->first();
                if (!$m) return;

                $payload = [];

                // ako tabela ima monastery_id (najčešće)
                if (Schema::hasColumn('monastery_profiles', 'monastery_id')) {
                    $payload['monastery_id'] = $m->id;
                }

                // ako tabela ima slug (nekad si tako pravila)
                if (Schema::hasColumn('monastery_profiles', 'slug')) {
                    $payload['slug'] = $d['slug'];
                }

                foreach (['intro','history','architecture','ktitor_text','art_frescoes','interesting_facts','sources_json'] as $col) {
                    if (array_key_exists($col, $d) && Schema::hasColumn('monastery_profiles', $col)) {
                        $payload[$col] = $d[$col] === '' ? null : $d[$col];
                    }
                }

                // ključ za updateOrCreate:
                $key = [];
                if (Schema::hasColumn('monastery_profiles', 'monastery_id')) $key = ['monastery_id' => $m->id];
                elseif (Schema::hasColumn('monastery_profiles', 'slug'))     $key = ['slug' => $d['slug']];
                else return;

                MonasteryProfile::updateOrCreate($key, $payload);
            });

            $this->info("Monastery profiles imported/updated: $importedP");
        } else {
            $this->warn("Model MonasteryProfile ne postoji — preskačem profiles import.");
        }

        return 0;
    }

    private function importCsv(string $path, callable $rowHandler): int
    {
        $h = fopen($path, 'r');
        if (!$h) return 0;

        $header = fgetcsv($h, 0, ',');
        if (!$header) { fclose($h); return 0; }

        // BOM + trim
        $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string)$header[0]);
        $header = array_map(fn($v) => trim((string)$v), $header);
        $hn = count($header);

        $count = 0;

        while (($row = fgetcsv($h, 0, ',')) !== false) {
            if (!$row || (count($row) === 1 && trim((string)$row[0]) === '')) continue;

            // map safely (bez array_combine)
            $d = [];
            for ($i=0; $i<$hn; $i++) {
                $k = $header[$i] ?? null;
                if (!$k) continue;
                $d[$k] = isset($row[$i]) ? trim((string)$row[$i]) : '';
            }

            $rowHandler($d);
            $count++;
        }

        fclose($h);
        return $count;
    }
}