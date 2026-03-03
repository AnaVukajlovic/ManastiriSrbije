<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EdukacijaController extends Controller
{
    public function index()
    {
        return view('pages.pravoslavni.modules.edukacija.index');
    }

    public function show($slug)
    {
        // dozvoljeni slugovi -> fajlovi
        $map = [
            'istorija-kultura'     => 'istorija-kultura',
            'arhitektura-umetnost' => 'arhitektura-umetnost',
            'ucenje-interakcija'   => 'ucenje-interakcija',
            'porodicno-stablo'     => 'porodicno-stablo',

            // aliasi (ako negde klikneš starije)
            'interakcija'          => 'ucenje-interakcija',
            'istorija'             => 'istorija-kultura',
            'umetnost'             => 'arhitektura-umetnost',
        ];

        $viewSlug = $map[$slug] ?? null;
        if (!$viewSlug) abort(404);

        return view("pages.pravoslavni.modules.edukacija.$viewSlug");
    }

    /* ---------------- TIMELINE ---------------- */

    public function timeline()
    {
        $timelines = [
            'nemanjici' => [
                ['year' => '1166', 'title' => 'Uspon Stefana Nemanje', 'text' => 'Nemanja učvršćuje vlast i postavlja temelje države. Počinje period snažnog državnog i crkvenog organizovanja.'],
                ['year' => '1196', 'title' => 'Povlačenje i monašenje', 'text' => 'Nemanja se povlači sa vlasti i prima monaški čin. Njegova zadužbina Studenica postaje ključni duhovni centar.'],
                ['year' => '1217–1219', 'title' => 'Kruna i autokefalnost', 'text' => 'Stefan biva krunisan, a Sveti Sava dobija autokefalnost. Država i Crkva jačaju identitet i obrazovanje.'],
                ['year' => '1282–1321', 'title' => 'Vladavina kralja Milutina', 'text' => 'Velika graditeljska delatnost i diplomatski odnosi. Mnogi manastiri dobijaju novi sjaj kroz umetnost i arhitekturu.'],
                ['year' => '1331–1355', 'title' => 'Car Dušan', 'text' => 'Širenje države i snažna zakonodavna delatnost. Dušanov zakonik postaje simbol uređenja i poretka.'],
                ['year' => '1371', 'title' => 'Kraj centralne vlasti', 'text' => 'Posle Uroša nejaka slabi centralna vlast. Počinje period raslojavanja i regionalnih gospodara.'],
            ],
            'spc' => [
                ['year' => '1219', 'title' => 'Autokefalnost', 'text' => 'Sveti Sava dobija samostalnost Srpske crkve. Uspostavlja se uređen crkveni život i eparhijska mreža.'],
                ['year' => '1346', 'title' => 'Patrijaršija', 'text' => 'U vreme Cara Dušana, arhiepiskopija uzdiže se na rang patrijaršije. Jača crkveni autoritet i međunarodni položaj.'],
                ['year' => '1459–1557', 'title' => 'Teško vreme pod Turcima', 'text' => 'Crkva postaje čuvar identiteta. Manastiri čuvaju knjige, predanje i pismenost uprkos pritiscima.'],
                ['year' => '1557', 'title' => 'Obnova Pećke patrijaršije', 'text' => 'Obnova donosi stabilizaciju crkvenog života i kulturni preporod. Obnavljaju se manastiri i umetničke radionice.'],
                ['year' => '1766', 'title' => 'Ukidanje Pećke patrijaršije', 'text' => 'Crkveni život trpi nove udare. Ipak, tradicija se prenosi kroz narod i monaštvo.'],
                ['year' => '1920', 'title' => 'Ujedinjenje SPC', 'text' => 'Obnavlja se jedinstvena Srpska pravoslavna crkva u modernom obliku. Uspostavlja se savremena organizacija.'],
            ],
            'turci' => [
                ['year' => '1389', 'title' => 'Kosovski zavet', 'text' => 'Kosovska bitka ostaje snažan duhovno-istorijski simbol. Predanje oblikuje svest o identitetu i žrtvi.'],
                ['year' => '1459', 'title' => 'Pad Smedereva', 'text' => 'Nestaje srednjovekovna država. Počinje dug period osmanske vlasti i prilagođavanja.'],
                ['year' => '1594', 'title' => 'Buna i stradanja', 'text' => 'Narod pruža otpor, a posledice su često teške. Svetinje i kulturna dobra trpe oštećenja i pustošenja.'],
                ['year' => '1804', 'title' => 'Prvi srpski ustanak', 'text' => 'Počinje oslobađanje i obnova državnosti. Crkva i narod deluju zajedno u održanju zajednice.'],
                ['year' => '1815', 'title' => 'Drugi srpski ustanak', 'text' => 'Postavljaju se temelji moderne Srbije. Kulturna i crkvena obnova dobija novi zamah.'],
                ['year' => '1878', 'title' => 'Međunarodno priznanje', 'text' => 'Srbija dobija međunarodno priznanje nezavisnosti. Počinje period institucionalnog jačanja.'],
            ],
        ];

        return view('pages.pravoslavni.modules.edukacija.timeline', compact('timelines'));
    }

    /* ---------------- KVIZ: ISTORIJA ---------------- */

    public function quizHistory()
    {
        $questions = $this->historyQuestions();
        return view('pages.pravoslavni.modules.edukacija.quiz-history', [
            'questions' => $questions,
            'result' => null,
        ]);
    }

    public function quizHistorySubmit(Request $request)
    {
        $questions = $this->historyQuestions();

        $answers = (array) $request->input('answers', []);
        $score = 0;
        $max = count($questions);

        foreach ($questions as $q) {
            $picked = $answers[$q['id']] ?? null;
            if ($picked !== null && (string)$picked === (string)$q['correct']) {
                $score++;
            }
        }

        $result = [
            'score' => $score,
            'max' => $max,
            'percent' => $max ? round(($score / $max) * 100) : 0,
        ];

        return view('pages.pravoslavni.modules.edukacija.quiz-history', [
            'questions' => $questions,
            'result' => $result,
            'answers' => $answers,
        ]);
    }

    private function historyQuestions(): array
    {
        return [
            [
                'id' => 'h1',
                'q' => 'Koji događaj se vezuje za 1219. godinu u istoriji SPC?',
                'options' => [
                    'Ujedinjenje SPC u modernom obliku',
                    'Dobijanje autokefalnosti od strane Svetog Save',
                    'Ukidanje Pećke patrijaršije',
                    'Pad Smedereva',
                ],
                'correct' => 1,
                'explain' => '1219. je godina autokefalnosti – Sveti Sava postavlja temelje samostalne crkvene organizacije.',
            ],
            [
                'id' => 'h2',
                'q' => 'Koja dinastija je obeležila zlatno doba srednjovekovne Srbije?',
                'options' => ['Obrenovići', 'Nemanjići', 'Karađorđevići', 'Brankovići'],
                'correct' => 1,
                'explain' => 'Nemanjići su utemeljili državnost, zadužbine i veliki kulturni razvoj.',
            ],
            [
                'id' => 'h3',
                'q' => 'Koji vladar je poznat po Dušanovom zakoniku i tituli cara?',
                'options' => ['Stefan Dečanski', 'Car Dušan', 'Stefan Prvovenčani', 'Uroš Nejaki'],
                'correct' => 1,
                'explain' => 'Car Dušan je doneo Zakonik i proširio državu.',
            ],
            [
                'id' => 'h4',
                'q' => 'Pad Smedereva (1459) označava:',
                'options' => ['Kraj osmanske vlasti', 'Početak autokefalnosti', 'Nestanak srednjovekovne države', 'Uvođenje Zakonika'],
                'correct' => 2,
                'explain' => '1459. se uzima kao kraj srednjovekovne državnosti i potpuni ulazak u period osmanske vlasti.',
            ],
            [
                'id' => 'h5',
                'q' => 'Šta je bila posebna uloga manastira tokom osmanske vlasti?',
                'options' => ['Centri fabrike oružja', 'Isključivo vojni logori', 'Čuvanje pismenosti i identiteta', 'Pomorske luke'],
                'correct' => 2,
                'explain' => 'Manastiri čuvaju knjige, predanje, školstvo i identitet naroda.',
            ],
        ];
    }

    /* ---------------- KVIZ: PRAVOSLAVLJE ---------------- */

    public function quizOrthodox()
    {
        $questions = $this->orthodoxQuestions();
        return view('pages.pravoslavni.modules.edukacija.quiz-orthodox', [
            'questions' => $questions,
            'result' => null,
        ]);
    }

    public function quizOrthodoxSubmit(Request $request)
    {
        $questions = $this->orthodoxQuestions();

        $answers = (array) $request->input('answers', []);
        $score = 0;
        $max = count($questions);

        foreach ($questions as $q) {
            $picked = $answers[$q['id']] ?? null;
            if ($picked !== null && (string)$picked === (string)$q['correct']) {
                $score++;
            }
        }

        $result = [
            'score' => $score,
            'max' => $max,
            'percent' => $max ? round(($score / $max) * 100) : 0,
        ];

        return view('pages.pravoslavni.modules.edukacija.quiz-orthodox', [
            'questions' => $questions,
            'result' => $result,
            'answers' => $answers,
        ]);
    }

    private function orthodoxQuestions(): array
    {
        return [
            [
                'id' => 'o1',
                'q' => 'Šta znači “Vaskrs” u hrišćanskom kontekstu?',
                'options' => [
                    'Rođenje Hrista',
                    'Vaskrsenje Hrista',
                    'Krštenje Hrista',
                    'Vaznesenje Presvete Bogorodice',
                ],
                'correct' => 1,
                'explain' => 'Vaskrs je praznik Vaskrsenja Gospoda Isusa Hrista.',
            ],
            [
                'id' => 'o2',
                'q' => 'Šta je “liturgija” najjednostavnije rečeno?',
                'options' => [
                    'Samo crkvena pesma',
                    'Zajedničko bogosluženje i Evharistija',
                    'Samo privatna molitva',
                    'Post bez hrane',
                ],
                'correct' => 1,
                'explain' => 'Liturgija je centralno bogosluženje Crkve povezano sa Evharistijom.',
            ],
            [
                'id' => 'o3',
                'q' => 'Zašto se farbaju jaja za Vaskrs?',
                'options' => [
                    'Samo zbog ukrasa',
                    'Kao simbol novog života i radosti Vaskrsenja',
                    'Kao obavezni poklon bez značenja',
                    'Zbog starog rimskog običaja bez veze sa verom',
                ],
                'correct' => 1,
                'explain' => 'Jaje simbolizuje život i obnovu – radost Vaskrsenja.',
            ],
            [
                'id' => 'o4',
                'q' => 'Šta znači “autokefalnost” crkve?',
                'options' => [
                    'Potpuna zabrana bogosluženja',
                    'Samostalnost u upravljanju crkvenim životom',
                    'Obavezno postavljanje ikona u svakoj kući',
                    'Jedan isti praznik svake nedelje',
                ],
                'correct' => 1,
                'explain' => 'Autokefalnost znači samostalnost crkve u organizaciji i upravljanju.',
            ],
            [
                'id' => 'o5',
                'q' => 'Koji je tradicionalni praznični pozdrav za Vaskrs?',
                'options' => [
                    'Mir Božiji',
                    'Hristos vaskrse — Vaistinu vaskrse',
                    'Srećna slava',
                    'Pomoz’ Bog',
                ],
                'correct' => 1,
                'explain' => 'To je ispovedni pozdrav radosti Vaskrsenja.',
            ],
        ];
    }

    /* ---------------- AI ---------------- */

    public function ai()
    {
        return view('pages.pravoslavni.modules.edukacija.ai');
    }

public function aiChat(Request $request)
{
    $prompt = trim((string) $request->input('message', ''));
    if ($prompt === '') {
        return response()->json(['ok' => false, 'error' => 'Tekst/tema je prazna.'], 422);
    }

    // režimi iz UI (ai.blade.php)
    $mode = (string) $request->input('mode', 'summarize');     // summarize | explain | glossary | quiz
    $level = (string) $request->input('level', 'B1');         // A2 | B1 | B2
    $length = (string) $request->input('length', 'medium');   // short | medium | long
    $instruction = trim((string) $request->input('instruction', ''));

    $host  = rtrim(env('OLLAMA_HOST', 'http://127.0.0.1:11434'), '/');
    $model = env('OLLAMA_MODEL', 'qwen2.5:7b');

    // Stabilan “system” za alat režime: dozvoljava opšte znanje, ali bez izmišljanja izvora/citata
    $system =
        "Ti si edukativni asistent (AI radionica) u aplikaciji Pravoslavni Svetionik.\n" .
        "Odgovaraj na srpskom, jasno, smireno i korisno.\n" .
        "Možeš koristiti opšte znanje o: Nemanjićima, istoriji SPC, srpskoj kulturi, arhitekturi manastira i pravoslavlju.\n" .
        "Ne izmišljaj izvore, citate i tačne bibliografske reference. Ako korisnik traži precizan citat ili broj koji ne možeš pouzdano da potvrdiš, naglasi nesigurnost.\n" .
        "Ne ponavljaj pitanje korisnika. Piši pregledno, sa naslovima i listama kada ima smisla.\n" .
        "Ako korisnik zalepi tekst, radi isključivo na osnovu tog teksta + opšteg konteksta, bez 'nemam podataka' fraza.\n";

    // User payload koji objašnjava “šta tačno da uradi”
    $user =
        "REŽIM: {$mode}\n" .
        "NIVO: {$level}\n" .
        "DUŽINA: {$length}\n\n" .
        ($instruction ? "UPUTSTVO:\n{$instruction}\n\n" : "") .
        "ULAZ (tema/tekst):\n{$prompt}\n\n" .
        "Molim te da rezultat formatiraš lepo (naslovi + tačke) i da bude praktičan za učenje.";

    try {
        $res = Http::timeout(120)->post("$host/api/chat", [
            'model' => $model,
            'stream' => false,
            'messages' => [
                ['role' => 'system', 'content' => $system],
                ['role' => 'user', 'content' => $user],
            ],
            // opcionalno (neke verzije ignorišu): drži odgovor stabilnijim
            'options' => [
                'temperature' => 0.4,
                'top_p' => 0.9,
            ],
        ]);

        if (!$res->ok()) {
            return response()->json([
                'ok' => false,
                'error' => 'Ollama odgovor nije OK.',
                'status' => $res->status(),
                'body' => $res->body(),
                'hint' => 'Proveri da li model postoji (ollama list) i da li je OLLAMA_HOST tačan.',
            ], 500);
        }

        $data = $res->json();
        $answer = (string) (data_get($data, 'message.content') ?? '');

        // fallback ako se format razlikuje
        if ($answer === '') {
            $answer = (string) (data_get($data, 'response') ?? '');
        }

        return response()->json([
            'ok' => true,
            'answer' => $answer !== '' ? $answer : '(Nema odgovora)',
            'model' => $model,
            'meta' => [
                'mode' => $mode,
                'level' => $level,
                'length' => $length,
            ],
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'ok' => false,
            'error' => 'Ne mogu da kontaktiram Ollama konekciju.',
            'details' => $e->getMessage(),
        ], 500);
    }
}
}