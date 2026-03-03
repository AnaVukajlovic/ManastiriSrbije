<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Monastery;
use App\Models\Eparchy;

class MonasterySeeder extends Seeder
{
    public function run(): void
    {
        // Uveri se da su eparhije već posejane (EparchySeeder pre ovoga u DatabaseSeeder)
        $e = Eparchy::pluck('id', 'name'); // ['Žička' => 1, ...]

        // Ako nema ni jedna eparhija, bolje odmah prekini da ne upišemo null svuda
        // (možeš ovo obrisati ako želiš da dozvoliš null eparchy_id)
        // if ($e->isEmpty()) return;

        Monastery::truncate();

        Monastery::insert([
            [
                'name' => 'Studenica',
                'slug' => 'studenica',
                'region' => 'Raška',
                'city' => 'Kraljevo',
                'description' => 'Manastir iz 12. veka, UNESCO baština.',
                'latitude' => 43.4850,
                'longitude' => 20.5310,
                'eparchy_id' => $e['Žička'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Žiča',
                'slug' => 'zica',
                'region' => 'Raška',
                'city' => 'Kraljevo',
                'description' => 'Sedište prvog srpskog arhiepiskopa.',
                'latitude' => 43.7242,
                'longitude' => 20.6893,
                'eparchy_id' => $e['Žička'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Đurđevi Stupovi',
                'slug' => 'djurdjevi-stupovi',
                'region' => 'Raška',
                'city' => 'Novi Pazar',
                'description' => 'Zadužbina Stefana Nemanje.',
                'latitude' => 43.1535,
                'longitude' => 20.5207,
                'eparchy_id' => $e['Raško-prizrenska'] ?? ($e['Žička'] ?? null),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
