<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

$apiKey = env('GEMINI_API_KEY');
$baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';

echo "Testing Gemini API...\n";
echo "API Key prefix: " . substr($apiKey, 0, 5) . "...\n";
echo "Base URL: " . $baseUrl . "\n";

try {
    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
    ])->post($baseUrl . '?key=' . $apiKey, [
        'contents' => [
            [
                'parts' => [
                    ['text' => 'Hello, are you there? Respond with "Yes" in JSON format.']
                ]
            ]
        ],
        'generationConfig' => [
            'response_mime_type' => 'application/json',
        ]
    ]);

    if ($response->successful()) {
        echo "SUCCESS!\n";
        echo "Response: " . $response->body() . "\n";
    } else {
        echo "FAILED!\n";
        echo "Status: " . $response->status() . "\n";
        echo "Body: " . $response->body() . "\n";
    }
} catch (\Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
}
