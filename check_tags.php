<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Tag;

$tags = Tag::take(5)->get(['id', 'name', 'slug']);
echo "TAGS DATA:\n";
foreach($tags as $tag) {
    echo "ID: {$tag->id} | Name: {$tag->name} | Slug: {$tag->slug}\n";
}
