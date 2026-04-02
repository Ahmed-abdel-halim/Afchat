<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Tag;

$tag = Tag::where('name', 'فرح')->first();
if ($tag) {
    $tag->slug = 'farah';
    $tag->save();
    echo "Slug for 'فرح' updated to 'farah'\n";
} else {
    echo "Tag 'فرح' not found\n";
}
