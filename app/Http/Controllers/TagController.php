<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TagController extends Controller
{
    public function showBySlug(string $slug)
    {
        Log::info('Incoming Tag Slug: ' . $slug);
        // Find tag and associated setups with their punchlines
        $tag = Tag::with(['setups' => function($query) {
                // For SEO landing page we want to display the setups and their top punchline
                $query->with(['user:id,name,email', 'punchlines']);
            }])
            ->where('slug', urldecode($slug))
            ->first();

        if (!$tag) {
            return response()->json([
                'message' => 'Tag not found'
            ], 404);
        }

        // Sort punchlines by laughs (optional, can be done fully on frontend)
        $tag->setups->each(function ($setup) {
            $setup->setRelation('punchlines', $setup->punchlines->sortByDesc('laughs')->values());
        });

        return response()->json([
            'data' => $tag
        ]);
    }
}
