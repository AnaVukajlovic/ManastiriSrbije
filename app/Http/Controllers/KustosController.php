<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Monastery;
use App\Models\Ktitor;

class KustosController extends Controller
{
    private function getApiKey(): string
    {
        return (string) config('services.groq.key', env('GROQ_API_KEY', ''));
    }

    private function getModel(): string
    {
        return (string) config('services.groq.model', env('GROQ_MODEL', 'llama-3.3-70b-versatile'));
    }

    private function sendGroqRequest($data) {
        $apiKey = $this->getApiKey();
        
        if (empty($apiKey)) {
            return ['error' => 'Groq API ključ nije konfigurisan.'];
        }

        $modelsToTry = array_unique(array_filter([
            'groq/compound-mini',
            $data['model'] ?? $this->getModel(),
            'qwen/qwen3.6-27b',
            'groq/compound',
            'openai/gpt-oss-120b'
        ]));

        foreach ($modelsToTry as $m) {
            $data['model'] = $m;
            try {
                $response = \Illuminate\Support\Facades\Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->connectTimeout(5)
                ->timeout(30)
                ->post('https://api.groq.com/openai/v1/chat/completions', $data);

                if ($response->successful()) {
                    return $response->json();
                }

                \Illuminate\Support\Facades\Log::warning("Groq model {$m} returned {$response->status()}, trying next model...");
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning("Groq model {$m} exception: {$e->getMessage()}, trying next model...");
            }
        }

        return ['error' => 'Svi modeli su trenutno nedostupni.'];
    }

