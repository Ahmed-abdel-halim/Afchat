<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SetupController extends Controller
{
    public function showBySlug(string $slug)
    {
        $setup = Setup::with(['user:id,name,email', 'tags:id,name','punchlines:id,setup_id,text,views,laughs'])
            ->where('slug', $slug)
            ->first();

        if (!$setup) {
            return response()->json([
                'message' => 'Setup not found'
            ], 404);
        }

        return response()->json([
            'data' => $setup
        ]);
    }
    
    public function store(Request $request)
    {
        $data = $request->validate([
            'text' => ['required','string','min:1','max:1000'],
            'media_type' => ['nullable','in:text,image,video'],
            'media_url' => ['nullable','string','max:2048'],
            'tags' => ['nullable','array'],
            'tags.*' => ['string','min:1','max:50'],
        ]);

        $mediaType = $data['media_type'] ?? 'text';

        // قواعد للـ media في setup (اختياري)
        if (in_array($mediaType, ['image','video'], true) && empty($data['media_url'])) {
            return response()->json(['message' => 'media_url is required when media_type is image/video'], 422);
        }

        // slug unique
        $baseSlug = Str::slug(Str::limit($data['text'], 60, ''));
        $slug = $baseSlug ?: Str::random(8);

        $i = 1;
        $finalSlug = $slug;
        while (Setup::where('slug', $finalSlug)->exists()) {
            $finalSlug = $slug . '-' . $i++;
        }

        $setup = Setup::create([
            'user_id' => $request->user()->id,
            'text' => $data['text'],
            'slug' => $finalSlug,
            'media_type' => $mediaType,
            'media_url' => $data['media_url'] ?? null,
        ]);

        // tags: create/find + sync على setup_tags
        $tagNames = collect($data['tags'] ?? [])
            ->map(fn($t) => trim($t))
            ->filter()
            ->unique()
            ->values();

        if ($tagNames->isNotEmpty()) {
            $tagIds = $tagNames->map(function ($name) {
                $tag = Tag::firstOrCreate(['name' => $name]);
                return $tag->id;
            });

            $setup->tags()->sync($tagIds->all());
        }

        $setup->load(['tags:id,name', 'user:id,name,avatar']);

        return response()->json([
            'data' => [
                'id' => $setup->id,
                'text' => $setup->text,
                'slug' => $setup->slug,
                'media_type' => $setup->media_type,
                'media_url' => $setup->media_url,
                'tags' => $setup->tags->map(fn($t) => [
                    'id' => $t->id,
                    'name' => $t->name,
                ])->values(),
            ]
        ], 201);
    }
}

