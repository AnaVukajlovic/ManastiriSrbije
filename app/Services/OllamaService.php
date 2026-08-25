<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OllamaService
{
    public function generateJson(string $systemPrompt, string $userPrompt): array
    {
        $apiKey = config('services.groq.key', env('GROQ_API_KEY'));
        $model = config('services.groq.model', env('GROQ_MODEL', 'groq/compound'));

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])
        ->connectTimeout(5)
        ->timeout(30)
        ->retry(1, 150)
        ->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => $model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $systemPrompt . ' Odgovor mora biti isključivo u validnom JSON formatu.'
                ],
                [
                    'role' => 'user',
                    'content' => $userPrompt
                ]
            ],
            'temperature' => 0.1,
            'max_tokens' => 300,
            'response_format' => ['type' => 'json_object']
        ]);

        if (! $response->successful()) {
            Log::error('Groq generateJson error: ' . $response->body());
            throw new \RuntimeException('Groq API request failed: ' . $response->body());
        }

        $payload = $response->json();
        $raw = $payload['choices'][0]['message']['content'] ?? '{}';

        // Čišćenje ako ima markdown blokova
        $clean = preg_replace('/^```(?:json)?\s*/i', '', trim($raw));
        $clean = preg_replace('/\s*```$/i', '', $clean);

        $decoded = json_decode($clean, true);

        if (! is_array($decoded)) {
            // Pokušaj izdvajanja JSON-a između zagrada { ... }
            if (preg_match('/\{[\s\S]*\}/', $raw, $matches)) {
                $decoded = json_decode($matches[0], true);
            }
        }

        if (! is_array($decoded)) {
            throw new \RuntimeException('Invalid JSON returned by Groq.');
        }

        return $decoded;
    }

    public function generateText(string $systemPrompt, string $userPrompt): string
    {
        $apiKey = config('services.groq.key', env('GROQ_API_KEY'));
        $model = config('services.groq.model', env('GROQ_MODEL', 'groq/compound'));

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])
        ->connectTimeout(5)
        ->timeout(30)
        ->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => $model,
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userPrompt]
            ],
            'temperature' => 0.4,
            'max_tokens' => 300,
        ]);

        if (! $response->successful()) {
            Log::error('Groq generateText error: ' . $response->body());
            throw new \RuntimeException('Groq API request failed: ' . $response->body());
        }

        $text = $response->json('choices.0.message.content') ?? '';
        // Čišćenje <think> blokova ako model koristi CoT
        $text = preg_replace('/<think>[\s\S]*?<\/think>/i', '', $text);

        return trim($text) ?: 'Objašnjenje nije dostupno.';
    }
}