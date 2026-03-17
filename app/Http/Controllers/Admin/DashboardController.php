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

        return view('admin.index', compact('stats'));
    }

    public function setups()
    {
        $setups = Setup::with('user')->latest()->paginate(12);
        return view('admin.setups.index', compact('setups'));
    }

    public function punchlines()
    {
        $punchlines = Punchline::with(['user', 'setup'])->latest()->paginate(12);
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
}
