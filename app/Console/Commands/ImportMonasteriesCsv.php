<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Models\Monastery;
use App\Models\Eparchy;

class ImportMonasteriesCsv extends Command
{
    protected $signature = 'monasteries:import {path=storage/app/data/monasteries.csv}';
    protected $description = 'Import monasteries from CSV (public image_url paths)';

    public function handle(): int
    {
        $path = $this->argument('path');
        $realPath = is_file($path) ? $path : base_path($path);

        if (!is_file($realPath)) {
            $this->error("CSV not found: {$realPath}");
            return self::FAILURE;
        }

        $fp = fopen($realPath, 'r');
        if (!$fp) {
            $this->error("Cannot open CSV.");
            return self::FAILURE;
        }

        $header = fgetcsv($fp);
        if (!$header) {
            $this->error("Empty CSV.");
            return self::FAILURE;
        }
        $header = array_map(fn($h) => trim((string)$h), $header);

        $created = $updated = $processed = 0;

        while (($row = fgetcsv($fp)) !== false) {
            $data = [];
            foreach ($header as $i => $key) {
                $data[$key] = isset($row[$i]) ? trim((string)$row[$i]) : null;
            }

            if (empty($data['name'])) continue;

            $slug = $data['slug'] ?: Str::slug($data['name']);

            // eparchy mapping (eparchy_name -> eparchy_id)
            $eparchyId = null;
            if (!empty($data['eparchy_name'])) {
                $e = Eparchy::firstOrCreate(['name' => $data['eparchy_name']]);
                $eparchyId = $e->id;
            }

            $payload = [
                'name' => $data['name'],
                'slug' => $slug,
                'type' => $data['type'] ?? 'manastir',
                'region' => $data['region'] ?? null,
                'city' => $data['city'] ?? null,
                'latitude' => ($data['latitude'] ?? '') !== '' ? (float)$data['latitude'] : null,
                'longitude' => ($data['longitude'] ?? '') !== '' ? (float)$data['longitude'] : null,
                'description' => $data['description'] ?? null,
                'image_url' => $data['image_url'] ?? null,
                'source' => $data['source'] ?? 'dataset_v1',
                'review_status' => $data['review_status'] ?? 'approved',
                'is_approved' => (int)($data['is_approved'] ?? 1) ? 1 : 0,
                'eparchy_id' => $eparchyId,
            ];

            $existing = Monastery::where('slug', $slug)->first();
            if ($existing) {
                $existing->update($payload);
                $updated++;
            } else {
                Monastery::create($payload);
                $created++;
            }

            $processed++;
        }

        fclose($fp);

        $this->info("Done. Processed: {$processed}, created: {$created}, updated: {$updated}");
        return self::SUCCESS;
    }
}
