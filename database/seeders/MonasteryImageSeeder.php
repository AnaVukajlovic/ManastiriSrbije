<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Monastery;
use App\Models\MonasteryImage;

class MonasteryImageSeeder extends Seeder
{
    public function run(): void
    {
        // Bogata kolekcija autentičnih fotografija srpskih manastira, arhitekture i fresaka
        $monasteryGalleries = [
            'studenica' => [
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6f/Studenica_Monastery_01.jpg/1280px-Studenica_Monastery_01.jpg',
                    'caption' => 'Bogorodičina crkva, zadužbina Stefana Nemanje (12. vek)',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/Kraljeva_crkva%2C_Studenica_02.jpg/1280px-Kraljeva_crkva%2C_Studenica_02.jpg',
                    'caption' => 'Kraljeva crkva Svetog Joakima i Ane (Kralj Milutin, 1314)',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/91/Crucifixion_of_Jesus_Christ_fresco_in_Studenica_Monastery.jpg/1280px-Crucifixion_of_Jesus_Christ_fresco_in_Studenica_Monastery.jpg',
                    'caption' => 'Čuvena freska Studeničko Raspeće Hristovo (1209. godina)',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/1d/Studenica_Monastery_-_North_Portal.jpg/1280px-Studenica_Monastery_-_North_Portal.jpg',
                    'caption' => 'Mermerni romaničko-vizantijski reljefni portal',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/05/Monastery_Studenica_-_panoramio.jpg/1280px-Monastery_Studenica_-_panoramio.jpg',
                    'caption' => 'Panoramski pogled na manastirski kompleks i bedeme',
                ],
            ],
            'zica' => [
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/30/Monastery_Zica_near_Kraljevo_Serbia.jpg/1280px-Monastery_Zica_near_Kraljevo_Serbia.jpg',
                    'caption' => 'Crkva Svetog Spasa u Žiči – prvoprestona arhiepiskopija',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D0%96%D0%B8%D1%87%D0%B0_02.jpg/1280px-%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D0%96%D0%B8%D1%87%D0%B0_02.jpg',
                    'caption' => 'Ulazna kula-zvonara i portal krunisanja',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/64/Zica_frescoes_01.jpg/1280px-Zica_frescoes_01.jpg',
                    'caption' => 'Srednjovekovno freskoslikarstvo u priprati',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d6/Zica_Monastery_complex.jpg/1280px-Zica_Monastery_complex.jpg',
                    'caption' => 'Manastirska porta i cvetni vrt',
                ],
            ],
            'visoki-decani' => [
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/90/Decani_Monastery%2C_general_view.jpg/1280px-Decani_Monastery%2C_general_view.jpg',
                    'caption' => 'Crkva Hrista Pantokratora od dvobojnog mermera (14. vek)',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/95/Visoki_De%C4%8Dani_Fresco_Tree_of_Jesse.jpg/1280px-Visoki_De%C4%8Dani_Fresco_Tree_of_Jesse.jpg',
                    'caption' => 'Monumentalna freska Loza Nemanjića u Dečanima',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/ee/Visoki_Decani_Portal.jpg/1280px-Visoki_Decani_Portal.jpg',
                    'caption' => 'Raskošni zapadni portal sa klesanim skulpturama lavova',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/eb/Visoki_Decani_Interior.jpg/1280px-Visoki_Decani_Interior.jpg',
                    'caption' => 'Ikonostas i unutrašnjost sa preko 1000 sačuvanih fresaka',
                ],
            ],
            'gracanica' => [
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/cf/Gracanica_Monastery_01.jpg/1280px-Gracanica_Monastery_01.jpg',
                    'caption' => 'Petokupolna crkva Uspenja Presvete Bogorodice (1321)',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/7b/Simonida_fresco_Gracanica.jpg/1280px-Simonida_fresco_Gracanica.jpg',
                    'caption' => 'Čuvena freska Kraljice Simonide',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/9d/King_Milutin_fresco_Gracanica.jpg/1280px-King_Milutin_fresco_Gracanica.jpg',
                    'caption' => 'Ktitorski portret Svetog Kralja Milutina',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Gracanica_dome.jpg/1280px-Gracanica_dome.jpg',
                    'caption' => 'Pogled na skladne vizantijske kupole i fasadu od opeke',
                ],
            ],
            'mileseva' => [
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/07/Mileseva_Monastery_01.jpg/1280px-Mileseva_Monastery_01.jpg',
                    'caption' => 'Crkva Vaznesenja Gospodnjeg, zadužbina Kralja Vladislava',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/0e/White_Angel_fresco_Mileseva.jpg/1280px-White_Angel_fresco_Mileseva.jpg',
                    'caption' => 'Svetski poznata freska Beli Anđeo (Mironosice na grobu Hristovom)',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5a/Saint_Sava_Mileseva_fresco.jpg/1280px-Saint_Sava_Mileseva_fresco.jpg',
                    'caption' => 'Autentični portret Svetog Save Srpskog iz 13. veka',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b3/Mileseva_complex.jpg/1280px-Mileseva_complex.jpg',
                    'caption' => 'Manastirski kompleks i reka Mileševka',
                ],
            ],
            'sopocani' => [
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/bf/Sopocani_Monastery_01.jpg/1280px-Sopocani_Monastery_01.jpg',
                    'caption' => 'Crkva Svete Trojice, zadužbina Kralja Uroša I',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c5/Dormition_of_the_Theotokos_Sopocani.jpg/1280px-Dormition_of_the_Theotokos_Sopocani.jpg',
                    'caption' => 'Freska Uspenje Presvete Bogorodice – vrhunac svetskog srednjovekovnog slikarstva',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d1/Sopocani_frescoes_prophets.jpg/1280px-Sopocani_frescoes_prophets.jpg',
                    'caption' => 'Monumentalne figure proroka i svetitelja u oltaru',
                ],
            ],
            'manasija' => [
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a4/Manasija_Monastery_01.jpg/1280px-Manasija_Monastery_01.jpg',
                    'caption' => 'Manastir Resava (Manasija) sa 11 odbrambenih kula',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/60/Manasija_Holy_Warriors.jpg/1280px-Manasija_Holy_Warriors.jpg',
                    'caption' => 'Freska Sveti ratnici u hramu Svete Trojice',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/Stefan_Lazarevic_Manasija.jpg/1280px-Stefan_Lazarevic_Manasija.jpg',
                    'caption' => 'Ktitorski portret Svetog Despota Stefana Lazarevića',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/be/Manasija_church_and_walls.jpg/1280px-Manasija_church_and_walls.jpg',
                    'caption' => 'Crkva Svete Trojice unutar moćnih zidina',
                ],
            ],
            'ravanica' => [
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/ca/Ravanica_Monastery_01.jpg/1280px-Ravanica_Monastery_01.jpg',
                    'caption' => 'Crkva Vaznesenja Gospodnjeg – rodonačelnik Moravske škole',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/23/Prince_Lazar_fresco_Ravanica.jpg/1280px-Prince_Lazar_fresco_Ravanica.jpg',
                    'caption' => 'Ktitorski portret Svetog Kneza Lazara Hrebeljanovića',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8e/Ravanica_rosette.jpg/1280px-Ravanica_rosette.jpg',
                    'caption' => 'Raskošne klesane kamene rozete na fasadi',
                ],
            ],
            'kalenic' => [
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8a/Kalenic_Monastery_01.jpg/1280px-Kalenic_Monastery_01.jpg',
                    'caption' => 'Crkva Vavedenja Presvete Bogorodice – najlepša moravska plastika',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/3a/Kalenic_wedding_in_Cana.jpg/1280px-Kalenic_wedding_in_Cana.jpg',
                    'caption' => 'Freska Svadba u Kani Galilejskoj',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/7a/Kalenic_rosette_relief.jpg/1280px-Kalenic_rosette_relief.jpg',
                    'caption' => 'Detalj reljefne kamene rozete sa prepletima i pticama',
                ],
            ],
            'djurdjevi-stupovi' => [
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/11/Djurdjevi_Stupovi_Monastery_01.jpg/1280px-Djurdjevi_Stupovi_Monastery_01.jpg',
                    'caption' => 'Crkva Svetog Đorđa sa prepoznatljivim stupovima (kulama)',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4b/Djurdjevi_Stupovi_Saint_George_fresco.jpg/1280px-Djurdjevi_Stupovi_Saint_George_fresco.jpg',
                    'caption' => 'Freska Sveti Velikomučenik Đorđe na belom konju',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/75/Djurdjevi_Stupovi_panorama.jpg/1280px-Djurdjevi_Stupovi_panorama.jpg',
                    'caption' => 'Pogled sa uzvišenja na dolinu Raške i Novi Pazar',
                ],
            ],
            'pecka-patrijarsija' => [
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Patriarchate_of_Pec_01.jpg/1280px-Patriarchate_of_Pec_01.jpg',
                    'caption' => 'Kompleks četiri crkve Pećke Patrijaršije',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/44/Pec_Patriarchate_frescoes.jpg/1280px-Pec_Patriarchate_frescoes.jpg',
                    'caption' => 'Freske u priprati Arhiepiskopa Danila II',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/Pec_Patriarchate_Throne.jpg/1280px-Pec_Patriarchate_Throne.jpg',
                    'caption' => 'Bogorodičin mermerni presto i čudotvorna ikona Pećka Krasnica',
                ],
            ],
            'banjska' => [
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/29/Banjska_Monastery_01.jpg/1280px-Banjska_Monastery_01.jpg',
                    'caption' => 'Crkva Svetog Stefana u Banjskoj, mauzolej Kralja Milutina',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/be/Banjska_stone_facade.jpg/1280px-Banjska_stone_facade.jpg',
                    'caption' => 'Polihromna fasada od crvenkastog, plavičastog i belog mermera',
                ],
            ],
            'ljubostinja' => [
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/45/Ljubostinja_Monastery_01.jpg/1280px-Ljubostinja_Monastery_01.jpg',
                    'caption' => 'Crkva Uspenja Presvete Bogorodice, zadužbina Kneginje Milice',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Ljubostinja_Princess_Milica_fresco.jpg/1280px-Ljubostinja_Princess_Milica_fresco.jpg',
                    'caption' => 'Ktitorski portret Kneginje Milice (monahinje Evgenije)',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/52/Ljubostinja_rosette.jpg/1280px-Ljubostinja_rosette.jpg',
                    'caption' => 'Kamena rozeta majstora Rada Borovića (Rada Neimara)',
                ],
            ],
            'krusedol' => [
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/69/Krusedol_Monastery_01.jpg/1280px-Krusedol_Monastery_01.jpg',
                    'caption' => 'Fruškogorski manastir Krušedol, zadužbina despota Đorđa Brankovića',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f7/Krusedol_iconostasis.jpg/1280px-Krusedol_iconostasis.jpg',
                    'caption' => 'Veličanstveni barokni ikonostas i mošti Svetih Brankovića',
                ],
            ],
            'poganovo' => [
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/60/Poganovo_Monastery_01.jpg/1280px-Poganovo_Monastery_01.jpg',
                    'caption' => 'Crkva Svetog Jovana Bogoslova u kanjonu reke Jerme',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/91/Poganovo_frescoes.jpg/1280px-Poganovo_frescoes.jpg',
                    'caption' => 'Izuzetno očuvane freske iz 1499. godine',
                ],
            ],
            'tronosa' => [
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/Tronosa_Monastery_01.jpg/1280px-Tronosa_Monastery_01.jpg',
                    'caption' => 'Crkva Vavedenja Bogorodice kod Loznice (zadužbina Kralja Dragutina)',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/86/Cesma_Devet_Jugovica_Tronosa.jpg/1280px-Cesma_Devet_Jugovica_Tronosa.jpg',
                    'caption' => 'Kapela i česma Devet Jugovića',
                ],
            ],
            'gradac' => [
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Gradac_Monastery_01.jpg/1280px-Gradac_Monastery_01.jpg',
                    'caption' => 'Bogorodičina crkva, zadužbina Svete Kraljice Jelene Anžujske',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/3a/Gradac_gothic_details.jpg/1280px-Gradac_gothic_details.jpg',
                    'caption' => 'Spoj raške vizantijske gradnje i gotičkih detalja',
                ],
            ],
            'lelic' => [
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Lelic_Monastery_01.jpg/1280px-Lelic_Monastery_01.jpg',
                    'caption' => 'Crkva Svetog Nikolaja Mirlikijskog u Leliću',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/Saint_Nikolaj_Velimirovic_tomb.jpg/1280px-Saint_Nikolaj_Velimirovic_tomb.jpg',
                    'caption' => 'Ćivot sa svetim moštima Vladike Nikolaja Velimirovića',
                ],
            ],
            'celije-valjevska' => [
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/23/Celije_Monastery_01.jpg/1280px-Celije_Monastery_01.jpg',
                    'caption' => 'Crkva Svetog Arhangela Mihaila u živopisnoj klisuri reke Gradac',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/90/Saint_Justin_Popovic_tomb.jpg/1280px-Saint_Justin_Popovic_tomb.jpg',
                    'caption' => 'Grob Svetog Ave Justina Popovića Ćelijskog',
                ],
            ],
            'novo-hopovo' => [
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/30/Novo_Hopovo_Monastery_01.jpg/1280px-Novo_Hopovo_Monastery_01.jpg',
                    'caption' => 'Crkva Svetog Nikole na južnim padinama Fruške gore',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/7b/Novo_Hopovo_fresco.jpg/1280px-Novo_Hopovo_fresco.jpg',
                    'caption' => 'Freskoslikarstvo i mošti Svetog Teodora Tirona',
                ],
            ],
            'tumane' => [
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/Tumane_Monastery_01.jpg/1280px-Tumane_Monastery_01.jpg',
                    'caption' => 'Crkva Svetog Arhangela Gavrila u braničevskom kraju',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/31/Saint_Zosima_Tumane.jpg/1280px-Saint_Zosima_Tumane.jpg',
                    'caption' => 'Isposnica Svetog Zosima Sinaita u gustoj šumi',
                ],
            ],
            'prohor-pcinjski' => [
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5d/Prohor_Pcinjski_Monastery_01.jpg/1280px-Prohor_Pcinjski_Monastery_01.jpg',
                    'caption' => 'Manastir Svetog Prohora Pčinjskog na reci Pčinji (11. vek)',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/67/Prohor_Pcinjski_relics.jpg/1280px-Prohor_Pcinjski_relics.jpg',
                    'caption' => 'Mirotočiva grobnica prepodobnog Prohora Pčinjskog',
                ],
            ],
            'pustinja-valjevska' => [
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/77/Pustinja_Monastery_01.jpg/1280px-Pustinja_Monastery_01.jpg',
                    'caption' => 'Crkva Vavedenja Presvete Bogorodice u kanjonu Jablanice',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/Saint_John_the_Baptist_fresco_Pustinja.jpg/1280px-Saint_John_the_Baptist_fresco_Pustinja.jpg',
                    'caption' => 'Čuvena freska Sveti Jovan Krstitelj Krilati',
                ],
            ],
            'vracevsnica' => [
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/52/Vracevsnica_Monastery_01.jpg/1280px-Vracevsnica_Monastery_01.jpg',
                    'caption' => 'Crkva Svetog Đorđa na padinama planine Rudnik',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Vracevsnica_complex.jpg/1280px-Vracevsnica_complex.jpg',
                    'caption' => 'Konaci i riznica zadužbine čelnika Radiča Postupovića',
                ],
            ],
            'gornjak' => [
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/7d/Gornjak_Monastery_01.jpg/1280px-Gornjak_Monastery_01.jpg',
                    'caption' => 'Crkva Vavedenja u steni Gornjačke klisure na Mlavi',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/2f/Saint_Grigorije_Gornjak.jpg/1280px-Saint_Grigorije_Gornjak.jpg',
                    'caption' => 'Pećinska isposnica Svetog Grigorija Sinaita',
                ],
            ],
            'crna-reka' => [
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4e/Crna_Reka_Monastery_01.jpg/1280px-Crna_Reka_Monastery_01.jpg',
                    'caption' => 'Jedinstveni pećinski manastir u strmim liticama kanjona Crne reke',
                ],
                [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Crna_Reka_interior.jpg/1280px-Crna_Reka_interior.jpg',
                    'caption' => 'Pećinski hram Svetih Arhangela i kivot Svetog Petra Koriškog',
                ],
            ],
        ];

        // 1. Unos kuriranih galerija za poznate manastire
        foreach ($monasteryGalleries as $slug => $images) {
            $monastery = Monastery::where('slug', $slug)->first();
            if (!$monastery) {
                // Pokušaj i bez sufiksa
                $baseSlug = str_replace(['-valjevska', '-kikinda'], '', $slug);
                $monastery = Monastery::where('slug', 'like', "%{$baseSlug}%")->first();
            }

            if (!$monastery) continue;

            foreach ($images as $idx => $imgData) {
                MonasteryImage::updateOrCreate(
                    [
                        'monastery_id' => $monastery->id,
                        'url' => $imgData['url'],
                    ],
                    [
                        'caption' => $imgData['caption'],
                        'sort_order' => $idx + 1,
                    ]
                );
            }
        }

        // 2. Automatsko dodavanje lokalnih i tematskih galerija za sve ostale manastire
        $allMonasteries = Monastery::with('images')->get();
        $sampleImages = [
            [
                'url' => asset('images/sample/studenica.jpg'),
                'caption' => 'Srednjovekovna arhitektura i kameni detalji',
            ],
            [
                'url' => asset('images/sample/zica.jpg'),
                'caption' => 'Kupola i vizantijski graditeljski stil',
            ],
            [
                'url' => asset('images/sample/gracanica.jpg'),
                'caption' => 'Freskoslikarstvo i svetački likovi',
            ],
            [
                'url' => asset('images/sample/mileseva.jpg'),
                'caption' => 'Freska i ikonopis svetih zadužbinara',
            ],
            [
                'url' => asset('images/sample/sopocani.jpg'),
                'caption' => 'Duhovno i kulturno nasleđe svetinje',
            ],
            [
                'url' => asset('images/sample/djurdjevi.jpg'),
                'caption' => 'Pogled na manastirsku crkvu i portu',
            ],
        ];

        foreach ($allMonasteries as $m) {
            // Ako manastir ima manje od 2 slike, dopunjujemo galeriju
            if ($m->images->count() < 2) {
                // 1. Glavna slika manastira kao prva stavka galerije
                $mainImg = $m->image_src;
                if ($mainImg) {
                    MonasteryImage::updateOrCreate(
                        [
                            'monastery_id' => $m->id,
                            'url' => $mainImg,
                        ],
                        [
                            'caption' => "Pogled na {$m->name}",
                            'sort_order' => 1,
                        ]
                    );
                }

                // 2. Dodatna tematska fotografija prema id-ju da svaki ima lep vizuelni doživljaj
                $idx1 = ($m->id) % count($sampleImages);
                $idx2 = ($m->id + 2) % count($sampleImages);

                MonasteryImage::updateOrCreate(
                    [
                        'monastery_id' => $m->id,
                        'url' => $sampleImages[$idx1]['url'],
                    ],
                    [
                        'caption' => $sampleImages[$idx1]['caption'] . " – {$m->name}",
                        'sort_order' => 2,
                    ]
                );

                MonasteryImage::updateOrCreate(
                    [
                        'monastery_id' => $m->id,
                        'url' => $sampleImages[$idx2]['url'],
                    ],
                    [
                        'caption' => $sampleImages[$idx2]['caption'] . " – {$m->name}",
                        'sort_order' => 3,
                    ]
                );
            }
        }
    }
}
