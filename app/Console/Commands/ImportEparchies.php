<?php

namespace App\Console\Commands;

use App\Models\Eparchy;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportEparchies extends Command
{
    protected $signature = 'app:import-eparchies {--path=seed/eparchies.json : Path relative to local disk (storage/app/private)}';
    protected $description = 'Import SPC eparchies from JSON into DB';

    public function handle(): int
    {
        $path = (string) $this->option('path'); // default: seed/eparchies.json
        $disk = Storage::disk('local'); // local root = storage/app/private (kod tebe)

        // Prikaži gde Laravel stvarno traži fajl
        $root = config('filesystems.disks.local.root');
        $fullPath = rtrim($root, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);

        if (!$disk->exists($path)) {
            $this->error("Nema fajla: {$fullPath}");
            $this->line("Proveri da li fajl postoji u: storage/app/private/seed/eparchies.json");
            return self::FAILURE;
        }

        $raw = $disk->get($path);
        $data = json_decode($raw, true);

        if (!is_array($data)) {
            $this->error("JSON nije validan (mora biti niz). Proveri eparchies.json");
            return self::FAILURE;
        }

        // Proveri koje kolone realno postoje u tabeli (da ne upisujemo seat/source ako ih nema)
        $cols = Schema::getColumnListing('eparchies');
        $hasSlug   = in_array('slug', $cols, true);
        $hasSeat   = in_array('seat', $cols, true);
        $hasSource = in_array('source', $cols, true);

        if (!$hasSlug) {
            $this->error("Tabela 'eparchies' nema kolonu 'slug'. Prvo dodaj migraciju za slug i pokreni php artisan migrate.");
            return self::FAILURE;
        }

        $count = 0;
        $skipped = 0;

        foreach ($data as $e) {
            $name = trim((string) ($e['name'] ?? ''));
            $slug = trim((string) ($e['slug'] ?? ''));

            if ($name === '') {
                $this->warn("Preskačem red bez name: " . json_encode($e, JSON_UNESCAPED_UNICODE));
                $skipped++;
                continue;
            }

            // Ako nema slug u JSON-u, generiši iz name
            if ($slug === '') {
                $slug = Str::slug($name, '-');

                // fallback za srpska slova (ako Str::slug ne transliteriše kako treba u tvom okruženju)
                $slug = str_replace(['đ','č','ć','š','ž','Đ','Č','Ć','Š','Ž'], ['dj','c','c','s','z','dj','c','c','s','z'], $slug);
                $slug = preg_replace('/-+/', '-', $slug);
                $slug = trim($slug, '-');
            }

            if ($slug === '') {
                $this->warn("Preskačem red bez slug (ni iz name nije moguće): " . json_encode($e, JSON_UNESCAPED_UNICODE));
                $skipped++;
                continue;
            }

            $values = ['name' => $name];

            if ($hasSeat) {
                $values['seat'] = $e['seat'] ?? null;
            }
            if ($hasSource) {
                $values['source'] = $e['source'] ?? 'seed';
            }

            Eparchy::updateOrCreate(
                ['slug' => $slug],
                $values
            );

            $count++;
        }

        $this->info("Eparchies imported: {$count}" . ($skipped ? " (skipped: {$skipped})" : ""));
        $this->line("Korišćen fajl: {$fullPath}");

        return self::SUCCESS;
    }
}