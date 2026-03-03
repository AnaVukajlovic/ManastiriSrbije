<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\ContentCategory;
use App\Models\ContentItem;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        // ORTHODOX
        $orthodox = [
            [
                'title' => 'Molitve',
                'description' => 'Osnovne molitve za svakodnevni život.',
                'items' => [
                    ['title' => 'Oče naš', 'excerpt' => 'Najpoznatija hrišćanska molitva.', 'body' => 'Dodaj tekst kasnije.'],
                    ['title' => 'Bogorodice Djevo', 'excerpt' => 'Molitva Presvetoj Bogorodici.', 'body' => 'Dodaj tekst kasnije.'],
                ],
            ],
            [
                'title' => 'Post i praznici',
                'description' => 'Uvod u postove, praznike i tradiciju.',
                'items' => [
                    ['title' => 'Četiri velika posta', 'excerpt' => 'Smisao i osnovna pravila.', 'body' => 'Dodaj tekst kasnije.'],
                    ['title' => 'Krsna slava', 'excerpt' => 'Šta je slava i kako se obeležava.', 'body' => 'Dodaj tekst kasnije.'],
                ],
            ],
            [
                'title' => 'Ikone i simboli',
                'description' => 'Značenje ikona, krsta i crkvenih simbola.',
                'items' => [
                    ['title' => 'Šta predstavlja ikona', 'excerpt' => 'Ikona kao “prozor” ka duhovnom.', 'body' => 'Dodaj tekst kasnije.'],
                ],
            ],
        ];

        // EDUCATION
        $education = [
            [
                'title' => 'Istorija manastira',
                'description' => 'Periodi, značaj i uloga manastira kroz vekove.',
                'items' => [
                    ['title' => 'Nemanjići i zadužbine', 'excerpt' => 'Kako su nastajale zadužbine.', 'body' => 'Dodaj tekst kasnije.'],
                ],
            ],
            [
                'title' => 'Arhitektura',
                'description' => 'Raška i Moravska škola, freskopis i stilovi.',
                'items' => [
                    ['title' => 'Raška škola', 'excerpt' => 'Osnovne karakteristike.', 'body' => 'Dodaj tekst kasnije.'],
                    ['title' => 'Moravska škola', 'excerpt' => 'Ornamentika i stil.', 'body' => 'Dodaj tekst kasnije.'],
                ],
            ],
            [
                'title' => 'Kvizovi',
                'description' => 'Učenje kroz igru (uskoro).',
                'items' => [
                    ['title' => 'Kviz: osnovno', 'excerpt' => '10 pitanja (uskoro).', 'body' => 'Kasnije dodaj prava pitanja i logiku kviza.'],
                ],
            ],
        ];

        $this->seedModule('orthodox', $orthodox);
        $this->seedModule('education', $education);
    }

    private function seedModule(string $module, array $cats): void
    {
        foreach ($cats as $i => $c) {
            $cat = ContentCategory::updateOrCreate(
                ['module' => $module, 'slug' => Str::slug($c['title'])],
                [
                    'title' => $c['title'],
                    'description' => $c['description'] ?? null,
                    'sort_order' => $i,
                ]
            );

            foreach (($c['items'] ?? []) as $j => $it) {
                ContentItem::updateOrCreate(
                    ['category_id' => $cat->id, 'slug' => Str::slug($it['title'])],
                    [
                        'title' => $it['title'],
                        'excerpt' => $it['excerpt'] ?? null,
                        'body' => $it['body'] ?? null,
                        'source_url' => $it['source_url'] ?? null,
                        'sort_order' => $j,
                    ]
                );
            }
        }
    }
}
