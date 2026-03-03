<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Eparchy;
use App\Models\Monastery;

class ImportMonasteriesSpc extends Command
{
    protected $signature = 'app:import-monasteries-spc';
    protected $description = 'Import SPC monasteries (whitelist) from storage/app/seed/monasteries_spc.json';

    public function handle(): int
    {
        $path = 'seed/monasteries_spc.json';

        if (!Storage::disk('local')->exists($path)) {
            $this->error("Nema fajla: storage/app/{$path}");
            return self::FAILURE;
        }

        $raw = Storage::disk('local')->get($path);
        $data = json_decode($raw, true);

        if (!is_array($data)) {
            $this->error("JSON nije validan (mora biti niz). Proveri monasteries_spc.json");
            return self::FAILURE;
        }

        $count = 0;

        foreach ($data as $m) {
            if (empty($m['name']) || empty($m['slug']) || empty($m['eparchy_slug'])) {
                $this->warn("Preskačem red bez name/slug/eparchy_slug: " . json_encode($m, JSON_UNESCAPED_UNICODE));
                continue;
            }

            $eparchy = Eparchy::where('slug', $m['eparchy_slug'])->first();

            if (!$eparchy) {
                $this->warn("Ne postoji eparchy_slug={$m['eparchy_slug']} za {$m['name']}. Najpre import eparhies.");
                continue;
            }

            Monastery::updateOrCreate(
                ['slug' => $m['slug']],
                [
                    'name'      => $m['name'],
                    'eparchy_id'=> $eparchy->id,
                    'region'    => $m['region'] ?? null,
                    'city'      => $m['city'] ?? null,
                    'source'    => $m['source'] ?? 'SPC whitelist',
                ]
            );

            $count++;
        }

        $this->info("Monasteries imported: {$count}");
        return self::SUCCESS;
    }
}