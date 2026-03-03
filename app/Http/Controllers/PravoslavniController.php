<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\CalendarDay;

class PravoslavniController extends Controller
{
    public function index()
    {
        $today = Carbon::now();
        $doy = (int) $today->dayOfYear;

        $day = CalendarDay::query()
            ->where('day_of_year', $doy)
            ->first();

        $pouka = '„Smirenje je odeća božanstva.“';

        $upcoming = CalendarDay::query()
            ->where('day_of_year', '>', $doy)
            ->whereNotNull('feast_name')
            ->where('feast_name', '!=', '')
            ->orderBy('day_of_year')
            ->limit(5)
            ->get()
            ->map(function ($x) use ($today) {
                $date = Carbon::create($today->year, 1, 1)->addDays(((int) $x->day_of_year) - 1);
                return [
                    'label' => $x->feast_name,
                    'date'  => $date->format('j. F'),
                    'raw'   => $date,
                ];
            });

        return view('pages.pravoslavni.index', [
            'today'    => $today,
            'day'      => $day,
            'pouka'    => $pouka,
            'upcoming' => $upcoming,
        ]);
    }

    public function show(string $slug)
    {
        $today = Carbon::now();
        $doy = (int) $today->dayOfYear;

        $day = CalendarDay::query()
            ->where('day_of_year', $doy)
            ->first();

        // Jednostavna "mapa" modula (posle ćemo ovo puniti iz baze)
        $pages = [
            'osnovni-koncepti' => [
                'title' => 'Osnovni koncepti vere',
                'subtitle' => 'Kratko i jasno objašnjenje osnovnih pojmova pravoslavlja.',
                'category' => 'Učenje',
                'badge' => 'Početnici',
                'intro' => "Ovaj modul je namenjen svima koji žele da razumeju osnove pravoslavne vere.\nTekst ćemo kasnije proširiti i ubaciti u bazu.",
                'sections' => [
                    [
                        'title' => 'Šta je Liturgija?',
                        'text' => "Liturgija je centralno bogosluženje Crkve.\nU liturgijskom životu vernici se sjedinjuju u molitvi i zajednici.",
                        'bullets' => [
                            'Nedelja i praznici su liturgijski dani',
                            'Priprema za pričešće uključuje post i molitvu',
                        ],
                        'note' => 'Uvek je najbolje konsultovati svog parohijskog sveštenika za lične savete.',
                    ],
                ],
                'quote' => 'Smirenje je odeća božanstva.',
                'quote_by' => 'Sv. Jovan Lestvičnik',
            ],

            'kalendar' => [
                'title' => 'Pravoslavni kalendar',
                'subtitle' => 'Svetitelji, praznici, crvena slova i post.',
                'category' => 'Kalendar',
                'badge' => 'Praktično',
                'intro' => "Ovde prikazujemo osnovne informacije iz crkvenog kalendara.\nKasnije dodajemo napredni prikaz po datumima.",
                'sections' => [
                    [
                        'title' => 'Šta znači crveno slovo?',
                        'text' => "Crveno slovo označava veće praznike.\nTih dana se uobičajeno ne obavljaju teški poslovi i praznik se dočekuje molitveno.",
                    ],
                ],
            ],

            'datum-vaskrsa' => [
                'title' => 'Datum Vaskrsa',
                'subtitle' => 'Kako se računa datum Vaskrsa i zašto se menja svake godine.',
                'category' => 'Praznici',
                'badge' => 'Algoritam',
                'intro' => "Vaskrs se računa na osnovu paschalije.\nU aplikaciji ćemo povezati i izračunavanje po godinama.",
                'sections' => [
                    [
                        'title' => 'Osnovna ideja',
                        'text' => "Vaskrs je prva nedelja posle punog meseca koji dolazi nakon prolećne ravnodnevice.\nPravoslavna Crkva koristi julijansku paschaliju.",
                    ],
                ],
            ],

            'posni-recepti' => [
                'title' => 'Posni recepti',
                'subtitle' => 'Ideje za posna jela: na vodi, na ulju i riblji dani.',
                'category' => 'Kuhinja',
                'badge' => 'Recepti',
                'intro' => "Ovo je baza posnih recepata.\nKasnije dodajemo filtriranje po tipu posta i sastojcima.",
                'sections' => [
                    [
                        'title' => 'Primer (na vodi)',
                        'text' => "Posan krompir paprikaš.\nKrompir, luk, paprika, začini i voda — jednostavno i ukusno.",
                        'bullets' => [
                            'Bez ulja i životinjskih proizvoda',
                            'Prilagodljivo po ukusu',
                        ],
                    ],
                ],
            ],

            'zanimljivosti' => [
                'title' => 'Zanimljivosti',
                'subtitle' => 'Kratke činjenice, običaji i zanimljive priče.',
                'category' => 'Zanimljivo',
                'badge' => 'Kratko',
                'intro' => "Ovde idu kratke zanimljivosti iz pravoslavnog sveta.\nKasnije dodajemo pretragu i teme.",
                'sections' => [
                    [
                        'title' => 'Ikone',
                        'text' => "Ikone nisu ukras, već svedočanstvo vere.\nPoštovanje ikone se odnosi na onoga ko je na ikoni prikazan.",
                    ],
                ],
            ],
        ];

        $page = $pages[$slug] ?? null;

        if (!$page) {
            abort(404);
        }

        return view('pages.pravoslavni.show', [
            'today' => $today,
            'day'   => $day,
            'page'  => $page,
        ]);
    }
}