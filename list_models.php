<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Http;

$apiKey = env('GEMINI_API_KEY');
$url = 'https://generativelanguage.googleapis.com/v1beta/models?key=' . $apiKey;

echo "Listing Models...\n";

try {
    $response = Http::get($url);

    if ($response->successful()) {
        $models = $response->json()['models'] ?? [];
        foreach ($models as $model) {
            echo "- " . $model['name'] . " (Display: " . $model['displayName'] . ")\n";
        }
    } else {
        echo "FAILED!\n";
        echo "Status: " . $response->status() . "\n";
        echo "Body: " . $response->body() . "\n";
    }
} catch (\Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
}
