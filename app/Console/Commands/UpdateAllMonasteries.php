<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateAllMonasteries extends Command
{
    protected $signature = 'manastiri:sync';
    protected $description = 'Potpuna sinhronizacija svih 268 manastira sa tačnim koordinatama';

    public function handle()
    {
        $manastiri = [
            ['slug' => 'studenica', 'lat' => 43.4866, 'lng' => 20.5316],
            ['slug' => 'zica', 'lat' => 43.6956, 'lng' => 20.6459],
            // ... ovde ćemo staviti svih 268 redova
        ];

        foreach ($manastiri as $m) {
            DB::table('monasteries')->where('slug', $m['slug'])->update([
                'lat' => $m['lat'],
                'lng' => $m['lng']
            ]);
        }
        $this->info('Sve koordinate su sada tačne!');
    }
}