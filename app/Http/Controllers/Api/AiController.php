<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ktitor;
use App\Models\Monastery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AiController extends Controller
{
    public function chat(Request $request)
    {
        @set_time_limit(120);

        $question = $this->cleanText((string) (
            $request->input('question')
            ?? $request->input('instruction')
            ?? $request->input('tema')
            ?? $request->input('topic')
            ?? $request->input('message')
            ?? ''
        ));

        $userContext = $this->cleanText((string) (
            $request->input('context')
            ?? $request->input('text')
            ?? ''
        ));

        $mode = trim((string) $request->input('mode', ''));

        if ($question === '') {
            return response()->json([
                'ok' => false,
                'error' => 'Pitanje ili instrukcija je prazna.',
                'answer' => '',
                'reply' => '',
            ], 422);
        }

        $maxTokens = (int) $request->input('max_tokens', 160);
        $maxTokens = max(80, min($maxTokens, 220));

        $dbContext = $this->buildDatabaseContext($question);
        $finalContext = $dbContext !== '' ? $dbContext : $userContext;

        if ($mode === 'timeline_explain') {
            $answer = $this->buildTimelineExplanation($finalContext);
            $answer = $this->normalizeAnswer($answer);

            return response()->json([
                'ok' => true,
                'answer' => $answer,
                'reply' => $answer,
                'meta' => [
                    'used_db_context' => false,
                    'generated_locally' => true,
                    'used_ollama_refinement' => false,
                    'mode' => 'timeline_explain',
                ],
            ]);
        }

        $useOllama = filter_var(env('AI_USE_OLLAMA', false), FILTER_VALIDATE_BOOL);

        if ($useOllama) {
            $baseUrl = rtrim((string) env('OLLAMA_URL', 'http://127.0.0.1:11434'), '/');
            $model = (string) env('OLLAMA_MODEL', 'qwen2.5:3b');
            $system = $this->buildSystemPrompt($finalContext !== '', $mode);

            try {
                $answer = $this->callOllama(
                    $baseUrl,
                    $model,
                    $system,
                    $question,
                    $finalContext,
                    $maxTokens
                );

                $answer = $this->normalizeAnswer($answer);

                if ($answer !== '') {
                    return response()->json([
                        'ok' => true,
                        'answer' => $answer,
                        'reply' => $answer,
                        'meta' => [
                            'used_db_context' => $dbContext !== '',
                            'generated_locally' => false,
                            'used_ollama' => true,
                        ],
                    ]);
                }
            } catch (\Throwable $e) {
                Log::warning('Ollama nedostupna, prelazim na lokalni odgovor', [
                    'msg' => $e->getMessage(),
                ]);
            }
        }

        $answer = $this->buildLocalAnswer($question, $dbContext, $userContext, $mode);
        $answer = $this->normalizeAnswer($answer);

        return response()->json([
            'ok' => true,
            'answer' => $answer,
            'reply' => $answer,
            'meta' => [
                'used_db_context' => $dbContext !== '',
                'generated_locally' => true,
                'used_ollama' => false,
            ],
        ]);
    }

    private function buildSystemPrompt(bool $hasContext, string $mode = ''): string
    {
        $contextRule = $hasContext
            ? '- Koristi isključivo informacije iz dostavljenog konteksta. Ako podatak nije u kontekstu, reci tačno: "Nemam dovoljno pouzdanih podataka u dostavljenom kontekstu."'
            : '- Ako nema dostavljenog konteksta i pitanje traži tačne podatke o konkretnom događaju, manastiru, ktitoru, datumu ili mestu, nemoj nagađati. Reci tačno: "Nemam dovoljno pouzdanih podataka u dostavljenom kontekstu."';

        if ($mode === 'timeline_explain') {
            return <<<SYS
Ti si pouzdan i veoma jasan AI asistent za aplikaciju "Pravoslavni Svetionik".

OBAVEZNA PRAVILA:
- Odgovaraj isključivo na standardnom srpskom jeziku.
- Koristi ekavicu i latinicu.
- Piši prirodno, jasno i onako kako se normalno govori i piše na srpskom jeziku.
- Koristi isključivo ekavske oblike.
- Ne mešaj jezike.
- Ne koristi čudne, neprirodne ili rogobatne izraze.
- Ne izmišljaj činjenice.
- Ne dopisuj ono čega nema u kontekstu.
{$contextRule}
- Piši najviše 3 kratke i povezane rečenice.
- Ako kontekst nije dovoljan, reci samo: "Nemam dovoljno pouzdanih podataka u dostavljenom kontekstu."
SYS;
        }

        return <<<SYS
Ti si pouzdan i sažet AI asistent za aplikaciju "Pravoslavni Svetionik".

OBAVEZNA PRAVILA:
- Odgovaraj isključivo na standardnom srpskom jeziku.
- Koristi ekavicu i latinicu.
- Ne mešaj jezike.
- Piši prirodno, jasno i gramatički ispravno.
- Ne izmišljaj činjenice, datume, titule ni događaje.
- Ne nagađaj.
{$contextRule}
- Odgovor napiši kao jedan kratak pasus ili najviše dva kratka povezana pasusa.
- Ne koristi liste osim ako korisnik to izričito traži.
SYS;
    }

    private function buildLocalAnswer(string $question, string $dbContext, string $userContext = '', string $mode = ''): string
    {
        if ($mode === 'timeline_explain') {
            return $this->buildTimelineExplanation($dbContext !== '' ? $dbContext : $userContext);
        }

        $normalized = Str::lower($this->cleanText($question));

        $monasteries = $this->findRelevantMonasteries($question);
        $ktitors = $this->findRelevantKtitors($question);

        if ($monasteries->isNotEmpty()) {
            $m = $monasteries->first();
            return $this->buildMonasteryAnswer($m, $normalized);
        }

        if ($ktitors->isNotEmpty()) {
            $k = $ktitors->first();
            return $this->buildKtitorAnswer($k, $normalized);
        }

        if ($dbContext !== '') {
            return $this->buildGenericContextAnswer($dbContext);
        }

        if ($userContext !== '') {
            return 'Na osnovu dostavljenog konteksta mogu da kažem sledeće: ' . $this->limitText($userContext, 350);
        }

        return 'Trenutno nemam dovoljno pouzdanih podataka za to pitanje u bazi aplikacije. Pokušaj da pitaš konkretnije, na primer o određenom manastiru, ktitoru ili temi iz edukacije.';
    }

    private function buildMonasteryAnswer(Monastery $m, string $question): string
    {
        $name = $m->name ?: 'Ovaj manastir';
        $pieces = [];

        if (
            str_contains($question, 'gde') ||
            str_contains($question, 'nalazi') ||
            str_contains($question, 'lokacij')
        ) {
            $loc = [];
            if (!empty($m->city)) $loc[] = $m->city;
            if (!empty($m->region)) $loc[] = $m->region;
            if (!empty($m->eparchy?->name)) $loc[] = 'u okviru ' . $m->eparchy->name;

            if (!empty($loc)) {
                return $name . ' se nalazi ' . implode(', ', $loc) . '.';
            }
        }

        if (str_contains($question, 'istorij')) {
            if (!empty($m->history)) {
                return $name . ': ' . $this->limitText($m->history, 420);
            }
        }

        if (str_contains($question, 'arhitektur')) {
            if (!empty($m->architecture)) {
                return $name . ': ' . $this->limitText($m->architecture, 360);
            }
        }

        if (str_contains($question, 'umetnost') || str_contains($question, 'fresk') || str_contains($question, 'slikar')) {
            if (!empty($m->art)) {
                return $name . ': ' . $this->limitText($m->art, 360);
            }
        }

        if (str_contains($question, 'duhovn') || str_contains($question, 'život')) {
            if (!empty($m->spiritual_life)) {
                return $name . ': ' . $this->limitText($m->spiritual_life, 360);
            }
        }

        if (str_contains($question, 'poset') || str_contains($question, 'obilazak') || str_contains($question, 'radno vreme')) {
            if (!empty($m->visiting)) {
                return $name . ': ' . $this->limitText($m->visiting, 300);
            }
        }

        if (!empty($m->excerpt)) {
            $pieces[] = $this->limitText($m->excerpt, 220);
        } elseif (!empty($m->description)) {
            $pieces[] = $this->limitText($m->description, 260);
        }

        if (!empty($m->history)) {
            $pieces[] = 'Iz dostupnih podataka može se izdvojiti i sledeće iz njegove istorije: ' . $this->limitText($m->history, 180);
        }

        if (empty($pieces)) {
            return 'Za manastir ' . $name . ' trenutno nemam dovoljno detaljnih podataka u bazi aplikacije.';
        }

        return implode(' ', $pieces);
    }

    private function buildKtitorAnswer(Ktitor $k, string $question): string
    {
        $name = $k->name ?: 'Ovaj ktitor';

        if (str_contains($question, 'ko je') || str_contains($question, 'ktitor')) {
            $parts = [$name];
            if (!empty($k->bio)) {
                return $name . ' je ličnost o kojoj baza sadrži sledeće podatke: ' . $this->limitText($k->bio, 380);
            }
            return $name . ' je evidentiran u bazi aplikacije kao ktitor.';
        }

        if (str_contains($question, 'kada je rođen') || str_contains($question, 'rođen')) {
            if (!empty($k->born_year)) {
                return $name . ' je, prema podacima u bazi, rođen ' . $k->born_year . '. godine.';
            }
        }

        if (str_contains($question, 'kada je umro') || str_contains($question, 'smrt') || str_contains($question, 'umro')) {
            if (!empty($k->died_year)) {
                return $name . ' je, prema podacima u bazi, preminuo ' . $k->died_year . '. godine.';
            }
        }

        if (!empty($k->bio)) {
            $answer = $name . ': ' . $this->limitText($k->bio, 420);

            if (!empty($k->born_year) || !empty($k->died_year)) {
                $range = [];
                if (!empty($k->born_year)) $range[] = 'rođen ' . $k->born_year . '.';
                if (!empty($k->died_year)) $range[] = 'preminuo ' . $k->died_year . '.';
                $answer .= ' ' . ucfirst(implode(' ', $range));
            }

            return $answer;
        }

        return 'Za ktitora ' . $name . ' trenutno nemam dovoljno detaljnih podataka u bazi aplikacije.';
    }

    private function buildGenericContextAnswer(string $context): string
    {
        $lines = preg_split("/\r\n|\r|\n/", $context);
        $lines = array_values(array_filter(array_map(fn ($line) => trim($line), $lines)));

        if (empty($lines)) {
            return 'Nemam dovoljno pouzdanih podataka u dostavljenom kontekstu.';
        }

        $useful = [];
        foreach ($lines as $line) {
            if (
                !Str::startsWith($line, 'MANASTIR') &&
                !Str::startsWith($line, 'KTITOR')
            ) {
                $useful[] = $line;
            }
            if (count($useful) >= 4) {
                break;
            }
        }

        if (empty($useful)) {
            return 'Pronašla sam relevantne podatke u bazi, ali pitanje traži preciznije usmerenje. Pokušaj da pitaš konkretnije.';
        }

        return implode(' ', $useful);
    }

    private function buildDatabaseContext(string $question): string
    {
        $parts = [];

        $monasteries = $this->findRelevantMonasteries($question);
        foreach ($monasteries as $monastery) {
            $parts[] = $this->formatMonasteryContext($monastery);
        }

        $ktitors = $this->findRelevantKtitors($question);
        foreach ($ktitors as $ktitor) {
            $parts[] = $this->formatKtitorContext($ktitor);
        }

        return trim(implode("\n\n", array_filter($parts)));
    }

    private function buildTimelineExplanation(string $context): string
    {
        $parsed = $this->parseTimelineContext($context);

        $year = $parsed['year'] ?? '';
        $title = $parsed['title'] ?? '';
        $summary = $parsed['summary'] ?? '';
        $area = $parsed['area'] ?? '';
        $extra = $parsed['extra'] ?? '';

        if ($title === '' && $summary === '') {
            return 'Nemam dovoljno pouzdanih podataka u dostavljenom kontekstu.';
        }

        $sentences = [];

        $intro = $this->buildTimelineIntroSentence($title, $year, $summary, $extra);
        if ($intro !== '') {
            $sentences[] = $intro;
        }

        $normalizedSummary = $this->normalizeTimelineSentence($summary);
        if ($normalizedSummary !== '' && !$this->isSameMeaningAsIntro($intro, $normalizedSummary)) {
            $sentences[] = $normalizedSummary;
        }

        $normalizedExtra = $this->normalizeTimelineSentence($extra);
        if (
            $normalizedExtra !== '' &&
            $normalizedExtra !== $normalizedSummary &&
            !$this->isSameMeaningAsIntro($intro, $normalizedExtra)
        ) {
            $sentences[] = $normalizedExtra;
        }

        $importance = $this->buildTimelineImportanceSentence($title, $summary, $extra, $area);
        if ($importance !== '') {
            $sentences[] = $importance;
        }

        $sentences = array_values(array_filter(array_map(
            fn ($s) => $this->cleanTimelineSentence($s),
            $sentences
        )));

        $sentences = array_values(array_unique($sentences));

        if (count($sentences) === 1) {
            $fallback = $this->buildTimelineFallbackSentence($title, $area);
            if ($fallback !== '') {
                $sentences[] = $fallback;
            }
        }

        $sentences = array_slice($sentences, 0, 4);

        if (empty($sentences)) {
            return 'Nemam dovoljno pouzdanih podataka u dostavljenom kontekstu.';
        }

        return implode(' ', $sentences);
    }

    private function buildTimelineFallbackSentence(string $title, string $area): string
    {
        $source = Str::lower($title . ' ' . $area);

        if (str_contains($source, 'nemanji')) {
            return 'Ovaj događaj ima posebno mesto u istoriji dinastije Nemanjić i razvoju srednjovekovne Srbije.';
        }

        if (str_contains($source, 'spc') || str_contains($source, 'crk')) {
            return 'Značaj ovog događaja ogleda se u razvoju Srpske pravoslavne crkve i njenog mesta u narodnom životu.';
        }

        if (str_contains($source, 'tur')) {
            return 'Ovaj događaj je važan za razumevanje položaja Srbije u periodu osmanske vlasti.';
        }

        return 'Ovaj događaj imao je važnu ulogu u istorijskom razvoju Srbije.';
    }

    private function buildTimelineIntroSentence(
        string $title,
        string $year,
        string $summary,
        string $extra = ''
    ): string {
        $title = $this->cleanText($title);
        $summary = $this->cleanText($summary);
        $extra = $this->cleanText($extra);

        if ($title === '') {
            return '';
        }

        $titleLower = Str::lower($title);
        $summaryLower = Str::lower($summary . ' ' . $extra);

        if (str_contains($titleLower, 'rođenje')) {
            $subject = trim(preg_replace('/^rođenje\s+/iu', '', $title));

            if ($subject !== '') {
                if (str_contains($summaryLower, 'rodonačelnik')) {
                    return ($year !== '' ? 'Oko ' . trim($year) . '. godine ' : '')
                        . $subject
                        . ' je rođen i smatra se rodonačelnikom dinastije Nemanjić.';
                }

                return ($year !== '' ? 'Oko ' . trim($year) . '. godine ' : '')
                    . $subject
                    . ' je rođen.';
            }

            return ($year !== '' ? 'Oko ' . trim($year) . '. godine ' : '') . $title . '.';
        }

        if (str_contains($titleLower, 'smrt')) {
            $subject = trim(preg_replace('/^smrt\s+/iu', '', $title));

            if ($subject !== '') {
                return ($year !== '' ? trim($year) . '. godine ' : '') . 'umro je ' . $subject . '.';
            }

            return ($year !== '' ? trim($year) . '. godine ' : '') . $title . '.';
        }

        if (str_contains($titleLower, 'početak vladavine')) {
            return ($year !== '' ? trim($year) . '. godine ' : '')
                . 'počinje vladavina '
                . trim(preg_replace('/^početak vladavine\s+/iu', '', $title))
                . '.';
        }

        if (
            str_contains($titleLower, 'uspon') ||
            str_contains($titleLower, 'dolazak') ||
            str_contains($titleLower, 'krunisanje') ||
            str_contains($titleLower, 'autokefalnost') ||
            str_contains($titleLower, 'osnivanje')
        ) {
            return ($year !== '' ? trim($year) . '. godine ' : '') . $title . '.';
        }

        if ($year !== '') {
            return trim($year) . '. godine ' . $title . '.';
        }

        return $title . '.';
    }

    private function parseTimelineContext(string $context): array
    {
        $result = [
            'area' => '',
            'year' => '',
            'title' => '',
            'summary' => '',
            'extra' => '',
        ];

        $lines = preg_split("/\r\n|\r|\n/", $context);

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '') {
                continue;
            }

            if (Str::startsWith($line, 'OBLAST:')) {
                $result['area'] = trim(Str::after($line, 'OBLAST:'));
                continue;
            }

            if (Str::startsWith($line, 'GODINA:')) {
                $result['year'] = trim(Str::after($line, 'GODINA:'));
                continue;
            }

            if (Str::startsWith($line, 'DOGAĐAJ:')) {
                $result['title'] = trim(Str::after($line, 'DOGAĐAJ:'));
                continue;
            }

            if (Str::startsWith($line, 'KRATAK OPIS:')) {
                $result['summary'] = trim(Str::after($line, 'KRATAK OPIS:'));
                continue;
            }

            if (Str::startsWith($line, 'DODATNI KONTEKST:')) {
                $result['extra'] = trim(Str::after($line, 'DODATNI KONTEKST:'));
                continue;
            }
        }

        return $result;
    }

    private function normalizeTimelineSentence(string $text): string
    {
        $text = $this->cleanText($text);
        $text = $this->normalizeEkavianSerbian($text);
        $text = preg_replace('/\s+([,.;:!?])/u', '$1', $text);
        $text = trim($text);

        if ($text === '') {
            return '';
        }

        $text = mb_strtoupper(mb_substr($text, 0, 1)) . mb_substr($text, 1);

        if (!preg_match('/[.!?]$/u', $text)) {
            $text .= '.';
        }

        return $text;
    }

    private function buildTimelineImportanceSentence(
        string $title,
        string $summary,
        string $extra,
        string $area
    ): string {
        $source = Str::lower($title . ' ' . $summary . ' ' . $extra . ' ' . $area);

        if (str_contains($source, 'krunis')) {
            return 'Značaj ovog događaja bio je u jačanju ugleda i državnog položaja Srbije.';
        }

        if (str_contains($source, 'autokefal')) {
            return 'Ovaj događaj bio je važan jer je učvrstio crkvenu samostalnost i položaj srpske države.';
        }

        if (str_contains($source, 'hilandar')) {
            return 'Značaj događaja ogleda se u jačanju duhovnog i kulturnog života srpskog naroda.';
        }

        if (
            str_contains($source, 'vladavin') ||
            str_contains($source, 'dolazak') ||
            str_contains($source, 'uspon') ||
            str_contains($source, 'preuzimanje vlasti')
        ) {
            return 'Ovaj događaj bio je važan jer je označio novu fazu u političkom razvoju države.';
        }

        if (str_contains($source, 'ugovor') || str_contains($source, 'mir')) {
            return 'Značaj događaja bio je u jačanju političkih odnosa i stabilnosti.';
        }

        if (str_contains($source, 'smrt')) {
            return 'Ovaj događaj označio je kraj jednog važnog perioda i početak novih promena.';
        }

        return 'Ovaj događaj imao je značajno mesto u istorijskom razvoju Srbije.';
    }

    private function cleanTimelineSentence(string $text): string
    {
        $text = $this->cleanText($text);
        $text = $this->normalizeEkavianSerbian($text);

        $badFragments = [
            'se odnosilo na godinu',
            'odgovara ',
            'predstavnik najvažnijeg',
            'oduvek toga vremena',
            'ključne poznate mjesto',
            'izazvao kao važan trenutak',
            'policijske institucije',
            'sveta praga',
            'stvarne političke odn',
        ];

        foreach ($badFragments as $fragment) {
            if (Str::contains(Str::lower($text), Str::lower($fragment))) {
                return '';
            }
        }

        $text = preg_replace('/\s+([,.;:!?])/u', '$1', $text);
        $text = trim($text);

        return $text;
    }

    private function isSameMeaningAsIntro(string $intro, string $summary): bool
    {
        $intro = Str::lower($this->cleanText($intro));
        $summary = Str::lower($this->cleanText($summary));

        if ($intro === '' || $summary === '') {
            return false;
        }

        $pairs = [
            ['rođen', 'rodo'],
            ['rođen', 'rođen'],
            ['vladavin', 'vladavin'],
            ['uspon', 'uspostavlja vlast'],
            ['krunis', 'krunisan'],
            ['autokefal', 'crkvenu samostalnost'],
            ['osnivanje', 'osnivanj'],
            ['smrt', 'umro'],
        ];

        foreach ($pairs as [$a, $b]) {
            if (str_contains($intro, $a) && str_contains($summary, $b)) {
                return true;
            }
        }

        return false;
    }

    private function findRelevantMonasteries(string $question)
    {
        $normalizedQuestion = Str::lower($question);

        return Monastery::with(['profile', 'eparchy'])
            ->select([
                'id',
                'name',
                'slug',
                'region',
                'city',
                'excerpt',
                'description',
                'history',
                'architecture',
                'art',
                'spiritual_life',
                'visiting',
                'sources',
                'latitude',
                'longitude',
            ])
            ->where(function ($q) {
                $q->where('is_approved', true)
                    ->orWhereNull('is_approved');
            })
            ->get()
            ->filter(function ($monastery) use ($normalizedQuestion) {
                $name = Str::lower((string) $monastery->name);
                $slug = Str::lower((string) $monastery->slug);

                return (
                    ($name !== '' && str_contains($normalizedQuestion, $name))
                    || ($slug !== '' && str_contains($normalizedQuestion, $slug))
                );
            })
            ->take(2)
            ->values();
    }

    private function findRelevantKtitors(string $question)
    {
        $normalizedQuestion = Str::lower($question);

        return Ktitor::query()
            ->select([
                'id',
                'name',
                'slug',
                'born_year',
                'died_year',
                'bio',
            ])
            ->get()
            ->filter(function ($ktitor) use ($normalizedQuestion) {
                $name = Str::lower((string) $ktitor->name);
                $slug = Str::lower((string) $ktitor->slug);

                return (
                    ($name !== '' && str_contains($normalizedQuestion, $name))
                    || ($slug !== '' && str_contains($normalizedQuestion, $slug))
                );
            })
            ->take(2)
            ->values();
    }

    private function formatMonasteryContext(Monastery $monastery): string
    {
        $lines = [];
        $lines[] = 'MANASTIR';
        $lines[] = 'Naziv: ' . $monastery->name;

        if (!empty($monastery->eparchy?->name)) {
            $lines[] = 'Eparhija: ' . $monastery->eparchy->name;
        }

        if (!empty($monastery->region)) {
            $lines[] = 'Region: ' . $monastery->region;
        }

        if (!empty($monastery->city)) {
            $lines[] = 'Grad/Mesto: ' . $monastery->city;
        }

        if (!empty($monastery->excerpt)) {
            $lines[] = 'Sažetak: ' . $this->limitText($monastery->excerpt, 300);
        }

        if (!empty($monastery->description)) {
            $lines[] = 'Opis: ' . $this->limitText($monastery->description, 500);
        }

        if (!empty($monastery->history)) {
            $lines[] = 'Istorija: ' . $this->limitText($monastery->history, 500);
        }

        if (!empty($monastery->architecture)) {
            $lines[] = 'Arhitektura: ' . $this->limitText($monastery->architecture, 350);
        }

        if (!empty($monastery->art)) {
            $lines[] = 'Umetnost: ' . $this->limitText($monastery->art, 300);
        }

        if (!empty($monastery->spiritual_life)) {
            $lines[] = 'Duhovni život: ' . $this->limitText($monastery->spiritual_life, 300);
        }

        if (!empty($monastery->visiting)) {
            $lines[] = 'Poseta: ' . $this->limitText($monastery->visiting, 250);
        }

        if (!empty($monastery->profile?->short_description)) {
            $lines[] = 'Profil: ' . $this->limitText($monastery->profile->short_description, 250);
        }

        return implode("\n", $lines);
    }

    private function formatKtitorContext(Ktitor $ktitor): string
    {
        $lines = [];
        $lines[] = 'KTITOR';
        $lines[] = 'Ime: ' . $ktitor->name;

        if (!empty($ktitor->born_year)) {
            $lines[] = 'Godina rođenja: ' . $ktitor->born_year;
        }

        if (!empty($ktitor->died_year)) {
            $lines[] = 'Godina smrti: ' . $ktitor->died_year;
        }

        if (!empty($ktitor->bio)) {
            $lines[] = 'Biografija: ' . $this->limitText($ktitor->bio, 500);
        }

        return implode("\n", $lines);
    }

    private function callOllama(
        string $baseUrl,
        string $model,
        string $system,
        string $question,
        string $context,
        int $maxTokens
    ): string {
        $messages = [
            [
                'role' => 'system',
                'content' => $system,
            ],
        ];

        if ($context !== '') {
            $messages[] = [
                'role' => 'system',
                'content' => "KONTEKST:\n" . $context,
            ];
        }

        $messages[] = [
            'role' => 'user',
            'content' => $question,
        ];

        $payload = [
            'model' => $model,
            'messages' => $messages,
            'stream' => false,
            'options' => [
                'temperature' => 0.05,
                'top_p' => 0.55,
                'num_predict' => $maxTokens,
                'num_ctx' => 2048,
                'repeat_penalty' => 1.20,
            ],
        ];

        $url = $baseUrl . '/api/chat';

        $res = Http::connectTimeout(20)
            ->timeout(90)
            ->acceptJson()
            ->asJson()
            ->post($url, $payload);

        if (!$res->successful()) {
            Log::error('Ollama failed', [
                'url' => $url,
                'status' => $res->status(),
                'body' => $res->body(),
            ]);

            throw new \RuntimeException('Ollama greška: HTTP ' . $res->status());
        }

        $data = $res->json();

        return trim((string) data_get($data, 'message.content', ''));
    }

    private function limitText(?string $text, int $limit = 300): string
    {
        $text = $this->cleanText((string) $text);

        return Str::limit($text, $limit, '...');
    }

    private function cleanText(string $text): string
    {
        $text = preg_replace("/\r\n|\r/", "\n", $text);
        $text = preg_replace('/[ \t]+/', ' ', $text);
        $text = preg_replace("/\n{3,}/", "\n\n", $text);

        return trim((string) $text);
    }

    private function normalizeAnswer(string $text): string
    {
        $text = $this->cleanText($text);
        $text = preg_replace('/^["„“]+|["„“]+$/u', '', $text);
        $text = $this->normalizeEkavianSerbian($text);
        $text = preg_replace('/\s+([,.;:!?])/u', '$1', $text);

        return trim((string) $text);
    }

    private function normalizeEkavianSerbian(string $text): string
    {
        $replacements = [
            '/\bovdje\b/u' => 'ovde',
            '/\bgdje\b/u' => 'gde',
            '/\buvijek\b/u' => 'uvek',
            '/\boduvijek\b/u' => 'oduvek',
            '/\btijekom\b/u' => 'tokom',
            '/\buvjet\b/u' => 'uslov',
            '/\buvjeti\b/u' => 'uslovi',
            '/\bsljedeći\b/u' => 'sledeći',
            '/\bsljedece\b/u' => 'sledeće',
            '/\bsljedećeg\b/u' => 'sledećeg',
            '/\bsljedećim\b/u' => 'sledećim',
            '/\bsljedeća\b/u' => 'sledeća',
            '/\bsljedeće\b/u' => 'sledeće',
            '/\buvjerljivo\b/u' => 'uverljivo',
            '/\bnaprimjer\b/u' => 'na primer',
            '/\bprimjer\b/u' => 'primer',
            '/\bprimjera\b/u' => 'primera',
            '/\bprimjeri\b/u' => 'primeri',
            '/\bvrijeme\b/u' => 'vreme',
            '/\bvrijednost\b/u' => 'vrednost',
            '/\bvrijednosti\b/u' => 'vrednosti',
            '/\bupotrebljava\b/u' => 'koristi',
            '/\btočka\b/u' => 'tačka',
            '/\btočke\b/u' => 'tačke',
            '/\btisuća\b/u' => 'hiljada',
            '/\bpovijest\b/u' => 'istorija',
            '/\bpovijesni\b/u' => 'istorijski',
            '/\bpovijesnog\b/u' => 'istorijskog',
            '/\bpovijesna\b/u' => 'istorijska',
            '/\bpovijesne\b/u' => 'istorijske',
            '/\bsvećenik\b/u' => 'sveštenik',
            '/\bsvećenika\b/u' => 'sveštenika',
            '/\bsvećenici\b/u' => 'sveštenici',
            '/\bposljedice\b/u' => 'posledice',
            '/\bposljedica\b/u' => 'posledica',
            '/\bposljednji\b/u' => 'poslednji',
            '/\bposljednja\b/u' => 'poslednja',
            '/\bposljednje\b/u' => 'poslednje',
            '/\btko\b/u' => 'ko',

            '/\bOvdje\b/u' => 'Ovde',
            '/\bGdje\b/u' => 'Gde',
            '/\bUvijek\b/u' => 'Uvek',
            '/\bOduvijek\b/u' => 'Oduvek',
            '/\bTijekom\b/u' => 'Tokom',
            '/\bSljedeći\b/u' => 'Sledeći',
            '/\bSljedećeg\b/u' => 'Sledećeg',
            '/\bSljedećim\b/u' => 'Sledećim',
            '/\bSljedeća\b/u' => 'Sledeća',
            '/\bSljedeće\b/u' => 'Sledeće',
            '/\bPrimjer\b/u' => 'Primer',
            '/\bPrimjera\b/u' => 'Primera',
            '/\bPrimjeri\b/u' => 'Primeri',
            '/\bVrijeme\b/u' => 'Vreme',
            '/\bVrijednost\b/u' => 'Vrednost',
            '/\bVrijednosti\b/u' => 'Vrednosti',
            '/\bTisuća\b/u' => 'Hiljada',
            '/\bPovijest\b/u' => 'Istorija',
            '/\bPovijesni\b/u' => 'Istorijski',
            '/\bPovijesnog\b/u' => 'Istorijskog',
            '/\bPovijesna\b/u' => 'Istorijska',
            '/\bPovijesne\b/u' => 'Istorijske',
            '/\bSvećenik\b/u' => 'Sveštenik',
            '/\bSvećenika\b/u' => 'Sveštenika',
            '/\bSvećenici\b/u' => 'Sveštenici',
            '/\bPosljedice\b/u' => 'Posledice',
            '/\bPosljedica\b/u' => 'Posledica',
            '/\bPosljednji\b/u' => 'Poslednji',
            '/\bPosljednja\b/u' => 'Poslednja',
            '/\bPosljednje\b/u' => 'Poslednje',
            '/\bTko\b/u' => 'Ko',
        ];

        foreach ($replacements as $pattern => $replacement) {
            $text = preg_replace($pattern, $replacement, $text);
        }

        return $text;
    }
}