    public function chat(Request $request)
    {
        $poruka = trim((string) $request->input('poruka', ''));
        $id = $request->input('id');
        $tip = $request->input('tip', 'manastir'); // 'manastir' ili 'ktitor'

        if (empty($poruka)) {
            return response()->json([
                'odgovor' => 'Молимо унесите питање.',
                'answer' => 'Молимо унесите питање.'
            ]);
        }

        if ($tip === 'manastir') {
            $entitet = is_numeric($id) ? Monastery::find($id) : Monastery::where('slug', $id)->first();
            if (!$entitet && $id) {
                $entitet = Monastery::find($id);
            }
        } else {
            $entitet = is_numeric($id) ? Ktitor::with('manastiri')->find($id) : Ktitor::with('manastiri')->where('slug', $id)->first();
            if (!$entitet && $id) {
                $entitet = Ktitor::with('manastiri')->find($id);
            }
        }
        
        if (!$entitet) {
            return response()->json([
                'odgovor' => 'Нисам пронашао податке о овом ентитету у бази.',
                'answer' => 'Нисам пронашао податке о овом ентитету у бази.'
            ]);
        }

        $pismo = session('pismo', 'cyrillic'); 
        $pismoTekst = ($pismo === 'cyrillic') ? "ИСКЉУЧИВО ЋИРИЛИЦОМ" : "ИСКЉУЧИВО ЛАТИНИЦОМ";

        // ДИНАМИЧКО ИЗВЛАЧЕЊЕ ПОДАТАКА
        if ($tip === 'manastir') {
            $ime = $entitet->name ?? $entitet->ime ?? 'Манастир';
            $ktitor = !empty($entitet->ktitor) ? "Ктитор: {$entitet->ktitor}. " : '';
            $godina = !empty($entitet->godina_izgradnje) ? "Период настанка: {$entitet->godina_izgradnje}. " : '';
            $lokacija = (!empty($entitet->city) ? "Место/град: {$entitet->city}. " : '') . (!empty($entitet->region) ? "Регион: {$entitet->region}. " : '');
            $bazaPodaci = "Назив светиње: {$ime}.\n{$ktitor}{$godina}{$lokacija}\nОпис и историјат: " . ($entitet->description ?? '') . ' ' . ($entitet->history ?? '');

            $systemPrompt = "Ти си Дигитални Летописац и кустос – академски стручњак за манастир \"{$ime}\" и српску православну баштину.

КОНТЕКСТ ИЗ БАЗЕ ПОДАТАКА О ОВОМ МАНАСТИРУ:
{$bazaPodaci}

СТРОГА ПРАВИЛА:
1. ПИСМО И ЈЕЗИК: Пиши {$pismoTekst}. Српски језик, екавски изговор.
2. ТЕМАТИКА: Питања се односе на манастир \"{$ime}\". Одговарај тачно, аутентично и историјски утемељено о овој светињи, њеном ктитору и предању.
3. ТАЧНОСТ: Користи податке из базе и своје опште историјско знање о овој светињи. Немој измишљати чињенице.
4. ФОРМАТ: Одмах пружи директан, јасан и срдачан одговор. Немој писати <think> блокове нити интерна размишљања.";
        } else {
            $ime = $entitet->name ?? $entitet->ime ?? 'Ктитор';
            $titula = !empty($entitet->title) ? "Титула/статус: {$entitet->title}. " : '';
            $godine = (!empty($entitet->born_year) ? "Година рођења: {$entitet->born_year}. " : '') . (!empty($entitet->died_year) ? "Година смрти: {$entitet->died_year}. " : '');
            $sahranjen = !empty($entitet->burial_place) ? "Место сахране: {$entitet->burial_place}. " : '';
            $manastiriNazivi = ($entitet->manastiri && $entitet->manastiri->isNotEmpty()) ? $entitet->manastiri->pluck('name')->implode(', ') : '';
            $zaduzbine = !empty($manastiriNazivi) ? "Повезани манастири и задужбине: {$manastiriNazivi}. " : '';
            $bazaPodaci = "Ктитор: {$ime}.\n{$titula}{$godine}{$sahranjen}{$zaduzbine}\nБиографија и историјски значај: " . ($entitet->bio ?? 'Нема биографије.');

            $systemPrompt = "Ти си Дигитални Летописац и кустос – академски стручњак за српске средњовековне ктиторе, владаре и манастире.

КОНТЕКСТ ИЗ БАЗЕ ПОДАТАКА О ЛИЧНОСТИ:
{$bazaPodaci}

СТРОГА ПРАВИЛА:
1. ПИСМО И ЈЕЗИК: Пиши {$pismoTekst}. Српски језик, екавски изговор.
2. ТЕМАТИКА: Корисник се налази на профилу ктитора \"{$ime}\". Чак и ако корисник пита у другом лицу (нпр. 'које су твоје задужбине', 'где си сахрањен', 'када си владао'), питање се ИСКЉУЧИВО односи на личност: {$ime}.
3. ТАЧНОСТ: Говори ИСКЉУЧИВО о личности \"{$ime}\" и његовим/њеним делима и задужбинама. Никада немој мешати ову личност са другим владарима (нпр. ако је реч о личности {$ime}, немој говорити о неком другом ктитору).
4. ЗАДУЖБИНЕ: Када корисник пита за задужбине, наведи оне које је подигао или обновио управо {$ime}.
5. ФОРМАТ: Одмах пружи директан, прецизан и достојанствен одговор. Немој писати <think> ознаке нити интерна размишљања.";
        }

        // 1. Покушај локалну Ollamu (ако је покренута)
        $ollamaUrl = config('services.ollama.url', env('OLLAMA_URL', 'http://127.0.0.1:11434'));
        $ollamaModel = config('services.ollama.model', env('OLLAMA_MODEL', 'qwen2.5:3b'));
        try {
            $ollamaResp = \Illuminate\Support\Facades\Http::connectTimeout(2)->timeout(25)->post($ollamaUrl . '/api/generate', [
                'model' => $ollamaModel,
                'system' => $systemPrompt,
                'prompt' => $poruka,
                'stream' => false,
            ]);

            if ($ollamaResp->successful() && !empty($ollamaResp->json('response'))) {
                $rawOllama = (string) $ollamaResp->json('response');
                $cleanOllama = preg_replace('/<think>[\s\S]*?<\/think>/i', '', $rawOllama);
                $cleanOllama = preg_replace('/<think>[\s\S]*$/i', '', $cleanOllama);
                $cleanOllama = trim($cleanOllama);

                if (!empty($cleanOllama)) {
                    return response()->json([
                        'odgovor' => $cleanOllama,
                        'answer' => $cleanOllama
                    ]);
                }
            }
        } catch (\Throwable $oe) {
            // Ollama nije pokrenuta, prelazimo automatski na Groq
        }

        // 2. Покушај Groq API
        try {
            $result = $this->sendGroqRequest([
                'model' => $this->getModel(),
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $poruka]
                ],
                'temperature' => 0.2,
                'max_tokens' => 2000
            ]);

