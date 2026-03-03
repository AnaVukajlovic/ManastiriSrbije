<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Monastery;
use App\Models\MonasteryImage;

class MonasteryImageSeeder extends Seeder
{
    public function run(): void
    {
        // Preporuka: koristi Wikimedia Commons URL-ove (legalnije za javne projekte).
        $imgs = [
            'studenica' => [
                ['url' => 'https://upload.wikimedia.org/wikipedia/commons/PLACEHOLDER1.jpg', 'caption' => 'Studenica – pogled 1'],
                ['url' => 'https://upload.wikimedia.org/wikipedia/commons/PLACEHOLDER2.jpg', 'caption' => 'Studenica – pogled 2'],
                ['url' => 'https://upload.wikimedia.org/wikipedia/commons/PLACEHOLDER3.jpg', 'caption' => 'Studenica – detalj'],
                ['url' => 'https://upload.wikimedia.org/wikipedia/commons/PLACEHOLDER4.jpg', 'caption' => 'Studenica – freske'],
                ['url' => 'https://upload.wikimedia.org/wikipedia/commons/PLACEHOLDER5.jpg', 'caption' => 'Studenica – okolina'],
            ],
            'zica' => [
                ['url' => 'https://upload.wikimedia.org/wikipedia/commons/PLACEHOLDER1.jpg', 'caption' => 'Žiča – pogled 1'],
                ['url' => 'https://upload.wikimedia.org/wikipedia/commons/PLACEHOLDER2.jpg', 'caption' => 'Žiča – pogled 2'],
                ['url' => 'https://upload.wikimedia.org/wikipedia/commons/PLACEHOLDER3.jpg', 'caption' => 'Žiča – detalj'],
                ['url' => 'https://upload.wikimedia.org/wikipedia/commons/PLACEHOLDER4.jpg', 'caption' => 'Žiča – enterijer'],
                ['url' => 'https://upload.wikimedia.org/wikipedia/commons/PLACEHOLDER5.jpg', 'caption' => 'Žiča – okolina'],
            ],
            'djurdjevi-stupovi' => [
                ['url' => 'https://upload.wikimedia.org/wikipedia/commons/PLACEHOLDER1.jpg', 'caption' => 'Đurđevi Stupovi – pogled 1'],
                ['url' => 'https://upload.wikimedia.org/wikipedia/commons/PLACEHOLDER2.jpg', 'caption' => 'Đurđevi Stupovi – pogled 2'],
                ['url' => 'https://upload.wikimedia.org/wikipedia/commons/PLACEHOLDER3.jpg', 'caption' => 'Đurđevi Stupovi – detalj'],
                ['url' => 'https://upload.wikimedia.org/wikipedia/commons/PLACEHOLDER4.jpg', 'caption' => 'Đurđevi Stupovi – ruševine'],
                ['url' => 'https://upload.wikimedia.org/wikipedia/commons/PLACEHOLDER5.jpg', 'caption' => 'Đurđevi Stupovi – panorama'],
            ],
        ];

        foreach ($imgs as $slug => $list) {
            $m = Monastery::where('slug', $slug)->first();
            if (!$m) continue;

            foreach ($list as $i => $img) {
                MonasteryImage::updateOrCreate(
                    ['monastery_id' => $m->id, 'url' => $img['url']],
                    [
                        'caption' => $img['caption'] ?? null,
                        'sort_order' => $i,
                    ]
                );
            }
        }
    }
}
