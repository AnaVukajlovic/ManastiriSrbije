<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiController extends Controller
{
    public function chat(Request $request)
    {
        @set_time_limit(180);

        // podržavamo i "question" i "message"
        $question = trim((string) ($request->input('question') ?? $request->input('message') ?? ''));
        if ($question === '') {
            return response()->json(['ok' => false, 'error' => 'Pitanje je prazno.'], 422);
        }

        $context = trim((string) $request->input('context', ''));

        // ✅ koristi localhost jer si dokazala da radi na localhost
        $baseUrl = rtrim(env('OLLAMA_URL', 'http://localhost:11434'), '/');

        // ✅ bitno: ti sada koristiš qwen2.5:7b
        $model   = env('OLLAMA_MODEL', 'qwen2.5:7b');

        // ✅ Sistem poruka: tvrdo srpski + bez izmišljanja
        $system = <<<SYS
Ti si pouzdan asistent za aplikaciju "Pravoslavni Svetionik".
Odgovaraj ISKLJUČIVO na srpskom (latinica). Nikada ne koristi engleski.
Ne izmišljaj činjenice, datume, titule, veze među ličnostima ili izvore.
PRAVILO ISTINE:
- Ako je KONTEKST dat: koristi isključivo informacije iz konteksta.
- Ako nešto nije u kontekstu: reci tačno "Nemam pouzdane podatke iz konteksta."
Odgovor napiši u 3 do 7 rečenica, kratko i jasno.
SYS;

        $messages = [
            ['role' => 'system', 'content' => $system],
        ];

        if ($context !== '') {
            $messages[] = [
                'role' => 'system',
                'content' => "KONTEKST (jedini izvor istine):\n" . $context,
            ];
        } else {
            // Ako nema konteksta, bolje je odmah odseći halucinacije
            $messages[] = [
                'role' => 'system',
                'content' => 'Nema konteksta. Ne pokušavaj da nagađaš. Odgovori da nemaš podatke.',
            ];
        }

        $messages[] = ['role' => 'user', 'content' => $question];

        $payload = [
            'model' => $model,
            'messages' => $messages,
            'stream' => false,
            'options' => [
                'temperature' => 0.2,
                'top_p' => 0.9,
            ],
        ];

        try {
            $res = Http::connectTimeout(5)
                ->timeout(180)
                ->post($baseUrl . '/api/chat', $payload);

            if (!$res->successful()) {
                Log::error('Ollama failed', [
                    'status' => $res->status(),
                    'body'   => $res->body(),
                ]);

                return response()->json([
                    'ok' => false,
                    'error' => 'Ollama greška: ' . $res->status(),
                ], 502);
            }

            $data = $res->json();
            $answer = trim((string) data_get($data, 'message.content', ''));

            if ($answer === '') {
                Log::warning('Ollama empty answer', ['data' => $data]);
                return response()->json(['ok' => false, 'error' => 'AI nije vratio odgovor.'], 502);
            }

            // ✅ Kompatibilno vraćanje
            return response()->json([
                'ok' => true,
                'answer' => $answer,
                'reply'  => $answer,
            ]);
        } catch (\Throwable $e) {
            Log::error('AI exception', ['msg' => $e->getMessage()]);

            return response()->json([
                'ok' => false,
                'error' => 'Ne mogu da kontaktiram Ollama (timeout/konekcija).',
            ], 502);
        }
    }
}