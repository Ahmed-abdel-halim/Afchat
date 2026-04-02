<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\Tag;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function index()
    {
        $setups = Setup::select('slug', 'updated_at')->get();
        $tags = Tag::select('slug', 'updated_at')->get();

        return response()->json([
            'setups' => $setups,
            'tags' => $tags
        ]);
    }
}
