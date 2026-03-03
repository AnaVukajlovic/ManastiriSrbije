<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Monastery;
use App\Models\VirtualTour;

class VirtualTourSeeder extends Seeder
{
    public function run(): void
    {
        $map = [
            'studenica' => [
                [
                    'title' => 'Studenica — 360° (Google)',
                    // NOTE: Ovo treba da bude pravi Google Maps embed URL (src iz iframe-a)
                    'embed_url' => 'https://www.google.com/maps/embed?pb=PASTE_EMBED_HERE'
                ],
            ],
            'zica' => [
                [
                    'title' => 'Žiča — 360° (Google)',
                    'embed_url' => 'https://www.google.com/maps/embed?pb=PASTE_EMBED_HERE'
                ],
            ],
            'djurdjevi-stupovi' => [
                [
                    'title' => 'Đurđevi Stupovi — 360° (Google)',
                    'embed_url' => 'https://www.google.com/maps/embed?pb=PASTE_EMBED_HERE'
                ],
            ],
        ];

        foreach ($map as $slug => $tours) {
            $m = Monastery::where('slug', $slug)->first();
            if (!$m) continue;

            foreach ($tours as $i => $t) {
                VirtualTour::updateOrCreate(
                    ['monastery_id' => $m->id, 'title' => $t['title']],
                    [
                        'provider' => 'google',
                        'embed_url' => $t['embed_url'],
                        'sort_order' => $i,
                    ]
                );
            }
        }
    }
}
