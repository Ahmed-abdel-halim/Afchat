<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

$apiKey = env('GEMINI_API_KEY');
echo "Checking API Key: " . substr($apiKey, 0, 5) . "...\n";

$response = Http::get("https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}");

if ($response->successful()) {
    $models = $response->json();
    echo "Available Models:\n";
    foreach ($models['models'] as $m) {
        echo "- " . $m['name'] . " (" . implode(',', $m['supportedGenerationMethods']) . ")\n";
    }
} else {
    echo "Failed to list models: " . $response->body() . "\n";
}
