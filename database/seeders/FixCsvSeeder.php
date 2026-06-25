<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FixCsvSeeder extends Seeder
{
    public function run(): void
    {
        $path = storage_path('app/import/monasteries.csv');
        $lines = file($path);
        $fixedLines = [];

        foreach ($lines as $line) {
            $parts = explode(';', trim($line));
            
            // Ako je red "pukao" i ima više od 19 delova (zbog zareza u opisu)
            if (count($parts) > 19) {
                // Uzimamo prvih 9 kolona (slug do eparhije)
                $firstPart = array_slice($parts, 0, 9);
                // Sve između 9. i pretposlednje kolone spajamo u jedan opis
                $description = implode('; ', array_slice($parts, 9, -10)); 
                // Uzimamo poslednjih 10 kolona (image_url do kraja)
                $lastPart = array_slice($parts, -10);
                
                $fixedLine = implode(';', $firstPart) . ';"' . str_replace('"', "'", $description) . '";' . implode(';', $lastPart);
                $fixedLines[] = $fixedLine;
            } else {
                $fixedLines[] = $line;
            }
        }

        file_put_contents(storage_path('app/import/monasteries_fixed.csv'), implode(PHP_EOL, $fixedLines));
        $this->command->info("Fajl je popravljen i snimljen kao monasteries_fixed.csv!");
    }
}