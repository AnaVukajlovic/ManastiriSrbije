<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Ktitor;
use App\Models\KtitorImage;

class KtitorSeeder extends Seeder
{
    public function run(): void
    {
        // CSV mora da bude ovde:
        // backend/database/seeders/ktitors_full_complete.csv
       $csvPath = storage_path('app/import/ktitors.csv');

        if (!file_exists($csvPath)) {
            $this->command?->error("CSV nije pronađen: {$csvPath}");
            return;
        }

        $rows = $this->readCsvAssoc($csvPath);

        if (empty($rows)) {
            $this->command?->warn("CSV je prazan ili nevalidan: {$csvPath}");
            return;
        }

        foreach ($rows as $r) {
            // očekujemo bar: slug, name, image
            $name = trim((string)($r['name'] ?? ''));
            $slug = trim((string)($r['slug'] ?? ''));
            $image = trim((string)($r['image'] ?? ''));

            if ($name === '' && $slug === '') {
                continue;
            }

            if ($slug === '') {
                $slug = Str::slug($name);
            }

            if ($name === '') {
                $name = Str::of($slug)->replace('-', ' ')->title()->toString();
            }


           $payload = [
    'name'         => $name,
    'slug'         => $slug,
    'bio'          => $r['bio'] ?? $r['description'] ?? null,
    'born_year'    => $this->toIntOrNull($r['born_year'] ?? null),
    'died_year'    => $this->toIntOrNull($r['died_year'] ?? null),

    'title'        => $r['title'] ?? null,
    'dynasty'      => $r['dynasty'] ?? null,
    'is_saint'     => isset($r['is_saint']) ? (bool)$r['is_saint'] : false,
    'burial_place' => $r['burial_place'] ?? null,
];


            $payload = array_filter($payload, fn($v) => $v !== null);

            $k = Ktitor::updateOrCreate(
                ['slug' => $slug],
                $payload
            );

            if ($image !== '') {
                KtitorImage::updateOrCreate(
                    [
                        'ktitor_id' => $k->id,
                        'path' => $image,
                    ],
                    [
                        'caption' => $name,
                        'source'  => 'Local',
                        'credit'  => null,
                        'sort'    => 1,
                    ]
                );
            }
        }

        $this->command?->info("Ktitori seed: OK (CSV: ktitors.csv)");
    }

    private function readCsvAssoc(string $path): array
    {
        $fh = fopen($path, 'r');
        if (!$fh) return [];

        $rows = [];
        $header = null;

        while (($data = fgetcsv($fh, 0, ',')) !== false) {
            if ($header === null) {
                // skini BOM ako postoji
                $data[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string)$data[0]);
                $header = array_map(fn($h) => trim((string)$h), $data);
                continue;
            }

            if (count($data) === 1 && trim((string)$data[0]) === '') continue;

            $row = [];
            foreach ($header as $i => $key) {
                $row[$key] = $data[$i] ?? null;
            }
            $rows[] = $row;
        }

        fclose($fh);
        return $rows;
    }

    private function toIntOrNull($v): ?int
    {
        if ($v === null) return null;
        $s = trim((string)$v);
        if ($s === '') return null;
        if (!preg_match('/^-?\d+$/', $s)) return null;
        return (int)$s;
    }
}