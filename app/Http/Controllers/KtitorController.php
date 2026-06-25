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
        $normalizedQ = $this->normalizeSearch($q);

        $ktitors = Ktitor::query()
            ->with(['mainImage', 'images'])
            ->when($q !== '', function ($query) use ($q, $normalizedQ) {
                $query->where(function ($sub) use ($q, $normalizedQ) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('bio', 'like', "%{$q}%")
                        ->orWhere('slug', 'like', "%{$q}%");

                    $sub->orWhereRaw($this->sqliteNormalizeExpression('name') . ' LIKE ?', ["%{$normalizedQ}%"])
                        ->orWhereRaw($this->sqliteNormalizeExpression('bio') . ' LIKE ?', ["%{$normalizedQ}%"])
                        ->orWhereRaw($this->sqliteNormalizeExpression('slug') . ' LIKE ?', ["%{$normalizedQ}%"]);
                });
            })
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('pages.ktitors.index', [
            'ktitors' => $ktitors,
            'q' => $q,
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

        $baseUrl = rtrim(config('services.ollama.base_url', env('OLLAMA_BASE_URL', 'http://127.0.0.1:11434')), '/');
        $model = config('services.ollama.model', env('OLLAMA_MODEL', 'llama3.1:latest'));

        $years = ($ktitor->born_year || $ktitor->died_year)
            ? (($ktitor->born_year ?? '—') . ' – ' . ($ktitor->died_year ?? '—'))
            : '—';

        $dbBio = trim((string) ($ktitor->bio ?? ''));
        $manastiriNames = $ktitor->manastiri->pluck('name')->implode(', ');

        $wikiText = '';
        $wikiEnabled = filter_var(env('AI_WIKI_ENABLED', true), FILTER_VALIDATE_BOOL);

        if ($wikiEnabled) {
            try {
                $summary = Http::timeout(10)->acceptJson()->get('https://sr.wikipedia.org/api/rest_v1/page/summary/' . rawurlencode($ktitor->name));
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
            $contextParts[] = "Wikipedia sažetak (proveriti tačnost): {$wikiText}";
        }

        $context = implode("\n", $contextParts);

        $prompt = <<<PROMPT
Ti si istoričar-asistent za srpske pravoslavne ktitore.
KONTEKST: {$context}
PITANJE: {$question}
ODGOVOR:
PROMPT;

        try {
            $resp = Http::timeout(60)->acceptJson()->post($baseUrl . '/api/generate', [
                'model' => $model, 'prompt' => $prompt, 'stream' => false,
            ]);

            if (!$resp->ok()) return response()->json(['error' => 'Ollama greška.', 'details' => $resp->body()], 502);

            return response()->json(['answer' => trim($resp->json('response') ?? 'Nema odgovora.')]);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Ne mogu da kontaktiram Ollamu.', 'details' => $e->getMessage()], 502);
        }
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