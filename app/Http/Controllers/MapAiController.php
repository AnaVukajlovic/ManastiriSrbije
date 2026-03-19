<?php

namespace App\Http\Controllers;

use App\Models\Monastery;
use App\Services\OllamaService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MapAiController extends Controller
{
    public function recommendByCity(Request $request, OllamaService $ollama)
    {
        $validated = $request->validate([
            'city' => ['required', 'string', 'max:100'],
        ]);

        $cityInput = trim($validated['city']);
        $aliases = $this->cityAliases($cityInput);

        $monasteries = Monastery::query()
            ->whereNotNull('name')
            ->where(function ($q) use ($aliases) {
                foreach ($aliases as $alias) {
                    $q->orWhereRaw('LOWER(name) LIKE ?', ['%' . mb_strtolower($alias) . '%'])
                      ->orWhereRaw('LOWER(city) LIKE ?', ['%' . mb_strtolower($alias) . '%'])
                      ->orWhereRaw('LOWER(region) LIKE ?', ['%' . mb_strtolower($alias) . '%'])
                      ->orWhereRaw('LOWER(description) LIKE ?', ['%' . mb_strtolower($alias) . '%']);
                }
            })
            ->select([
                'id',
                'name',
                'slug',
                'city',
                'region',
                'description',
                'lat',
                'lng',
                'image',
            ])
            ->limit(8)
            ->get();

        if ($monasteries->isEmpty()) {
            return response()->json([
                'success' => true,
                'city' => $cityInput,
                'ai_text' => "Za grad „{$cityInput}“ trenutno nisam pronašla manastire u bazi. Pokušaj sa drugim gradom ili obližnjim mestom.",
                'items' => [],
                'fallback' => true,
            ]);
        }

        $contextItems = $monasteries->map(function ($m) {
            return [
                'name' => $m->name,
                'slug' => $m->slug,
                'city' => $m->city,
                'region' => $m->region,
                'short_description' => Str::limit(strip_tags((string) $m->description), 180),
            ];
        })->values()->all();

        $systemPrompt = <<<PROMPT
Ti si pomoćnik u aplikaciji o srpskim pravoslavnim manastirima.

Tvoj zadatak je:
- da na osnovu unetog grada i ponuđene liste manastira napišeš KRATAK predlog posete
- da izdvojiš najviše 3 manastira iz prosleđene liste

PRAVILA:
- koristi isključivo manastire iz prosleđene liste
- ne izmišljaj nove manastire, gradove, udaljenosti ni istorijske podatke
- piši kratko, prirodno i korisno
- ai_text neka bude najviše oko 90 reči
- recommended_slugs mora biti niz slug vrednosti iz prosleđene liste
- vrati ISKLJUČIVO JSON

Vrati tačno ovaj format:
{
  "ai_text": "kratak tekst",
  "recommended_slugs": ["slug-1", "slug-2", "slug-3"]
}
PROMPT;

        $userPrompt = json_encode([
            'city' => $cityInput,
            'aliases' => $aliases,
            'candidates' => $contextItems,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        try {
            $ai = $ollama->generateJson($systemPrompt, $userPrompt);

            $recommendedSlugs = collect($ai['recommended_slugs'] ?? [])
                ->filter(fn ($slug) => is_string($slug) && $slug !== '')
                ->values();

            $recommended = $monasteries
                ->filter(fn ($m) => $recommendedSlugs->contains($m->slug))
                ->values();

            if ($recommended->isEmpty()) {
                $recommended = $monasteries->take(3)->values();
            }

            return response()->json([
                'success' => true,
                'city' => $cityInput,
                'ai_text' => $ai['ai_text'] ?? "Za grad „{$cityInput}“ izdvojila sam nekoliko manastira iz tvoje baze koje možeš da posetiš.",
                'items' => $recommended->map(fn ($m) => [
                    'id' => $m->id,
                    'name' => $m->name,
                    'slug' => $m->slug,
                    'city' => $m->city,
                    'region' => $m->region,
                    'lat' => $m->lat,
                    'lng' => $m->lng,
                    'image' => $m->image,
                    'url' => route('monasteries.show', $m->slug),
                ])->values(),
            ]);
        } catch (\Throwable $e) {
            $fallback = $monasteries->take(3)->values();

            return response()->json([
                'success' => true,
                'city' => $cityInput,
                'ai_text' => "Za grad „{$cityInput}“ izdvojila sam nekoliko manastira iz tvoje baze koje možeš da posetiš.",
                'items' => $fallback->map(fn ($m) => [
                    'id' => $m->id,
                    'name' => $m->name,
                    'slug' => $m->slug,
                    'city' => $m->city,
                    'region' => $m->region,
                    'lat' => $m->lat,
                    'lng' => $m->lng,
                    'image' => $m->image,
                    'url' => route('monasteries.show', $m->slug),
                ])->values(),
                'fallback' => true,
            ]);
        }
    }

    private function cityAliases(string $city): array
    {
        $normalized = $this->normalizeText($city);

        $map = [
            'cacak' => ['čačak', 'cacak', 'ovčar', 'ovcar', 'ovčar-kablar', 'ovcar-kablar', 'ovčar banja', 'ovcar banja'],
            'nis' => ['niš', 'nis'],
            'krusevac' => ['kruševac', 'krusevac'],
            'kraljevo' => ['kraljevo', 'žiča', 'zica', 'mataruška banja', 'mataruska banja'],
            'beograd' => ['beograd', 'belgrade'],
            'kragujevac' => ['kragujevac'],
            'novi sad' => ['novi sad'],
            'novi_sad' => ['novi sad'],
        ];

        return $map[$normalized] ?? [$normalized];
    }

    private function normalizeText(string $text): string
    {
        $text = mb_strtolower(trim($text));

        return str_replace(
            ['č', 'ć', 'š', 'ž', 'đ'],
            ['c', 'c', 's', 'z', 'dj'],
            $text
        );
    }
}