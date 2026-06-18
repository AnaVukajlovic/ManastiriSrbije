<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OllamaService
{
    public function generateJson(string $systemPrompt, string $userPrompt): array
    {
        // Povećavamo malo timeout pošto sada gađamo eksterni API
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
            'Content-Type' => 'application/json',
        ])
        ->connectTimeout(5)
        ->timeout(30)
        ->retry(1, 150)
        ->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => 'llama3-8b-8192', // Znatno brži model od onog koji si vrtela lokalno
            'messages' => [
                [
                    'role' => 'system',
                    // Groq zahteva da se reč "JSON" nađe u promptu kada tražimo JSON format
                    'content' => $systemPrompt . ' Odgovor mora biti isključivo u validnom JSON formatu.'
                ],
                [
                    'role' => 'user',
                    'content' => $userPrompt
                ]
            ],
            // Prepisani tvoji stari parametri za nivo kreativnosti i dužinu
            'temperature' => 0.0, 
            'max_tokens' => 120, // max_tokens je u Groq-u isto što i num_predict u Ollami
            'top_p' => 0.4,
            'response_format' => ['type' => 'json_object'] // Striktno forsiranje JSON-a
        ]);

        if (! $response->successful()) {
            throw new \RuntimeException('Groq API request failed: ' . $response->body());
        }

        $payload = $response->json();
        // Parsiranje OpenAI/Groq formata odgovora
        $raw = $payload['choices'][0]['message']['content'] ?? '{}';

        $decoded = json_decode($raw, true);

        if (! is_array($decoded)) {
            throw new \RuntimeException('Invalid JSON returned by Groq.');
        }

        return $decoded;
    }
}