<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setup;
use App\Models\Punchline;
use App\Models\User;
use App\Models\Comment;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'setups_count' => Setup::count(),
            'punchlines_count' => Punchline::count(),
            'users_count' => User::count(),
            'comments_count' => Comment::count(),
        ];

        $months = [];
        $setups_history = [];
        $punchlines_history = [];
        $users_history = [];
        $comments_history = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $months[] = $monthDate->translatedFormat('M');
            
            $start = $monthDate->copy()->startOfMonth();
            $end = $monthDate->copy()->endOfMonth();

            $setups_history[] = Setup::whereBetween('created_at', [$start, $end])->count();
            $punchlines_history[] = Punchline::whereBetween('created_at', [$start, $end])->count();
            $users_history[] = User::whereBetween('created_at', [$start, $end])->count();
            $comments_history[] = Comment::whereBetween('created_at', [$start, $end])->count();
        }

        $latest_setups = Setup::with(['user'])->withCount('punchlines')->latest()->take(5)->get();
        $latest_users = User::latest()->take(5)->get();
        $latest_punchlines = Punchline::with(['user'])->latest()->take(5)->get();

        return view('admin.index', compact(
            'stats', 
            'latest_setups', 
            'latest_users', 
            'latest_punchlines',
            'months',
            'setups_history',
            'punchlines_history',
            'users_history',
            'comments_history'
        ));
    }

    public function setups()
    {
        $setups = Setup::with('user')->latest()->paginate(12);
        return view('admin.setups.index', compact('setups'));
    }

    public function punchlines()
    {
        $punchlines = Punchline::with(['user', 'setup', 'comments.user'])->latest()->paginate(12);
        return view('admin.punchlines.index', compact('punchlines'));
    }

    public function users()
    {
        $users = User::latest()->paginate(12);
        return view('admin.users.index', compact('users'));
    }

    public function storeSetup(Request $request)
    {
        $request->validate([
            'text' => 'required|string|min:5',
        ]);

        Setup::create([
            'text' => $request->text,
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'تم إضافة القفشة بنجاح');
    }

    public function deleteSetup($id)
    {
        Setup::findOrFail($id)->delete();
        return back()->with('success', 'تم حذف القفشة بنجاح');
    }

    public function updateSetup(Request $request, $id)
    {
        $request->validate([
            'text' => 'required|string|min:5',
        ]);

        $setup = Setup::findOrFail($id);
        $setup->update([
            'text' => $request->text,
        ]);

        return back()->with('success', 'تم تحديث القفشة بنجاح');
    }

    public function deletePunchline($id)
    {
        Punchline::findOrFail($id)->delete();
        return back()->with('success', 'تم حذف الرد بنجاح');
    }

    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'تم حذف المستخدم بنجاح');
    }

    public function deleteComment($id)
    {
        Comment::findOrFail($id)->delete();
        return back()->with('success', 'تم حذف التعليق بنجاح');
    }
}

