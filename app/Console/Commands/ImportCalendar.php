<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LiturgicalDay;

class ImportCalendar extends Command
{
    protected $signature = 'app:import-calendar';
    protected $description = 'Import SPC calendar from storage/app/seed/calendar_2026.json';

    public function handle(): int
    {
        $full = storage_path('app/seed/calendar_2026.json');

        if (!file_exists($full)) {
            $this->error("Nema fajla: {$full}");
            return self::FAILURE;
        }

        $raw = file_get_contents($full);
        $data = json_decode($raw, true);

        if (!is_array($data)) {
            $this->error("JSON nije validan: {$full}");
            $this->line("JSON error: " . json_last_error_msg());
            return self::FAILURE;
        }

        $count = 0;

        foreach ($data as $d) {
            if (!is_array($d) || empty($d['date'])) continue;

            LiturgicalDay::updateOrCreate(
                ['date' => $d['date']],
                [
                    'feast' => $d['feast'] ?? null,
                    'saint' => $d['saint'] ?? null,
                    'fasting' => $d['fasting'] ?? null,
                    'note' => $d['note'] ?? null,
                    'source' => $d['source'] ?? 'SPC',
                ]
            );

            $count++;
        }

        $this->info("Calendar imported: {$count}");
        return self::SUCCESS;
    }
}