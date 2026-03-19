<?php

namespace App\Http\Controllers;

use App\Models\CalendarDay;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PravoslavniController extends Controller
{
    public function index()
    {
        $today = Carbon::now();
        $day = $this->getCalendarDayForDate($today);

        $pouka = $this->getDailyThought($today);

        $upcoming = $this->getUpcomingFeasts($today, 5);

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
        $day = $this->getCalendarDayForDate($today);

        $pages = $this->getPravoslavniPages();
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

    private function getCalendarDayForDate(Carbon $date): ?CalendarDay
    {
        $doy = (int) $date->dayOfYear;

        return CalendarDay::query()
            ->where('day_of_year', $doy)
            ->first();
    }

    private function getDailyThought(Carbon $date): string
    {
        $thoughts = [
            'Smirenje je odeća božanstva.',
            'Gde je ljubav, tamo je i Bog.',
            'Molitva je disanje duše.',
            'Mir u srcu je početak duhovne snage.',
            'Strpljenje i vera vode čoveka ka miru.',
            'Ne traži mnogo od drugih, već najpre popravi sebe.',
            'Dobrota učinjena u tišini najlepše svedoči o veri.',
            'Onaj ko prašta, oslobađa i sebe i drugoga.',
            'U trpljenju se pokazuje snaga vere.',
            'Pokajanje nije slabost, nego novi početak.',
        ];

        $index = ((int) $date->dayOfYear - 1) % count($thoughts);

        return $thoughts[$index];
    }

    private function getUpcomingFeasts(Carbon $today, int $limit = 5): Collection
    {
        $currentDayOfYear = (int) $today->dayOfYear;

        $currentYearFeasts = CalendarDay::query()
            ->where('day_of_year', '>', $currentDayOfYear)
            ->whereNotNull('feast_name')
            ->where('feast_name', '!=', '')
            ->orderBy('day_of_year')
            ->limit($limit)
            ->get();

        if ($currentYearFeasts->count() < $limit) {
            $remaining = $limit - $currentYearFeasts->count();

            $nextYearFeasts = CalendarDay::query()
                ->whereNotNull('feast_name')
                ->where('feast_name', '!=', '')
                ->orderBy('day_of_year')
                ->limit($remaining)
                ->get();

            $allFeasts = $currentYearFeasts->concat($nextYearFeasts);
        } else {
            $allFeasts = $currentYearFeasts;
        }

        return $allFeasts->values()->map(function ($item, $index) use ($today, $currentDayOfYear) {
            $itemDayOfYear = (int) $item->day_of_year;

            $year = $itemDayOfYear > $currentDayOfYear
                ? $today->year
                : $today->copy()->addYear()->year;

            $date = Carbon::create($year, 1, 1)->addDays($itemDayOfYear - 1);

            return [
                'label' => $item->feast_name,
                'date'  => $date->translatedFormat('j. F'),
                'raw'   => $date,
            ];
        });
    }

    private function getPravoslavniPages(): array
    {
        return [
            'osnovni-koncepti' => [
                'title' => 'Osnovni koncepti vere',
                'subtitle' => 'Kratko i jasno objašnjenje osnovnih pojmova pravoslavlja.',
                'category' => 'Učenje',
                'badge' => 'Početnici',
                'intro' => "Ovaj modul je namenjen svima koji žele da razumeju osnove pravoslavne vere.\nKasnije možeš proširiti sadržaj i prebaciti ga u bazu ili posebne fajlove.",
                'sections' => [
                    [
                        'title' => 'Šta je Liturgija?',
                        'text' => "Liturgija je centralno bogosluženje Crkve.\nU liturgijskom životu vernici se sjedinjuju u molitvi, zajednici i pričešću.",
                        'bullets' => [
                            'Nedelja i praznici imaju posebno mesto u liturgijskom životu',
                            'Priprema za pričešće uključuje molitvu, post i ispovest kada je potrebno',
                        ],
                        'note' => 'Za ličnu duhovnu praksu i konkretne savete uvek je najbolje posavetovati se sa sveštenikom.',
                    ],
                    [
                        'title' => 'Molitva i post',
                        'text' => "Molitva i post nisu samo pravila, već sredstva duhovnog rasta.\nNjihov cilj je smirenje, sabranost i približavanje Bogu.",
                    ],
                ],
                'quote' => 'Smirenje je odeća božanstva.',
                'quote_by' => 'Sveti Jovan Lestvičnik',
            ],

            'kalendar' => [
                'title' => 'Pravoslavni kalendar',
                'subtitle' => 'Svetitelji, praznici, crvena slova i post.',
                'category' => 'Kalendar',
                'badge' => 'Praktično',
                'intro' => "Ovde se nalaze osnovne informacije iz crkvenog kalendara.\nKasnije možeš dodati napredni prikaz po datumima, mesecima i vrstama praznika.",
                'sections' => [
                    [
                        'title' => 'Šta znači crveno slovo?',
                        'text' => "Crveno slovo označava veće praznike i dane od posebnog značaja.\nTakvi dani se tradicionalno dočekuju svečanije, uz uzdržavanje od težih poslova i veće molitveno raspoloženje.",
                    ],
                    [
                        'title' => 'Šta znači tip posta?',
                        'text' => "Tip posta pokazuje kakav je crkveni poredak ishrane toga dana.\nU praksi je važno i lično rasuđivanje i savet duhovnika ili sveštenika.",
                    ],
                ],
            ],

            'datum-vaskrsa' => [
                'title' => 'Datum Vaskrsa',
                'subtitle' => 'Kako se računa datum Vaskrsa i zašto se menja svake godine.',
                'category' => 'Praznici',
                'badge' => 'Objašnjenje',
                'intro' => "Datum Vaskrsa nije fiksan, već se određuje prema crkvenom računanju vremena.\nKasnije ovde možeš dodati i automatski kalkulator po godinama.",
                'sections' => [
                    [
                        'title' => 'Osnovna ideja',
                        'text' => "Vaskrs se određuje kao prva nedelja posle punog meseca koji dolazi nakon prolećne ravnodnevice.\nPravoslavna Crkva koristi svoje paschalno računanje zasnovano na julijanskoj tradiciji.",
                    ],
                    [
                        'title' => 'Zašto datum nije isti svake godine?',
                        'text' => "Zato što zavisi od odnosa nedelje, ravnodnevice i punog meseca.\nZbog toga se datum Vaskrsa pomera unutar određenog perioda u proleće.",
                    ],
                ],
            ],

            'posni-recepti' => [
                'title' => 'Posni recepti',
                'subtitle' => 'Ideje za posna jela: na vodi, na ulju i riblji dani.',
                'category' => 'Kuhinja',
                'badge' => 'Recepti',
                'intro' => "Ovaj modul je zamišljen kao zbirka praktičnih posnih jela.\nKasnije možeš dodati pretragu po vrsti posta, sastojcima i vremenu pripreme.",
                'sections' => [
                    [
                        'title' => 'Primer jela na vodi',
                        'text' => "Posan krompir paprikaš je jednostavno i pristupačno jelo.\nPriprema se od krompira, luka, začina i vode, bez ulja i životinjskih proizvoda.",
                        'bullets' => [
                            'Pogodno za dane posta na vodi',
                            'Može se prilagoditi začinima i povrću koje imaš kod kuće',
                        ],
                    ],
                    [
                        'title' => 'Post nije samo jelovnik',
                        'text' => "U pravoslavlju post ima i duhovnu dimenziju.\nUzdržanje u hrani prati se molitvom, smirenjem i trudom na popravljanju sebe.",
                    ],
                ],
            ],

            'zanimljivosti' => [
                'title' => 'Zanimljivosti',
                'subtitle' => 'Kratke činjenice, običaji i zanimljive priče iz pravoslavnog sveta.',
                'category' => 'Zanimljivo',
                'badge' => 'Kratko',
                'intro' => "Ovde mogu ići kratke zanimljivosti, predanja, običaji i zanimljivi detalji.\nKasnije možeš dodati pretragu po temama ili nasumični prikaz sadržaja.",
                'sections' => [
                    [
                        'title' => 'Ikone',
                        'text' => "Ikone u pravoslavlju nisu običan ukras, već svedočanstvo vere.\nPoštovanje ukazane ikoni odnosi se na ličnost koja je na njoj predstavljena.",
                    ],
                    [
                        'title' => 'Zvona i bogosluženje',
                        'text' => "Zvona imaju ulogu poziva na molitvu i bogosluženje.\nNjihov zvuk u narodu često ima i simbolično značenje sabiranja i opominjanja.",
                    ],
                ],
            ],
        ];
    }
}