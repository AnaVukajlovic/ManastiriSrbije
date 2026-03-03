<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Monastery;

class ImportMonasteries extends Command
{
    protected $signature = 'import:monasteries {--overwrite : Overwrite existing non-empty fields} {--dry-run : Do not write to DB}';
    protected $description = 'Import monasteries from CSV into existing monasteries table';

    public function handle()
    {
        $path = storage_path('app/import/monasteries.csv');

        if (!file_exists($path)) {
            $this->error("CSV not found: {$path}");
            return Command::FAILURE;
        }

        $overwrite = (bool) $this->option('overwrite');
        $dryRun = (bool) $this->option('dry-run');

        $handle = fopen($path, 'r');
        if (!$handle) {
            $this->error("Cannot open CSV: {$path}");
            return Command::FAILURE;
        }

        $header = fgetcsv($handle);
        if (!$header) {
            $this->error("CSV is empty: {$path}");
            fclose($handle);
            return Command::FAILURE;
        }

        // Remove UTF-8 BOM if present
        $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);
        $header = array_map('trim', $header);

        $created = 0;
        $updated = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) === 1 && trim($row[0] ?? '') === '') {
                continue;
            }

            if (count($row) !== count($header)) {
                $skipped++;
                continue;
            }

            $data = array_combine($header, $row);

            $rawSlug = trim($data['slug'] ?? '');
            if ($rawSlug === '') {
                $skipped++;
                continue;
            }

            // normalize slug: "manastir-studenica" -> "studenica"
            $slug = preg_replace('/^manastir-/', '', $rawSlug);

            // Find existing first (you already have SPC import that creates slugs)
            $monastery = Monastery::where('slug', $slug)->first();

            // If not found, we create a new one with normalized slug
            $isNew = false;
            if (!$monastery) {
                $isNew = true;
                $monastery = new Monastery();
                $monastery->slug = $slug;
            }

            // Prepare CSV -> DB mapping (your real columns)
            $incoming = [
    'name'   => $data['name'] ?? null,
    'region' => $data['region'] ?? null,
    'city'   => $data['city'] ?? null,

    // CSV "description_short" -> DB "excerpt" (kratko)
    'excerpt' => $data['description_short'] ?? null,

    // opcionalno: isti tekst i u description (da imaš bar nešto i na detalju)
    'description' => $data['description_short'] ?? null,

    // CSV lat/lng -> DB latitude/longitude
    'latitude'  => isset($data['lat']) && $data['lat'] !== '' ? (float)$data['lat'] : null,
    'longitude' => isset($data['lng']) && $data['lng'] !== '' ? (float)$data['lng'] : null,

    // označi da je SPC + odobri (da ti featured i filteri rade)
    'is_spc' => 1,
    'is_approved' => 1,
    'review_status' => 'approved',
];

            // Apply "fill only if empty" unless overwrite
            foreach ($incoming as $field => $value) {
                if ($value === null || $value === '') {
                    continue;
                }

                if ($overwrite) {
                    $monastery->{$field} = $value;
                } else {
                    $current = $monastery->{$field} ?? null;
                    if ($current === null || $current === '') {
                        $monastery->{$field} = $value;
                    }
                }
            }
// Ove flagove uvek forsiramo (da UI radi), bez obzira na overwrite
$monastery->is_spc = 1;
$monastery->is_approved = 1;
$monastery->review_status = 'approved';
            if ($dryRun) {
                $this->line("DRY RUN ✅ would " . ($isNew ? "create" : "update") . " monastery slug={$slug}");
                continue;
            }

            $monastery->save();

            if ($isNew) $created++;
            else $updated++;
        }

        fclose($handle);

        $this->info("✅ Monasteries imported. created={$created}, updated={$updated}, skipped={$skipped}");
        return Command::SUCCESS;
    }
}