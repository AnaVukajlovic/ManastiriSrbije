<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Saint;

class SaintSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'name' => 'Sveti Simeon Mirotočivi',
                'short_name' => 'Simeon',
                'icon' => '🕯️',
                'bio' => 'Osnivač dinastije Nemanjića; monah Simeon.',
                'source' => 'Ručno',
            ],
            [
                'name' => 'Sveti Sava',
                'short_name' => 'Sava',
                'icon' => '📜',
                'bio' => 'Prvi arhiepiskop srpski; utemeljivač SPC.',
                'source' => 'Ručno',
            ],
        ];

        foreach ($items as $it) {
            $slug = Str::slug($it['name'], '-');

            Saint::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $it['name'],
                    'short_name' => $it['short_name'],
                    'icon' => $it['icon'],
                    'bio' => $it['bio'],
                    'source' => $it['source'],
                    'is_active' => true,
                ]
            );
        }
    }
}