<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Quote;

class ImportQuotes extends Command
{
    protected $signature = 'app:import-quotes';
    protected $description = 'Import quotes from storage/app/seed/quotes.json';

    public function handle(): int
    {
        $full = storage_path('app/seed/quotes.json');

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

        foreach ($data as $q) {
            if (!is_array($q) || empty($q['text'])) continue;

            Quote::updateOrCreate(
                ['text' => $q['text']],
                [
                    'author' => $q['author'] ?? null,
                    'source' => $q['source'] ?? null,
                    'is_active' => $q['is_active'] ?? true,
                    'weight' => $q['weight'] ?? 1,
                ]
            );

            $count++;
        }

        $this->info("Quotes imported: {$count}");
        return self::SUCCESS;
    }
}