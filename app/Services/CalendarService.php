<?php

namespace App\Services;

use Carbon\Carbon;

class CalendarService
{
    public static function getTodayData(): ?array
    {
        $file = storage_path('app/data/calendar_days_2026_sr_lat.csv');
        if (!file_exists($file)) return null;

        $today = Carbon::now()->format('Y-m-d');

        $h = fopen($file, 'r');
        if (!$h) return null;

        // CSV je kod tebe sa zarezima
        $delimiter = ',';

        $header = fgetcsv($h, 0, $delimiter);
        if (!$header) { fclose($h); return null; }

        // očisti BOM + trim
        $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string)$header[0]);
        $header = array_map(fn($v) => trim((string)$v), $header);
        $hn = count($header);

        while (($row = fgetcsv($h, 0, $delimiter)) !== false) {
            if (!$row || (count($row) === 1 && trim((string)$row[0]) === '')) continue;

            // RUČNO spajanje (bez array_combine) -> ne može da pukne
            $data = [];
            for ($i = 0; $i < $hn; $i++) {
                $key = $header[$i] ?? null;
                if ($key === null || $key === '') continue;
                $data[$key] = isset($row[$i]) ? trim((string)$row[$i]) : '';
            }

            if (($data['date'] ?? null) !== $today) {
                continue;
            }

            fclose($h);

            // Mapiranje na format koji tvoj home.blade očekuje
            return [
                'date'    => $data['date'] ?? '—',
                'feast'   => $data['feast_name'] ?? '—',
                'saint'   => $data['saint_name'] ?? '—',
                'fasting' => $data['fasting_type'] ?? '—',
                'note'    => $data['note'] ?? null,
                'red'     => !empty($data['is_red_letter']) && (string)$data['is_red_letter'] !== '0',
                // (ako ti zatreba kasnije)
                'raw'     => $data,
            ];
        }

        fclose($h);
        return null;
    }
}