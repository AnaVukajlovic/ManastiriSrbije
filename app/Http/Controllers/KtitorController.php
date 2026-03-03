<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Ktitor;

class KtitorController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $query = Ktitor::query()
            ->with(['mainImage']); // ✅ slika na kartici (bez N+1)

        if ($q !== '') {
            $query->where(function ($qq) use ($q) {
                $qq->where('name', 'like', "%{$q}%")
                   ->orWhere('bio', 'like', "%{$q}%");
            });
        }

        $ktitors = $query
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('pages.ktitors.index', compact('ktitors', 'q'));
    }

    public function show(string $slug)
    {
        $ktitor = Ktitor::query()
            ->with(['images', 'mainImage']) // ✅ da show.blade vidi slike
            ->where('slug', $slug)
            ->firstOrFail();

        // Ako imaš relaciju monasteries() – super. Ako nemaš, neće puknuti.
        $monasteries = method_exists($ktitor, 'monasteries')
            ? $ktitor->monasteries()->orderBy('name')->get()
            : collect();

        return view('pages.ktitors.show', compact('ktitor', 'monasteries'));
    }

    // ASK AI
    public function askAi(Request $request, string $slug)
    {
        $ktitor = Ktitor::query()
            ->where('slug', $slug)
            ->firstOrFail();

        $question = trim((string) $request->input('question', ''));
        if ($question === '') {
            return response()->json(['error' => 'Unesi pitanje.'], 422);
        }

        // 1) OLLAMA podešavanja iz .env
        $baseUrl = rtrim(config('services.ollama.base_url', env('OLLAMA_BASE_URL', 'http://127.0.0.1:11434')), '/');
        $model   = config('services.ollama.model', env('OLLAMA_MODEL', 'llama3.1:latest'));

        // 2) Kontekst iz baze
        $years = ($ktitor->born_year || $ktitor->died_year)
            ? (($ktitor->born_year ?? '—') . ' – ' . ($ktitor->died_year ?? '—'))
            : '—';

        $dbBio = trim((string) ($ktitor->bio ?? ''));

        // 3) Wikipedia (opciono)
        $wikiText = '';
        $wikiEnabled = filter_var(env('AI_WIKI_ENABLED', true), FILTER_VALIDATE_BOOL);

        if ($wikiEnabled) {
            try {
                // Direktan summary po imenu (nekad radi odmah)
                $summary = Http::timeout(10)
                    ->acceptJson()
                    ->get('https://sr.wikipedia.org/api/rest_v1/page/summary/' . rawurlencode($ktitor->name));

                if ($summary->ok()) {
                    $wikiText = (string) ($summary->json('extract') ?? '');
                }

                // fallback: search -> summary po naslovu
                if (trim($wikiText) === '') {
                    $search = Http::timeout(10)
                        ->acceptJson()
                        ->get('https://sr.wikipedia.org/w/api.php', [
                            'action' => 'query',
                            'list' => 'search',
                            'srsearch' => $ktitor->name,
                            'format' => 'json',
                            'utf8' => 1,
                        ]);

                    if ($search->ok()) {
                        $title = $search->json('query.search.0.title');
                        if ($title) {
                            $summary2 = Http::timeout(10)
                                ->acceptJson()
                                ->get('https://sr.wikipedia.org/api/rest_v1/page/summary/' . rawurlencode($title));

                            if ($summary2->ok()) {
                                $wikiText = (string) ($summary2->json('extract') ?? '');
                            }
                        }
                    }
                }
            } catch (\Throwable $e) {
                $wikiText = '';
            }
        }

        // 4) Finalni kontekst (baza + wiki)
        $contextParts = [];
        $contextParts[] = "Ime: {$ktitor->name}";
        $contextParts[] = "Godine: {$years}";
        $contextParts[] = "Biografija (baza): " . ($dbBio !== '' ? $dbBio : 'Nema biografije u bazi.');

        if (trim($wikiText) !== '') {
            $contextParts[] = "Wikipedia sažetak (proveriti tačnost): {$wikiText}";
        }

        $context = implode("\n", $contextParts);

        // 5) Prompt
        $prompt = <<<PROMPT
Ti si istoričar-asistent za srpske pravoslavne ktitore.

PRAVILA:
- Primarno koristi "Biografija (baza)".
- Wikipedia koristi samo kao pomoć i naglasi ako nešto nije sigurno.
- Ne izmišljaj činjenice. Ako nema dovoljno informacija, napiši: "Nemam dovoljno pouzdanih podataka da odgovorim tačno." i reci šta treba dopuniti u bazi.
- Odgovor 6–10 rečenica, jasno i informativno.

KONTEKST:
{$context}

PITANJE:
{$question}

ODGOVOR:
PROMPT;

        // 6) Poziv Ollama
        try {
            $resp = Http::timeout(60)
                ->acceptJson()
                ->post($baseUrl . '/api/generate', [
                    'model' => $model,
                    'prompt' => $prompt,
                    'stream' => false,
                    'options' => [
                        'temperature' => 0.2,
                        'top_p' => 0.9,
                    ],
                ]);

            if (!$resp->ok()) {
                return response()->json([
                    'error' => 'Ollama greška',
                    'details' => $resp->body(),
                ], 502);
            }

            $answer = (string) ($resp->json('response') ?? '');
            $answer = trim($answer) !== '' ? trim($answer) : 'Nema odgovora.';

            return response()->json(['answer' => $answer]);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Ne mogu da kontaktiram Ollamu',
                'details' => $e->getMessage(),
            ], 502);
        }
    }
}