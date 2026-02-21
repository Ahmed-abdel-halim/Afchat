<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use App\Models\Punchline;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    public function index($punchlineId)
    {
        $p = Punchline::findOrFail($punchlineId);

        $comments = Comment::query()
            ->where('punchline_id', $p->id)
            ->with('user:id,name,email')
            ->orderByDesc('id')
            ->get(['id','user_id','punchline_id','body','created_at']);

        return response()->json([
            'data' => $comments,
        ]);
    }

    public function store(Request $request, $punchlineId)
    {
        $p = Punchline::findOrFail($punchlineId);

        $data = $request->validate([
            'body' => ['required','string','min:1','max:500'],
        ]);

        $comment = Comment::create([
            'user_id' => $request->user()->id,
            'punchline_id' => $p->id,
            'body' => $data['body'],
        ]);

        $comment->load('user:id,name,email');

        return response()->json([
            'data' => $comment,
        ], 201);
    }
}