            if (!isset($result['error'])) {
                $raw = $result['choices'][0]['message']['content'] ?? '';

                // Безбедно уклањање reasoning/think тагова
                if (stripos($raw, '</think>') !== false) {
                    $parts = preg_split('/<\/think>/i', $raw);
                    $odgovor = trim(end($parts));
                } else {
                    $odgovor = preg_replace('/<think>[\s\S]*?<\/think>/i', '', $raw);
                    $odgovor = preg_replace('/<think>[\s\S]*$/i', '', $odgovor);
                    $odgovor = trim($odgovor);
                }

                if (!empty($odgovor)) {
                    return response()->json([
                        'odgovor' => $odgovor,
                        'answer' => $odgovor
                    ]);
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Groq chat error: ' . $e->getMessage());
        }

        // 3. Динамички безбедни одговор из базе података за конкретан ентитет (никад хардкодован)
        if ($tip === 'manastir') {
            $ime = $entitet->name ?? 'Овај манастир';
            $info = (!empty($entitet->city) ? "налази се у месту {$entitet->city}. " : '') . (!empty($entitet->ktitor) ? "Његов ктитор је {$entitet->ktitor}. " : '');
            $opis = !empty($entitet->description) ? \Illuminate\Support\Str::limit(strip_tags($entitet->description), 260) : 'Значајна је светиња српске духовности и културе.';
            $odgovor = "{$ime} {$info}{$opis}";
        } else {
            $ime = $entitet->name ?? 'Ова историјска личност';
            $zad = !empty($manastiriNazivi) ? "Његове/њене познате задужбине и повезани манастири су: {$manastiriNazivi}. " : '';
            $bio = !empty($entitet->bio) ? \Illuminate\Support\Str::limit(strip_tags($entitet->bio), 260) : 'Једна од кључних личности српске средњовековне историје.';
            $odgovor = "{$ime}: {$zad}{$bio}";
        }

        return response()->json([
            'odgovor' => $odgovor,
            'answer' => $odgovor
        ]);
    }

    public function contextGreeting(Request $request)
    {
        $id = $request->input('id') ?? $request->input('model_id');
        $tip = $request->input('type') ?? $request->input('tip') ?? $request->input('model_type') ?? 'manastir';
        $entitet = ($tip === 'manastir') ? Monastery::find($id) : Ktitor::find($id);

        $ime = $entitet ? ($entitet->ime ?? $entitet->name ?? 'овом светом месту') : ($request->input('name') ?? 'овом светом месту');

        if ($tip === 'manastir') {
            $greeting = "Помаже Бог. Добродошли у {$ime}. Као Дигитални Летописац, овде сам да вам помогнем у истраживању историје, архитектуре и предања ове светиње. Слободно ме упитајте све што вас занима.";
        } else {
            $greeting = "Помаже Бог. Добродошли. Овде смо да заједно истражимо живот, задужбине и историјско наслеђе које је оставио {$ime}. Као Дигитални Летописац, слободно ме упитајте било шта о његовој владавини, делима и светињама.";
        }

        return response()->json([
            'success' => true,
            'greeting' => $greeting,
            'odgovor' => $greeting,
            'answer' => $greeting
        ]);
    }
}