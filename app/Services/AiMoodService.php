<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AiMoodService
{
    public function analyzeMessage($text, $preference)
    {
        $prompt = "Analisis mood dari pesan ini: \"$text\".
        Mood harus salah satu: Senang, Sedih, Marah, Cemas, Netral.
        Preferensi motivasi: $preference
        Berikan JSON dengan format:
        {\"mood\":string, \"score\":float, \"desc\":string, \"motivation\":string}";

        $response = Http::withToken(env('AI_API_KEY'))
            ->post('https://api.kolosal.ai/v1/chat/completions', [
                "model" => "Claude Sonnet 4.5",
                "messages" => [
                    [
                        "role" => "user",
                        "content" => $prompt
                    ]
                ]
            ]);

        $json = json_decode($response->json()['choices'][0]['message']['content'], true);

        return [
            "mood_label" => $json["mood"] ?? "Netral",
            "confidence" => $json["score"] ?? 0.5,
            "description" => $json["desc"] ?? null,
            "motivation" => $json["motivation"] ?? null,
            "raw" => $response->json()
        ];
    }
}
