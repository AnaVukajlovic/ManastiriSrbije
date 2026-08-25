<?php

namespace App\Http\Controllers;

use App\Models\Monastery;
use App\Services\OllamaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MapAiController extends Controller
{
    public function recommendByCity(Request $request, OllamaService $ollama)
    {
        $validated = $request->validate([
            'city' => ['required', 'string', 'max:100'],
        ]);

        $cityInput = trim($validated['city']);
        $normalizedCity = $this->normalizeText($cityInput);

        // 1. Покушај претраге преко географске удаљености (најтачније)
        $cityCoords = $this->getCityCoordinates($normalizedCity);
        $candidates = collect();

        $allMonasteries = Monastery::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('latitude', '!=', 0)
            ->where('longitude', '!=', 0)
            ->get();

        if ($cityCoords) {
            $scored = [];
            foreach ($allMonasteries as $m) {
                $lat = (float) $m->latitude;
                $lng = (float) $m->longitude;
                $dist = $this->haversineDistance($cityCoords['lat'], $cityCoords['lng'], $lat, $lng);
                
                // Manastiri u krugu od 65 km
                if ($dist <= 65) {
                    $m->calculated_distance = round($dist, 1);
                    $scored[] = $m;
                }
            }

            usort($scored, fn($a, $b) => $a->calculated_distance <=> $b->calculated_distance);
            $candidates = collect(array_slice($scored, 0, 8));
        }

        // 2. Ако координате града нису познате или нема манастира у близини, претражујемо текст из базе
        if ($candidates->isEmpty()) {
            $aliases = $this->cityAliases($cityInput);
            $candidates = Monastery::query()
                ->whereNotNull('name')
                ->where(function ($q) use ($aliases) {
                    foreach ($aliases as $alias) {
                        $term = mb_strtolower($alias);
                        $q->orWhereRaw('LOWER(city) LIKE ?', ["%{$term}%"])
                          ->orWhereRaw('LOWER(region) LIKE ?', ["%{$term}%"])
                          ->orWhereRaw('LOWER(name) LIKE ?', ["%{$term}%"]);
                    }
                })
                ->limit(8)
                ->get();
        }

        // Ако и даље нема ништа, fallback на најпознатије
        if ($candidates->isEmpty()) {
            return response()->json([
                'success' => true,
                'city' => $cityInput,
                'ai_text' => "За град „{$cityInput}“ тренутно нисам пронашао манастире у непосредној близини у бази. Покушај са оближњим већим местом или регијом.",
                'items' => [],
                'fallback' => true,
            ]);
        }

        $contextItems = $candidates->take(5)->map(function ($m) {
            $distInfo = isset($m->calculated_distance) ? " (~{$m->calculated_distance} km)" : '';
            return [
                'name' => $m->name,
                'slug' => $m->slug,
                'city' => $m->city . $distInfo,
                'region' => $m->region,
                'short_description' => Str::limit(strip_tags((string) ($m->excerpt ?? $m->description ?? '')), 160),
            ];
        })->values()->all();

        $systemPrompt = <<<PROMPT
Ти си историјски водич кроз српске православне манастире у апликацији "Православни Светионик".
Твој задатак је да на основу унетог града и листе најближих манастира напишеш кратак, срдачан и користан предлог за посету (до 80 речи) на српском језику (екавица).
Издвој највише 3–4 најзначајнија манастира из понуђене листе.
Врати ИСКЉУЧИВО важећи JSON у овом формату:
{
  "ai_text": "кратак и топао предлог за посету",
  "recommended_slugs": ["slug-1", "slug-2", "slug-3"]
}
PROMPT;

        $userPrompt = json_encode([
            'city' => $cityInput,
            'candidates' => $contextItems,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $aiText = "За локацију „{$cityInput}“ и њену околину издвајамо најзначајније манастире из наше базе које вреди посетити:";
        $recommended = $candidates->take(4)->values();

        try {
            // Покушај Groq / Ollama за богат опис
            $apiKey = config('services.groq.key', env('GROQ_API_KEY'));
            if (!empty($apiKey)) {
                $groqResp = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type'  => 'application/json',
                ])
                ->connectTimeout(4)
                ->timeout(15)
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'groq/compound-mini',
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userPrompt]
                    ],
                    'temperature' => 0.2,
                    'max_tokens' => 400,
                    'response_format' => ['type' => 'json_object']
                ]);

                if ($groqResp->successful()) {
                    $json = json_decode((string) data_get($groqResp->json(), 'choices.0.message.content', ''), true);
                    if (!empty($json['ai_text'])) {
                        $aiText = $json['ai_text'];
                    }
                    if (!empty($json['recommended_slugs']) && is_array($json['recommended_slugs'])) {
                        $recFilter = $candidates->filter(fn($m) => in_array($m->slug, $json['recommended_slugs']))->values();
                        if ($recFilter->isNotEmpty()) {
                            $recommended = $recFilter;
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Map AI recommendation fallback: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'city' => $cityInput,
            'ai_text' => $aiText,
            'items' => $recommended->map(fn ($m) => [
                'id' => $m->id,
                'name' => $m->name,
                'slug' => $m->slug,
                'city' => $m->city,
                'region' => $m->region,
                'lat' => (float) ($m->latitude ?? $m->lat),
                'lng' => (float) ($m->longitude ?? $m->lng),
                'image' => $m->image_src,
                'distance_km' => isset($m->calculated_distance) ? $m->calculated_distance : null,
                'url' => route('monasteries.show', $m->slug),
            ])->values(),
        ]);
    }

    private function haversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * 6371;
    }

    private function getCityCoordinates(string $normalized): ?array
    {
        $map = [
            'kraljevo' => ['lat' => 43.725, 'lng' => 20.689],
            'beograd' => ['lat' => 44.817, 'lng' => 20.463],
            'belgrade' => ['lat' => 44.817, 'lng' => 20.463],
            'novi sad' => ['lat' => 45.267, 'lng' => 19.833],
            'novi_sad' => ['lat' => 45.267, 'lng' => 19.833],
            'nis' => ['lat' => 43.320, 'lng' => 21.895],
            'kragujevac' => ['lat' => 44.012, 'lng' => 20.916],
            'cacak' => ['lat' => 43.891, 'lng' => 20.350],
            'krusevac' => ['lat' => 43.583, 'lng' => 21.326],
            'subotica' => ['lat' => 46.100, 'lng' => 19.667],
            'valjevo' => ['lat' => 44.275, 'lng' => 19.898],
            'uzice' => ['lat' => 43.858, 'lng' => 19.848],
            'novi pazar' => ['lat' => 43.136, 'lng' => 20.512],
            'novi_pazar' => ['lat' => 43.136, 'lng' => 20.512],
            'vranje' => ['lat' => 42.551, 'lng' => 21.900],
            'leskovac' => ['lat' => 42.998, 'lng' => 21.946],
            'sabac' => ['lat' => 44.748, 'lng' => 19.690],
            'pozarevac' => ['lat' => 44.621, 'lng' => 21.187],
            'smederevo' => ['lat' => 44.662, 'lng' => 20.930],
            'sombor' => ['lat' => 45.774, 'lng' => 19.112],
            'zrenjanin' => ['lat' => 45.383, 'lng' => 20.381],
            'pancevo' => ['lat' => 44.870, 'lng' => 20.640],
            'pirot' => ['lat' => 43.153, 'lng' => 22.586],
            'zajecar' => ['lat' => 43.903, 'lng' => 22.277],
            'jagodina' => ['lat' => 43.977, 'lng' => 21.261],
            'cuprija' => ['lat' => 43.927, 'lng' => 21.370],
            'paracin' => ['lat' => 43.860, 'lng' => 21.407],
            'prokuplje' => ['lat' => 43.234, 'lng' => 21.588],
            'raska' => ['lat' => 43.286, 'lng' => 20.613],
            'prijepolje' => ['lat' => 43.388, 'lng' => 19.646],
            'priboj' => ['lat' => 43.589, 'lng' => 19.526],
            'nova varos' => ['lat' => 43.460, 'lng' => 19.813],
            'arandjelovac' => ['lat' => 44.307, 'lng' => 20.559],
            'topola' => ['lat' => 44.253, 'lng' => 20.683],
            'vrnjacka banja' => ['lat' => 43.626, 'lng' => 20.898],
            'trstenik' => ['lat' => 43.618, 'lng' => 20.999],
            'despotovac' => ['lat' => 44.093, 'lng' => 21.442],
            'loznica' => ['lat' => 44.533, 'lng' => 19.225],
            'bajina basta' => ['lat' => 43.969, 'lng' => 19.566],
            'bor' => ['lat' => 44.075, 'lng' => 22.095],
            'negotin' => ['lat' => 44.226, 'lng' => 22.531],
            'knjazevac' => ['lat' => 43.566, 'lng' => 22.257],
            'aleksandrovac' => ['lat' => 43.456, 'lng' => 21.047],
            'brus' => ['lat' => 43.382, 'lng' => 21.033],
            'kursumlija' => ['lat' => 43.140, 'lng' => 21.272],
            'boljevac' => ['lat' => 43.829, 'lng' => 21.956],
            'svilajnac' => ['lat' => 44.231, 'lng' => 21.196],
            'kladovo' => ['lat' => 44.606, 'lng' => 22.610],
            'bela crkva' => ['lat' => 44.898, 'lng' => 21.419],
            'vrsac' => ['lat' => 45.121, 'lng' => 21.303],
            'kula' => ['lat' => 45.609, 'lng' => 19.530],
            'vrbas' => ['lat' => 45.571, 'lng' => 19.641],
            'becej' => ['lat' => 45.616, 'lng' => 20.033],
            'backa palanka' => ['lat' => 45.250, 'lng' => 19.388],
            'sremska mitrovica' => ['lat' => 44.976, 'lng' => 19.612],
            'sremski karlovci' => ['lat' => 45.203, 'lng' => 19.934],
            'irig' => ['lat' => 45.097, 'lng' => 19.863],
            'indjija' => ['lat' => 45.048, 'lng' => 20.081],
            'ruma' => ['lat' => 45.008, 'lng' => 19.822],
            'trebinje' => ['lat' => 42.711, 'lng' => 18.344],
            'pristina' => ['lat' => 42.662, 'lng' => 21.165],
            'prizren' => ['lat' => 42.215, 'lng' => 20.741],
            'pec' => ['lat' => 42.659, 'lng' => 20.288],
        ];

        foreach ($map as $key => $coords) {
            if ($normalized === $key || str_contains($normalized, $key) || str_contains($key, $normalized)) {
                return $coords;
            }
        }

        return null;
    }

    private function cityAliases(string $city): array
    {
        $normalized = $this->normalizeText($city);

        $map = [
            'cacak' => ['čačak', 'cacak', 'ovčar', 'ovcar'],
            'nis' => ['niš', 'nis'],
            'krusevac' => ['kruševac', 'krusevac'],
            'kraljevo' => ['kraljevo', 'mataruška banja'],
            'beograd' => ['beograd', 'belgrade'],
            'kragujevac' => ['kragujevac'],
            'novi sad' => ['novi sad'],
            'novi pazar' => ['novi pazar'],
            'valjevo' => ['valjevo'],
            'uzice' => ['užice', 'uzice'],
        ];

        return $map[$normalized] ?? [$city, $normalized];
    }

    private function normalizeText(string $value): string
    {
        $value = mb_strtolower(trim($value));
        $replacements = [
            'č' => 'c', 'ć' => 'c',
            'ž' => 'z', 'š' => 's',
            'đ' => 'dj',
        ];

        return strtr($value, $replacements);
    }
}