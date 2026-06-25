<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ktitor;
use App\Models\Monastery;

class KtitorManastirSeeder extends Seeder
{
    public function run()
    {
        // Mapa: [Ktitor Slug => [Niz Manastir Slugova]]
$mapa = [
            'ana-dandolo'            => ['zica'],
            'ana-zena-stefana-nemanje' => ['studenica'],
            'car-dusan'              => ['prizren'], // Sveti Arhangeli
            'carica-jelena'          => ['prizren'],
            'jelena-anzujska'        => ['gradac', 'draca'],
            'kneginja-milica'        => ['ljubostinja', 'manastirica', 'manastirak-sumadijska'],
            'kralj-dragutin'         => ['tronosa', 'divljane', 'matejic'],
            'kralj-milutin'          => ['gracanica', 'banjska', 'sveti-nikola-vranje', 'nagoricino'], // Proveri da li se 'nagoricino' tako zove u listi
            'lazar-hrebveljanovic'   => ['ravanica', 'gornjak', 'gostilje'], // Dodao sam ono što je iz Lazarevog doba
            'simonida'               => ['gracanica', 'banjska'],
            'stefan-decanski'        => ['visoki-decani'],
            'stefan-lazarevic'       => ['manasija', 'koporin', 'tresije'],
            'stefan-nemanja'         => ['studenica', 'djurdjevi-stupovi'],
            'stefan-prvovencani'     => ['studenica', 'zica', 'nova-pavlica'],
            'stefan-radoslav'        => ['studenica'],
            'stefan-uros-i'          => ['sopocani', 'gradac'],
            'stefan-vladislav'       => ['mileseva'],
            'sveti-sava'             => ['zica', 'mileseva', 'studenica', 'isposnica-svetog-save'],
            'uros-nejaki'            => ['matejic'],
        ];

        foreach ($mapa as $ktitorSlug => $manastirSlugovi) {
            $ktitor = Ktitor::where('slug', $ktitorSlug)->first();
            
            if ($ktitor) {
                foreach ($manastirSlugovi as $mSlug) {
                    $monastery = Monastery::where('slug', $mSlug)->first();
                    if ($monastery) {
                        $ktitor->manastiri()->syncWithoutDetaching([$monastery->id]);
                    }
                }
            }
        }
    }
}