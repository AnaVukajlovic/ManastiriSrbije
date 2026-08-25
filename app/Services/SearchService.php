<?php

namespace App\Services;

use App\Models\Monastery;
use App\Models\Ktitor;
use App\Models\CalendarDay;
use App\Models\Curiosity;
use App\Models\Eparchy;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class SearchService
{
    private static array $cyrToLat = [
        'а'=>'a', 'б'=>'b', 'в'=>'v', 'г'=>'g', 'д'=>'d', 'ђ'=>'đ', 'е'=>'e', 'ж'=>'ž', 'з'=>'z', 'и'=>'i',
        'ј'=>'j', 'к'=>'k', 'л'=>'l', 'љ'=>'lj', 'м'=>'m', 'н'=>'n', 'њ'=>'nj', 'о'=>'o', 'п'=>'p', 'р'=>'r',
        'с'=>'s', 'т'=>'t', 'ћ'=>'ć', 'у'=>'u', 'ф'=>'f', 'х'=>'h', 'ц'=>'c', 'ч'=>'č', 'џ'=>'dž', 'ш'=>'š',
        'А'=>'A', 'Б'=>'B', 'В'=>'V', 'Г'=>'G', 'Д'=>'D', 'Ђ'=>'Đ', 'Е'=>'E', 'Ж'=>'Ž', 'З'=>'Z', 'И'=>'I',
        'Ј'=>'J', 'К'=>'K', 'Л'=>'L', 'Љ'=>'Lj', 'М'=>'M', 'Н'=>'N', 'Њ'=>'Nj', 'О'=>'O', 'П'=>'P', 'Р'=>'R',
        'С'=>'S', 'Т'=>'T', 'Ћ'=>'Ć', 'У'=>'U', 'Ф'=>'F', 'Х'=>'H', 'Ц'=>'C', 'Ч'=>'Č', 'Џ'=>'Dž', 'Ш'=>'Š',
    ];

    private static array $latToCyr = [
        'lj'=>'љ', 'nj'=>'њ', 'dž'=>'џ', 'dj'=>'ђ',
        'Lj'=>'Љ', 'Nj'=>'Њ', 'Dž'=>'Џ', 'Dj'=>'Ђ',
        'LJ'=>'Љ', 'NJ'=>'Њ', 'DŽ'=>'Џ', 'DJ'=>'Ђ',
        'a'=>'а', 'b'=>'б', 'v'=>'в', 'g'=>'г', 'd'=>'д', 'đ'=>'ђ', 'e'=>'е', 'ž'=>'ж', 'z'=>'з', 'i'=>'и',
        'j'=>'ј', 'k'=>'к', 'l'=>'л', 'm'=>'м', 'n'=>'н', 'o'=>'о', 'p'=>'п', 'r'=>'р',
        's'=>'с', 't'=>'т', 'ć'=>'ћ', 'u'=>'у', 'f'=>'ф', 'h'=>'х', 'c'=>'ц', 'č'=>'ч', 'š'=>'ш',
        'A'=>'А', 'B'=>'Б', 'V'=>'В', 'G'=>'Г', 'D'=>'Д', 'Đ'=>'Ђ', 'E'=>'Е', 'Ž'=>'Ж', 'Z'=>'З', 'I'=>'И',
        'J'=>'Ј', 'K'=>'К', 'L'=>'Л', 'M'=>'М', 'N'=>'Н', 'O'=>'О', 'P'=>'П', 'R'=>'Р',
        'S'=>'С', 'T'=>'Т', 'Ć'=>'Ћ', 'U'=>'У', 'F'=>'Ф', 'H'=>'Х', 'C'=>'Ц', 'Č'=>'Ч', 'Š'=>'Ш',
    ];

    /**
     * Preslovljava ćirilicu u latinicu
     */
    public static function cyrToLat(string $str): string
    {
        return strtr($str, self::$cyrToLat);
    }

    /**
     * Preslovljava latinicu u ćirilicu
     */
    public static function latToCyr(string $str): string
    {
        return strtr($str, self::$latToCyr);
    }

    /**
     * Uklanja srpske dijakritike (č, ć, š, ž, đ -> c, c, s, z, dj)
     */
    public static function stripDiacritics(string $str): string
    {
        $lat = self::cyrToLat($str);
        return str_replace(
            ['š', 'č', 'ć', 'ž', 'đ', 'Š', 'Č', 'Ć', 'Ž', 'Đ'],
            ['s', 'c', 'c', 'z', 'dj', 'S', 'C', 'C', 'Z', 'Dj'],
            $lat
        );
    }

    /**
     * Generiše varijacije sa i bez srpskih dijakritika (c->č,ć; s->š; z->ž; dj->đ)
     */
    public static function generateDiacriticVariants(string $word): array
    {
        $word = mb_strtolower($word, 'UTF-8');
        $wordLat = self::cyrToLat($word);
        $variants = [$word, $wordLat];

        // Zamenjujemo dj sa đ i obrnuto
        if (str_contains($wordLat, 'dj')) {
            $variants[] = str_replace('dj', 'đ', $wordLat);
        }
        if (str_contains($wordLat, 'đ')) {
            $variants[] = str_replace('đ', 'dj', $wordLat);
        }

        // Verzija bez kvačica
        $stripped = self::stripDiacritics($wordLat);
        if ($stripped !== $wordLat) {
            $variants[] = $stripped;
        }

        // Generisanje mogućih kombinacija kvačica
        $charList = preg_split('//u', $stripped, -1, PREG_SPLIT_NO_EMPTY);
        $tree = [''];

        foreach ($charList as $i => $ch) {
            $nextTree = [];
            $options = [$ch];

            if ($ch === 'c') {
                $options = ['c', 'č', 'ć'];
            } elseif ($ch === 's') {
                $options = ['s', 'š'];
            } elseif ($ch === 'z') {
                $options = ['z', 'ž'];
            }

            foreach ($tree as $prefix) {
                foreach ($options as $opt) {
                    $nextTree[] = $prefix . $opt;
                }
            }

            if (count($nextTree) > 32) {
                $tree = array_slice($nextTree, 0, 32);
            } else {
                $tree = $nextTree;
            }
        }

        foreach ($tree as $candidate) {
            if (!in_array($candidate, $variants, true)) {
                $variants[] = $candidate;
            }
            if (str_contains($candidate, 'dj')) {
                $withDj = str_replace('dj', 'đ', $candidate);
                if (!in_array($withDj, $variants, true)) {
                    $variants[] = $withDj;
                }
            }
        }

        return array_slice(array_values(array_unique($variants)), 0, 24);
    }

    /**
     * Generiše sve varijante pretrage (ćirilica, latinica sa kvačicama, latinica bez kvačica, reči)
     */
    public static function getSearchTerms(string $query): array
    {
        $q = trim($query);
        if ($q === '') {
            return [];
        }

        $terms = [$q];

        // 1. Latinica
        $lat = self::cyrToLat($q);
        if (!in_array($lat, $terms, true)) $terms[] = $lat;

        // 2. Ćirilica
        $cyr = self::latToCyr($lat);
        if (!in_array($cyr, $terms, true)) $terms[] = $cyr;

        // 3. Bez kvačica
        $stripped = self::stripDiacritics($lat);
        if (!in_array($stripped, $terms, true)) $terms[] = $stripped;

        // 4. Dijakritičke varijacije cele fraze
        $phraseVariants = self::generateDiacriticVariants($stripped);
        foreach ($phraseVariants as $pv) {
            if (!in_array($pv, $terms, true)) $terms[] = $pv;
            $cyrPv = self::latToCyr($pv);
            if (!in_array($cyrPv, $terms, true)) $terms[] = $cyrPv;
        }

        // 5. Pojedinačne reči za višerečne upite
        $words = preg_split('/[\s\-_,]+/u', $lat, -1, PREG_SPLIT_NO_EMPTY);
        if (count($words) > 1) {
            foreach ($words as $w) {
                if (mb_strlen($w) >= 3) {
                    $wVariants = self::generateDiacriticVariants($w);
                    foreach ($wVariants as $wv) {
                        if (!in_array($wv, $terms, true)) $terms[] = $wv;
                        $wCyr = self::latToCyr($wv);
                        if (!in_array($wCyr, $terms, true)) $terms[] = $wCyr;
                    }
                }
            }
        }

        return array_values(array_unique(array_filter($terms, fn($t) => mb_strlen($t) >= 2)));
    }

    /**
     * Pretražuje manastire po imenu, gradu, regionu, eparhiji, ktitoru, istoriji i opisu.
     */
    public static function searchMonasteries(array $terms, string $rawQuery): Collection
    {
        if (empty($terms)) {
            return collect();
        }

        $rawNormalized = mb_strtolower(self::stripDiacritics(self::cyrToLat($rawQuery)));

        return Monastery::query()
            ->with(['eparchy', 'ktitori'])
            ->where(function ($query) use ($terms) {
                foreach ($terms as $term) {
                    $query->orWhere('name', 'like', "%{$term}%")
                          ->orWhere('slug', 'like', "%{$term}%")
                          ->orWhere('city', 'like', "%{$term}%")
                          ->orWhere('region', 'like', "%{$term}%")
                          ->orWhere('ktitor', 'like', "%{$term}%")
                          ->orWhere('godina_izgradnje', 'like', "%{$term}%")
                          ->orWhere('description', 'like', "%{$term}%")
                          ->orWhere('history', 'like', "%{$term}%")
                          ->orWhere('excerpt', 'like', "%{$term}%")
                          ->orWhere('spiritual_life', 'like', "%{$term}%")
                          ->orWhere('architecture', 'like', "%{$term}%")
                          ->orWhere('art', 'like', "%{$term}%")
                          ->orWhereHas('eparchy', function ($eq) use ($term) {
                              $eq->where('name', 'like', "%{$term}%")
                                 ->orWhere('slug', 'like', "%{$term}%");
                          })
                          ->orWhereHas('ktitori', function ($kq) use ($term) {
                              $kq->where('name', 'like', "%{$term}%")
                                 ->orWhere('bio', 'like', "%{$term}%");
                          });
                }
            })
            ->get()
            ->map(function ($m) use ($rawNormalized) {
                $mCity = mb_strtolower(self::stripDiacritics(self::cyrToLat((string)$m->city)));
                $mRegion = mb_strtolower(self::stripDiacritics(self::cyrToLat((string)$m->region)));
                $mEparchy = mb_strtolower(self::stripDiacritics(self::cyrToLat((string)($m->eparchy?->name ?? ''))));
                $mKtitor = mb_strtolower(self::stripDiacritics(self::cyrToLat((string)$m->ktitor)));

                $matchNote = null;
                if ($rawNormalized !== '' && str_contains($mCity, $rawNormalized)) {
                    $matchNote = "Lokacija: " . ($m->city ?: $m->region);
                } elseif ($rawNormalized !== '' && str_contains($mEparchy, $rawNormalized)) {
                    $matchNote = "Eparhija: " . ($m->eparchy?->name ?: '');
                } elseif ($rawNormalized !== '' && str_contains($mKtitor, $rawNormalized)) {
                    $matchNote = "Ktitor: " . ($m->ktitor ?: '');
                } elseif ($m->city) {
                    $matchNote = "Mesto: {$m->city}";
                } elseif ($m->eparchy) {
                    $matchNote = "Eparhija: {$m->eparchy->name}";
                }

                $m->search_match_note = $matchNote;
                return $m;
            })
            ->sortByDesc(function ($m) use ($rawNormalized) {
                $nameNorm = mb_strtolower(self::stripDiacritics(self::cyrToLat((string)$m->name)));
                $slugNorm = mb_strtolower(self::stripDiacritics((string)$m->slug));
                $score = 0;
                if ($slugNorm === $rawNormalized) $score += 100;
                if (str_contains($slugNorm, $rawNormalized)) $score += 50;
                if (str_contains($nameNorm, $rawNormalized)) $score += 40;
                if ($m->city && str_contains(mb_strtolower(self::stripDiacritics(self::cyrToLat($m->city))), $rawNormalized)) $score += 20;
                return $score;
            })
            ->values();
    }

    /**
     * Pretražuje ktitore.
     */
    public static function searchKtitors(array $terms): Collection
    {
        if (empty($terms)) {
            return collect();
        }

        return Ktitor::query()
            ->with(['mainImage', 'manastiri'])
            ->where(function ($query) use ($terms) {
                foreach ($terms as $term) {
                    $query->orWhere('name', 'like', "%{$term}%")
                          ->orWhere('bio', 'like', "%{$term}%")
                          ->orWhere('slug', 'like', "%{$term}%");
                }
            })
            ->orderBy('name')
            ->get();
    }

    /**
     * Pretražuje kalendarske dane, praznike i svetitelje.
     */
    public static function searchCalendarDays(array $terms): Collection
    {
        if (empty($terms)) {
            return collect();
        }

        return CalendarDay::query()
            ->where(function ($query) use ($terms) {
                foreach ($terms as $term) {
                    $query->orWhere('feast_name', 'like', "%{$term}%")
                          ->orWhere('saint_name', 'like', "%{$term}%")
                          ->orWhere('fasting_type', 'like', "%{$term}%")
                          ->orWhere('note', 'like', "%{$term}%");
                }
            })
            ->orderBy('day_of_year')
            ->limit(24)
            ->get()
            ->map(function ($d) {
                $formattedDate = '—';
                if ($d->date) {
                    try {
                        $c = Carbon::parse($d->date);
                        $formattedDate = $c->translatedFormat('j. F');
                    } catch (\Throwable $e) {
                        $formattedDate = (string)$d->date;
                    }
                }
                $d->formatted_date = $formattedDate;
                return $d;
            });
    }

    /**
     * Pretražuje zanimljivosti.
     */
    public static function searchCuriosities(array $terms): Collection
    {
        if (empty($terms)) {
            return collect();
        }

        return Curiosity::query()
            ->where('is_published', true)
            ->where(function ($query) use ($terms) {
                foreach ($terms as $term) {
                    $query->orWhere('title', 'like', "%{$term}%")
                          ->orWhere('excerpt', 'like', "%{$term}%")
                          ->orWhere('content', 'like', "%{$term}%")
                          ->orWhere('category', 'like', "%{$term}%");
                }
            })
            ->orderByDesc('id')
            ->get();
    }

    /**
     * Pretražuje edukativne teme, lekcije, koncepte i posebne stranice.
     */
    public static function searchTopics(string $rawQuery, array $terms): Collection
    {
        $allTopics = self::getEducationalIndex();

        if (trim($rawQuery) === '') {
            return collect();
        }

        $results = collect();
        $rawNorm = mb_strtolower(self::stripDiacritics(self::cyrToLat($rawQuery)));

        foreach ($allTopics as $topic) {
            $score = 0;
            $haystack = mb_strtolower(self::stripDiacritics(self::cyrToLat(
                $topic['title'] . ' ' . $topic['description'] . ' ' . $topic['keywords'] . ' ' . $topic['category']
            )));

            if (str_contains($haystack, $rawNorm)) {
                $score += 10;
            }

            foreach ($terms as $t) {
                $tNorm = mb_strtolower(self::stripDiacritics(self::cyrToLat($t)));
                if (str_contains($haystack, $tNorm)) {
                    $score += 2;
                }
            }

            if ($score > 0) {
                $topic['score'] = $score;
                $results->push($topic);
            }
        }

        return $results->sortByDesc('score')->values();
    }

    /**
     * Baza edukativnih tema, lekcija i ključnih pravoslavnih pojmova
     */
    private static function getEducationalIndex(): array
    {
        return [
            [
                'id' => 'vaskrs-sve',
                'title' => 'Datum Vaskrsa i praznovanje Vaskrsenja Hristovog',
                'category' => 'Praznici',
                'url' => route('vaskrs.show', 'sve-o-vaskrsu'),
                'icon' => '✝️',
                'description' => 'Kako se računa datum Vaskrsa, zašto se pomera, pravoslavno pashalno računanje, tradicija i duhovni smisao Vaskrsa.',
                'keywords' => 'vaskrs uskrs vaskrsenje hristovo pasha racunanje prolecna ravnodnevica pun mesec strasna sedmica veliki petak post vaskrsnji praznik',
            ],
            [
                'id' => 'vaskrs-kalkulator',
                'title' => 'Kalkulator datuma Vaskrsa',
                'category' => 'Praznici',
                'url' => route('vaskrs.index'),
                'icon' => '📅',
                'description' => 'Pregled datuma Vaskrsa za tekuću i naredne godine po pravoslavnom crkvenom kalendaru.',
                'keywords' => 'vaskrs datum vaskrsa kalendar vaskrsa godina uskrs prolece',
            ],
            [
                'id' => 'osnovni-koncepti',
                'title' => 'Osnovni koncepti pravoslavne vere',
                'category' => 'Učenje i dogma',
                'url' => route('pravoslavni.osnovni-koncepti'),
                'icon' => '📖',
                'description' => 'Kratko i jasno objašnjenje osnovnih pojmova pravoslavlja: Sveta Liturgija, molitva, post, ispovest, pričešće i duhovni rast.',
                'keywords' => 'koncepti vera liturgija molitva post pricesce ispovest krstenje slava ikona svetinja duhovnost crkva bogosluzenje',
            ],
            [
                'id' => 'kalendar-modul',
                'title' => 'Pravoslavni crkveni kalendar',
                'category' => 'Kalendar',
                'url' => route('pravoslavni.kalendar.index'),
                'icon' => '📆',
                'description' => 'Pregled svetitelja, praznika, crvenih slova i tipova posta za svaki dan u godini.',
                'keywords' => 'kalendar crkveni kalendar crveno slovo praznici svetitelji post mesecnik dani danas u kalendaru',
            ],
            [
                'id' => 'posni-recepti',
                'title' => 'Posni recepti i duhovni smisao posta',
                'category' => 'Praktični život',
                'url' => route('pravoslavni.show', 'posni-recepti'),
                'icon' => '🍲',
                'description' => 'Ideje i recepti za posna jela na vodi, na ulju i riblje dane, uz objašnjenje duhovne dimenzije uzdržanja.',
                'keywords' => 'posni recepti recepti hrana posna jela na vodi na ulju riba kuvanje postenje uzdrzavanje kuhinja',
            ],
            [
                'id' => 'porodicno-stablo',
                'title' => 'Porodično stablo Nemanjića',
                'category' => 'Edukacija',
                'url' => route('edukacija.porodicno-stablo'),
                'icon' => '👑',
                'description' => 'Interaktivni prikaz dinastije Nemanjić od Stefana Nemanje do cara Uroša Nejakog sa svim granama i vladarima.',
                'keywords' => 'porodicno stablo stablo nemanjica nemanjici rodoslov dinastija zavida stefan nemanja sveti sava vukan stefan prvovencani radoslav vladislav uros dragutin milutin decanski dusan uros car',
            ],
            [
                'id' => 'timeline',
                'title' => 'Vremenska linija i hronologija dinastije Nemanjić',
                'category' => 'Edukacija',
                'url' => route('edukacija.timeline'),
                'icon' => '⏳',
                'description' => 'Hronološki pregled svih ključnih događaja, krunisanja, zadužbina i prekretnica srpske srednjovekovne istorije.',
                'keywords' => 'timeline vremenska linija hronologija istorija nemanjici vekovi 1166 1198 1217 1219 1346 1389 hilandar zica autokefalnost',
            ],
            [
                'id' => 'istorija-kultura',
                'title' => 'Istorija i kultura srednjovekovne Srbije',
                'category' => 'Edukacija',
                'url' => route('edukacija.show', 'istorija-kultura'),
                'icon' => '📜',
                'description' => 'Upoznaj uspon srpske države, pismenost, Miroslavljevo jevanđelje, vladarsku diplomatiju i bogato nasleđe.',
                'keywords' => 'istorija kultura srednji vek raska nemanjici pismenost miroslavljevo jevandjelje tradicija drzavnost',
            ],
            [
                'id' => 'srpska-crkva',
                'title' => 'Srpska pravoslavna crkva i Sveti Sava',
                'category' => 'Edukacija',
                'url' => route('edukacija.show', 'srpska-crkva'),
                'icon' => '☦️',
                'description' => 'Sticanje autokefalnosti 1219. godine, Žička arhiepiskopija, Pećka patrijaršija, Zakonopravilo i uloga Svetog Save.',
                'keywords' => 'srpska crkva spc sveti sava autokefalnost 1219 zicka arhiepiskopija pecka patrijarsija hilandar zakonopravilo krmcija',
            ],
            [
                'id' => 'arhitektura-umetnost',
                'title' => 'Arhitektura i freskoslikarstvo srpskih manastira',
                'category' => 'Edukacija',
                'url' => route('edukacija.show', 'arhitektura-umetnost'),
                'icon' => '🎨',
                'description' => 'Raška škola, Vardarski stil, Moravska škola, ikonopis, rozete i remek-dela freskoslikarstva poput Belog Anđela.',
                'keywords' => 'arhitektura umetnost freske freskoslikarstvo raska skola vardarski stil moravska skola beli andjeo ikonopis vizantijski stil',
            ],
            [
                'id' => 'manastiri-kao-zaduzbine',
                'title' => 'Manastiri kao zadužbine i duhovna središta',
                'category' => 'Edukacija',
                'url' => route('edukacija.show', 'manastiri-kao-zaduzbine'),
                'icon' => '🏛️',
                'description' => 'Zadužbinarstvo kod Srba, uloga manastira kao centara pismenosti, lečilišta, prepisivačkih škola i čuvara identiteta.',
                'keywords' => 'zaduzbine zaduzbinarstvo manastiri kao zaduzbine vladarske zaduzbine bolnice prepisivacke skole duhovna sredista',
            ],
            [
                'id' => 'srbija-pod-osmanlijama',
                'title' => 'Srbija pod Osmanlijama i čuvanje vere',
                'category' => 'Edukacija',
                'url' => route('edukacija.show', 'srbija-pod-osmanlijama'),
                'icon' => '⚔️',
                'description' => 'Položaj srpskog naroda i crkve pod turskom vlašću, obnova Pećke patrijaršije 1557, Makarije Sokolović i Velika seoba Srba.',
                'keywords' => 'osmanlije turci obnova pecke patrijarsije makarije sokolovic velika seoba srba arsenije carnojevic fruskogorski manastiri',
            ],
            [
                'id' => 'ucenje-interakcija',
                'title' => 'Interaktivno učenje i kvizovi znanja',
                'category' => 'Edukacija',
                'url' => route('edukacija.ucenje-interakcija'),
                'icon' => '💡',
                'description' => 'Proveri i utvrdi svoje znanje kroz istorijske i pravoslavne kvizove i interaktivne lekcije.',
                'keywords' => 'kviz ucenje test znanje provera pitanja istorijski kviz pravoslavni kviz edukacija interakcija',
            ],
            [
                'id' => 'mapa-svetinja',
                'title' => 'Interaktivna mapa svetinja i manastira Srbije',
                'category' => 'Mapa',
                'url' => route('map.index'),
                'icon' => '🗺️',
                'description' => 'Pronađi sve manastire na geografskoj mapi, filtriraj po eparhijama i gradovima i istraži lokacije svetinja.',
                'keywords' => 'mapa karta geografija lokacije gradovi rute navigacija gde se nalazi manastir',
            ],
        ];
    }
}
