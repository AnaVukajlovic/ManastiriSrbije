<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OllamaService
{
    public function generateJson(string $systemPrompt, string $userPrompt): array
    {
        $url = rtrim(config('services.ollama.url', 'http://127.0.0.1:11434'), '/');
        $model = config('services.ollama.model', 'qwen2.5:3b');
        $keepAlive = config('services.ollama.keep_alive', '10m');

        $response = Http::connectTimeout(2)
            ->timeout(10)
            ->retry(1, 150)
            ->post($url . '/api/generate', [
                'model' => $model,
                'system' => $systemPrompt,
                'prompt' => $userPrompt,
                'stream' => false,
                'format' => 'json',
                'keep_alive' => $keepAlive,
                'options' => [
                    'temperature' => 0.2,
                    'num_predict' => 160,
                ],
            ]);

        if (! $response->successful()) {
            throw new \RuntimeException('Ollama request failed.');
        }

        $payload = $response->json();
        $raw = $payload['response'] ?? '{}';

        $decoded = json_decode($raw, true);

        if (! is_array($decoded)) {
            throw new \RuntimeException('Invalid JSON returned by Ollama.');
        }

        return $decoded;
    }
}