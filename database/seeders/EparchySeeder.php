<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Eparchy;

class EparchySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'Žička',
            'Raško-prizrenska',
            'Beogradsko-karlovačka',
            'Šumadijska',
            'Niška',
            'Banatska',
            'Bačka',
        ];

        foreach ($items as $name) {
        Eparchy::updateOrCreate(
            ['name' => $name],
            [] // nema slug kolone u tabeli
        );

        }
    }
}
