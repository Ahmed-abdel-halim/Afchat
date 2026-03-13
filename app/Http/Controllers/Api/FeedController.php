<?php

namespace App\Http\Controllers\Api;

use App\Models\Tag;
use App\Models\Setup;
use App\Models\Punchline;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FeedController extends Controller
{
    public function feed(Request $request)
    {
        $cursor = (int) $request->query('cursor', 0);

        $query = Setup::query()->orderByDesc('id');
        if ($cursor > 0) $query->where('id', '<', $cursor);

        $setup = $query->with([
            'user:id,name,avatar',
            'tags:id,name',
            'punchlines' => function ($q) {
                // رتّب punchlines بالأقوى أولاً مع جلب اليوزر
                $q->with('user:id,name,avatar')
                  ->orderByRaw('CASE WHEN views=0 THEN 0 ELSE laughs/views END DESC')
                  ->orderByDesc('laughs')
                  ->orderByDesc('id');
            }
        ])->first();

        if (!$setup) {
            return response()->json(['data' => null, 'next_cursor' => null]);
        }

        return response()->json([
            'data' => [
                'setup' => $setup,
                'user' => [
                    'id' => $setup->user->id,
                    'name' => $setup->user->name,
                    'avatar' => $setup->user->avatar,
                ],
                'tags' => $setup->tags->map(fn($t) => [
                    'id' => $t->id,
                    'name' => $t->name,
                ])->values(),
                'punchlines' => $setup->punchlines->map(fn($p) => [
                    'id' => $p->id,
                    'text' => $p->text,
                    'views' => $p->views,
                    'laughs' => $p->laughs,
                    'strength' => $p->strength,
                    'user' => $p->user,
                ])->values(),
            ],
            'next_cursor' => $setup->id,
        ]);
    }

    // GET /api/setups/{id}/punchlines
    public function punchlines($id)
    {
        $setup = Setup::with('tags:id,name')->findOrFail($id);
    
        $p = Punchline::where('setup_id', $id)
            ->orderByRaw('CASE WHEN views=0 THEN 0 ELSE laughs/views END DESC')
            ->orderByDesc('laughs')
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'text' => $p->text,
                'media_type' => $p->media_type,
                'media_url' => $p->media_url,
                'views' => $p->views,
                'laughs' => $p->laughs,
                'strength' => $p->strength,
            ]);
    
        return response()->json([
            'data' => [
                'setup' => [
                    'id' => $setup->id,
                    'text' => $setup->text,
                    'tags' => $setup->tags->map(fn($t) => [
                        'id' => $t->id,
                        'name' => $t->name,
                    ])->values(),
                ],
                'punchlines' => $p
            ]
        ]);
    }
    

    // POST /api/punchlines/{id}/view
    public function view($id)
    {
        $p = Punchline::findOrFail($id);
        $p->increment('views');
        return response()->json(['ok' => true]);
    }

    // POST /api/punchlines/{id}/laugh
    public function laugh($id)
    {
        $p = Punchline::findOrFail($id);
        $p->increment('laughs');
        return response()->json(['ok' => true]);
    }

    public function storePunchline(Request $request, $setupId)
    {
        $setup = Setup::findOrFail($setupId);
    
        $data = $request->validate([
            'media_type' => ['required','in:text,image,video'],
            'text' => ['nullable','string'],
            'media_file' => ['nullable', 'file', 'max:20480'], // max 20MB
        ]);
    
        if ($data['media_type'] === 'text' && empty($data['text'])) {
            return response()->json(['message' => 'text is required when media_type=text'], 422);
        }
    
        $mediaUrl = null;
        if (in_array($data['media_type'], ['image','video'], true)) {
            if (!$request->hasFile('media_file')) {
                return response()->json(['message' => 'media_file is required when media_type is image/video'], 422);
            }
            $path = $request->file('media_file')->store('media', 'public');
            $mediaUrl = asset('storage/' . $path);
        }
    
        $punchline = Punchline::create([
            'setup_id' => $setup->id,
            'user_id' => auth()->id(),
            'text' => $data['text'] ?? '',
            'media_type' => $data['media_type'],
            'media_url' => $mediaUrl,
            'views' => 0,
            'laughs' => 0,
        ]);
        
        $punchline->load('user:id,name,avatar');
    
        return response()->json([
            'data' => [
                'id' => $punchline->id,
                'setup_id' => $punchline->setup_id,
                'text' => $punchline->text,
                'media_type' => $punchline->media_type,
                'media_url' => $punchline->media_url,
                'views' => $punchline->views,
                'laughs' => $punchline->laughs,
                'strength' => $punchline->strength,
                'user' => $punchline->user,
            ]
        ], 201);
    }
    
}
