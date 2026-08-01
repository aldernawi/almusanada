<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ReviewerController extends Controller
{
    public function index()
    {
        $reviewers = User::where('role', 'reviewer')->latest()->get();
        return view('admin.reviewers.index', compact('reviewers'));
    }

    public function create()
    {
        return view('admin.reviewers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6',
            'can_view_all_transactions' => 'boolean',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->username . '@reviewer.local',
            'password' => Hash::make($request->password),
            'role' => 'reviewer',
            'can_view_transactions' => true, // Reviewers always see transactions
            'can_view_all_transactions' => $request->has('can_view_all_transactions'),
        ]);

        return redirect()->route('admin.reviewers.index')->with('success', 'تم إنشاء حساب المراجع بنجاح');
    }

    public function edit($id)
    {
        $reviewer = User::where('role', 'reviewer')->findOrFail($id);
        return view('admin.reviewers.edit', compact('reviewer'));
    }

    public function update(Request $request, $id)
    {
        $reviewer = User::where('role', 'reviewer')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'can_view_all_transactions' => 'boolean',
        ]);

        $reviewer->name = $request->name;
        $reviewer->username = $request->username;

        if (!empty($request->password)) {
            $reviewer->password = Hash::make($request->password);
        }

        $reviewer->can_view_all_transactions = $request->has('can_view_all_transactions');
        $reviewer->save();

        return redirect()->route('admin.reviewers.index')->with('success', 'تم تحديث بيانات المراجع بنجاح');
    }

    public function destroy($id)
    {
        $reviewer = User::where('role', 'reviewer')->findOrFail($id);
        $reviewer->delete();
        return redirect()->route('admin.reviewers.index')->with('success', 'تم حذف حساب المراجع بنجاح');
    }
}
