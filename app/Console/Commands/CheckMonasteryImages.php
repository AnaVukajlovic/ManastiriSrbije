<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Monastery;

class CheckMonasteryImages extends Command
{
    protected $signature = 'monasteries:check-images';
    protected $description = 'Proverava koje slike manastira postoje, koje treba preimenovati i koje fale';

    public function handle()
    {
        $dir = public_path('images/monasteries');

        if (!is_dir($dir)) {
            $this->error("Folder ne postoji: {$dir}");
            return self::FAILURE;
        }

        $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];

        $slugs = Monastery::query()
            ->pluck('slug')
            ->filter()
            ->map(fn ($s) => trim((string) $s))
            ->unique()
            ->values();

        $files = collect(scandir($dir))
            ->filter(function ($file) use ($dir, $allowedExt) {
                if ($file === '.' || $file === '..') return false;
                if (!is_file($dir . DIRECTORY_SEPARATOR . $file)) return false;

                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                return in_array($ext, $allowedExt, true);
            })
            ->values();

        $normalize = function (string $name) {
            $name = pathinfo($name, PATHINFO_FILENAME);
            $name = mb_strtolower($name, 'UTF-8');

            $map = [
                'ć' => 'c', 'č' => 'c', 'š' => 's', 'ž' => 'z', 'đ' => 'dj',
                'Ć' => 'c', 'Č' => 'c', 'Š' => 's', 'Ž' => 'z', 'Đ' => 'dj',
            ];
            $name = strtr($name, $map);

            $name = preg_replace('/\s+/', '-', $name);
            $name = preg_replace('/[^a-z0-9\-]+/', '-', $name);
            $name = preg_replace('/-+/', '-', $name);
            $name = trim($name, '-');

            return $name;
        };

        $existingExact = [];
        $normalizedFiles = [];
        $renameSuggestions = [];

        foreach ($files as $file) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $filenameOnly = pathinfo($file, PATHINFO_FILENAME);

            $existingExact[$filenameOnly] = $file;

            $normalized = $normalize($file);

            if (!isset($normalizedFiles[$normalized])) {
                $normalizedFiles[$normalized] = [
                    'file' => $file,
                    'ext' => $ext,
                ];
            }

            if ($normalized !== $filenameOnly) {
                $renameSuggestions[] = [
                    'old' => $file,
                    'new' => $normalized . '.' . $ext,
                ];
            }
        }

        $found = [];
        $missing = [];

        foreach ($slugs as $slug) {
            if (isset($existingExact[$slug])) {
                $found[$slug] = $existingExact[$slug];
                continue;
            }

            if (isset($normalizedFiles[$slug])) {
                $found[$slug] = $normalizedFiles[$slug]['file'];
                continue;
            }

            $missing[] = $slug;
        }

        $this->newLine();
        $this->info('=== UKUPNO ===');
        $this->line('Ukupno manastira u bazi: ' . $slugs->count());
        $this->line('Ukupno fajlova sa slikama: ' . $files->count());
        $this->line('Pronađeno poklapanja: ' . count($found));
        $this->line('Nedostaje slika: ' . count($missing));

        $this->newLine();
        $this->info('=== PRONAĐENO ===');
        foreach ($found as $slug => $file) {
            $this->line($slug . ' => ' . $file);
        }

        $this->newLine();
        $this->info('=== PREDLOG PREIMENOVANJA ===');
        if (count($renameSuggestions) === 0) {
            $this->line('Nema predloga za preimenovanje.');
        } else {
            foreach ($renameSuggestions as $row) {
                $this->line($row['old'] . '  =>  ' . $row['new']);
            }
        }

        $this->newLine();
        $this->info('=== NEDOSTAJU SLIKE ZA OVE SLUGOVE ===');
        if (count($missing) === 0) {
            $this->line('Ništa ne fali.');
        } else {
            foreach ($missing as $slug) {
                $this->line($slug);
            }
        }

        $reportPath = storage_path('app/monastery_images_report.txt');

        $report = [];
        $report[] = '=== UKUPNO ===';
        $report[] = 'Ukupno manastira u bazi: ' . $slugs->count();
        $report[] = 'Ukupno fajlova sa slikama: ' . $files->count();
        $report[] = 'Pronađeno poklapanja: ' . count($found);
        $report[] = 'Nedostaje slika: ' . count($missing);
        $report[] = '';

        $report[] = '=== PRONAĐENO ===';
        foreach ($found as $slug => $file) {
            $report[] = $slug . ' => ' . $file;
        }
        $report[] = '';

        $report[] = '=== PREDLOG PREIMENOVANJA ===';
        if (count($renameSuggestions) === 0) {
            $report[] = 'Nema predloga za preimenovanje.';
        } else {
            foreach ($renameSuggestions as $row) {
                $report[] = $row['old'] . ' => ' . $row['new'];
            }
        }
        $report[] = '';

        $report[] = '=== NEDOSTAJU SLIKE ZA OVE SLUGOVE ===';
        if (count($missing) === 0) {
            $report[] = 'Ništa ne fali.';
        } else {
            foreach ($missing as $slug) {
                $report[] = $slug;
            }
        }

        file_put_contents($reportPath, implode(PHP_EOL, $report));

        $this->newLine();
        $this->info("Izveštaj sačuvan u: {$reportPath}");

        return self::SUCCESS;
    }
}