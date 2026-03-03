<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Monastery;
use App\Models\MonasteryProfile;

class ImportMonasteryProfiles extends Command
{
    protected $signature = 'import:monastery-profiles {--dry-run : Do not write to DB, only show what would happen}';
    protected $description = 'Import monastery profiles from CSV (storage/app/import/monastery_profiles.csv)';

    public function handle()
    {
        $path = storage_path('app/import/monastery_profiles.csv');

        if (!file_exists($path)) {
            $this->error("CSV not found: {$path}");
            return Command::FAILURE;
        }

        $dryRun = (bool) $this->option('dry-run');

        $handle = fopen($path, 'r');
        if (!$handle) {
            $this->error("Cannot open CSV: {$path}");
            return Command::FAILURE;
        }

        // --- Read header (handle UTF-8 BOM) ---
        $header = fgetcsv($handle);
        if (!$header) {
            $this->error("CSV is empty: {$path}");
            fclose($handle);
            return Command::FAILURE;
        }

        // Remove BOM from first header cell if present
        $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);
        $header = array_map('trim', $header);

        $created = 0;
        $updated = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle)) !== false) {
            // Skip empty lines
            if (count($row) === 1 && trim($row[0] ?? '') === '') {
                continue;
            }

            // If columns mismatch, skip
            if (count($row) !== count($header)) {
                $skipped++;
                continue;
            }

            $data = array_combine($header, $row);

            // --- slug normalize ---
            $rawSlug = trim($data['slug'] ?? '');
            if ($rawSlug === '') {
                $skipped++;
                continue;
            }

            // normalize common patterns:
            // "manastir-studenica" -> "studenica"
            $slug = preg_replace('/^manastir-/', '', $rawSlug);

            $monastery = Monastery::where('slug', $slug)->first();

            if (!$monastery) {
                // fallback: try raw slug too (just in case)
                $monastery = Monastery::where('slug', $rawSlug)->first();
            }

            if (!$monastery) {
                $this->warn("⚠️ Monastery not found for slug: {$rawSlug} (normalized: {$slug})");
                $skipped++;
                continue;
            }

            // --- sources_json: try to decode string into array; if fail, store null or raw ---
            $sourcesRaw = $data['sources_json'] ?? null;
            $sources = null;

            if (is_string($sourcesRaw) && trim($sourcesRaw) !== '') {
                $decoded = json_decode($sourcesRaw, true);
                $sources = (json_last_error() === JSON_ERROR_NONE) ? $decoded : null;
            }

            $payload = [
                'intro' => $data['intro'] ?? null,
                'history' => $data['history'] ?? null,
                'architecture' => $data['architecture'] ?? null,
                'ktitor_text' => $data['ktitor_text'] ?? null,
                'art_frescoes' => $data['art_frescoes'] ?? null,
                'interesting_facts' => $data['interesting_facts'] ?? null,
                'sources_json' => $sources, // čuvamo kao array (cast u modelu)
            ];

            if ($dryRun) {
                $this->line("DRY RUN ✅ would upsert profile for monastery_id={$monastery->id} slug={$monastery->slug}");
                continue;
            }

            $profile = MonasteryProfile::updateOrCreate(
                ['monastery_id' => $monastery->id],
                $payload
            );

            // crude created/updated counting
            if ($profile->wasRecentlyCreated) {
                $created++;
            } else {
                $updated++;
            }
        }

        fclose($handle);

        $this->info("✅ Monastery profiles imported. created={$created}, updated={$updated}, skipped={$skipped}");
        return Command::SUCCESS;
    }
}