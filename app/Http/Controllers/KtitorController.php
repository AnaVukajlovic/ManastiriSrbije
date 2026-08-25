<?php

namespace App\Http\Controllers;

use App\Models\Ktitor;
use App\Models\Monastery; // Dodato
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KtitorController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $category = trim((string) $request->query('category', 'all'));

        $query = Ktitor::query()->with(['mainImage', 'images', 'manastiri']);

        // Filter po dinastiji / kategoriji
        if ($category === 'nemanjici') {
            $query->where(function ($sub) {
                $sub->where('dynasty', 'like', '%Nemanji%')
                    ->orWhere('dynasty', 'like', '%Немањи%');
            });
        } elseif ($category === 'vladarke') {
            $query->where(function ($sub) {
                $sub->whereIn('slug', ['simonida', 'kneginja-milica', 'jelena-anzujska', 'carica-jelena', 'ana-dandolo', 'ana-zena-stefana-nemanje'])
                    ->orWhere('title', 'like', '%kraljica%')
                    ->orWhere('title', 'like', '%краљица%')
                    ->orWhere('title', 'like', '%carica%')
                    ->orWhere('title', 'like', '%царица%')
                    ->orWhere('title', 'like', '%kneginja%')
                    ->orWhere('title', 'like', '%кнегиња%');
            });
        } elseif ($category === 'lazarevici') {
            $query->where(function ($sub) {
                $sub->whereIn('slug', ['knez-lazar', 'kneginja-milica', 'stefan-lazarevic'])
                    ->orWhere('dynasty', 'like', '%Lazarev%')
                    ->orWhere('dynasty', 'like', '%Hrebeljanov%')
                    ->orWhere('dynasty', 'like', '%Лазарев%');
            });
        }

        if ($q !== '') {
            $terms = \App\Services\SearchService::getSearchTerms($q);
            $query->where(function ($sub) use ($terms) {
                foreach ($terms as $term) {
                    $sub->orWhere('name', 'like', "%{$term}%")
                        ->orWhere('bio', 'like', "%{$term}%")
                        ->orWhere('slug', 'like', "%{$term}%")
                        ->orWhere('title', 'like', "%{$term}%")
                        ->orWhere('dynasty', 'like', "%{$term}%");
                }
            });
        }

        $ktitors = $query->orderBy('born_year', 'asc')
            ->paginate(24)
            ->withQueryString();

        return view('pages.ktitors.index', [
            'ktitors' => $ktitors,
            'q' => $q,
            'category' => $category,
        ]);
    }

    public function show(string $slug)
    {
        // Ispravljeno: učitavamo 'manastiri' relaciju
        $ktitor = Ktitor::with(['mainImage', 'images', 'manastiri'])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('pages.ktitors.show', compact('ktitor'));
    }

    public function askAi(Request $request, string $slug)
    {
        // Ispravljeno: učitavamo 'manastiri' relaciju za kontekst
        $ktitor = Ktitor::query()
            ->with(['mainImage', 'images', 'manastiri'])
            ->where('slug', $slug)
            ->firstOrFail();

        $question = trim((string) $request->input('question', ''));

        if ($question === '') {
            return response()->json(['error' => 'Unesi pitanje.'], 422);
        }

        $baseUrl = rtrim((string) config('services.ollama.url', env('OLLAMA_URL', 'http://127.0.0.1:11434')), '/');
        $model = (string) config('services.ollama.model', env('OLLAMA_MODEL', 'qwen2.5:3b'));

        $years = ($ktitor->born_year || $ktitor->died_year)
            ? (($ktitor->born_year ?? '—') . ' – ' . ($ktitor->died_year ?? '—'))
            : '—';

        $dbBio = trim((string) ($ktitor->bio ?? ''));
        $manastiriNames = $ktitor->manastiri->pluck('name')->implode(', ');

        $wikiText = '';
        $wikiEnabled = filter_var(env('AI_WIKI_ENABLED', true), FILTER_VALIDATE_BOOL);

        if ($wikiEnabled) {
            try {
                $summary = Http::timeout(5)->acceptJson()->get('https://sr.wikipedia.org/api/rest_v1/page/summary/' . rawurlencode($ktitor->name));
                if ($summary->ok()) {
                    $wikiText = (string) ($summary->json('extract') ?? '');
                }
            } catch (\Throwable $e) {
                $wikiText = '';
            }
        }

        $contextParts = [];
        $contextParts[] = "Ime: {$ktitor->name}";
        $contextParts[] = "Godine: {$years}";
        $contextParts[] = "Povezani manastiri: " . ($manastiriNames !== '' ? $manastiriNames : 'Nema podataka.');
        $contextParts[] = "Biografija (baza): " . ($dbBio !== '' ? $dbBio : 'Nema biografije u bazi.');

        if (trim($wikiText) !== '') {
            $contextParts[] = "Wikipedia sažetak: {$wikiText}";
        }

        $context = implode("\n", $contextParts);

        $prompt = <<<PROMPT
Ti si istoričar-asistent za srpske pravoslavne ktitore.
KONTEKST:
{$context}

PITANJE: {$question}
ODGOVOR:
PROMPT;

        // 1. Pokušaj lokalnu Ollamu
        try {
            $resp = Http::connectTimeout(2)->timeout(25)->acceptJson()->post($baseUrl . '/api/generate', [
                'model' => $model,
                'prompt' => $prompt,
                'stream' => false,
            ]);

            if ($resp->ok() && !empty($resp->json('response'))) {
                return response()->json(['answer' => trim((string) $resp->json('response'))]);
            }
        } catch (\Throwable $e) {
            // Ollama nije dostupna ili je isteklo vreme, prelazimo na Groq fallback
        }

        // 2. Fallback na Groq ako postoji ključ
        $groqKey = config('services.groq.key', env('GROQ_API_KEY'));
        if (!empty($groqKey)) {
            try {
                $groqModel = config('services.groq.model', env('GROQ_MODEL', 'groq/compound'));
                $groqResp = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $groqKey,
                    'Content-Type' => 'application/json',
                ])->connectTimeout(5)->timeout(30)->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => $groqModel,
                    'messages' => [
                        ['role' => 'system', 'content' => 'Ti si istoričar-asistent za srpske pravoslavne ktitore. Koristi dostavljeni kontekst i odgovori direktno na srpskom jeziku bez <think> tagova.'],
                        ['role' => 'system', 'content' => "KONTEKST:\n" . $context],
                        ['role' => 'user', 'content' => $question]
                    ],
                    'max_tokens' => 1000,
                    'temperature' => 0.3
                ]);

                if ($groqResp->ok()) {
                    $ans = (string) $groqResp->json('choices.0.message.content');
                    $ans = preg_replace('/<think>[\s\S]*?<\/think>/i', '', $ans);
                    $ans = preg_replace('/<think>[\s\S]*$/i', '', $ans);
                    $ans = trim($ans);
                    if (!empty($ans)) {
                        return response()->json(['answer' => $ans]);
                    }
                }
            } catch (\Throwable $e) {
                // Nastavi na lokalni tekstualni odgovor
            }
        }

        // 3. Konačni fallback iz baze
        $fallbackAnswer = $ktitor->name . ' je ktitor o kome baza sadrži sledeće podatke: ' . ($dbBio !== '' ? $dbBio : 'Podaci se trenutno dopunjuju.');
        return response()->json(['answer' => $fallbackAnswer]);
    }

    private function normalizeSearch(string $value): string
    {
        $value = mb_strtolower($value, 'UTF-8');
        return str_replace(['š', 'č', 'ć', 'ž', 'đ'], ['s', 'c', 'c', 'z', 'dj'], $value);
    }

    private function sqliteNormalizeExpression(string $column): string
    {
        return "lower(replace(replace(replace(replace(replace($column, 'š', 's'), 'č', 'c'), 'ć', 'c'), 'ž', 'z'), 'đ', 'dj'))";
    }
}