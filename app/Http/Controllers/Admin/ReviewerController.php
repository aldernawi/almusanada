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
        $reviewers = User::whereIn('role', ['reviewer', 'viewer'])->latest()->get();
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
            'role' => 'required|in:reviewer,viewer',
            'can_view_all_transactions' => 'boolean',
        ]);

        $role = $request->input('role', 'reviewer');
        $emailDomain = $role === 'viewer' ? 'viewer.local' : 'reviewer.local';

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->username . '@' . $emailDomain,
            'password' => Hash::make($request->password),
            'role' => $role,
            'can_view_transactions' => true,
            'can_view_all_transactions' => $role === 'viewer' ? false : $request->has('can_view_all_transactions'),
        ]);

        $message = $role === 'viewer' ? 'تم إنشاء حساب الاطلاع بنجاح' : 'تم إنشاء حساب المراجع بنجاح';
        return redirect()->route('admin.reviewers.index')->with('success', $message);
    }

    public function edit($id)
    {
        $reviewer = User::whereIn('role', ['reviewer', 'viewer'])->findOrFail($id);
        return view('admin.reviewers.edit', compact('reviewer'));
    }

    public function update(Request $request, $id)
    {
        $reviewer = User::whereIn('role', ['reviewer', 'viewer'])->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'can_view_all_transactions' => 'boolean',
        ]);

        $reviewer->name = $request->name;
        $reviewer->username = $request->username;

        if (!empty($request->password)) {
            $reviewer->password = Hash::make($request->password);
        }

        $reviewer->can_view_all_transactions = $reviewer->role === 'viewer' ? false : $request->has('can_view_all_transactions');
        $reviewer->save();

        $message = $reviewer->role === 'viewer' ? 'تم تحديث بيانات حساب الاطلاع بنجاح' : 'تم تحديث بيانات المراجع بنجاح';
        return redirect()->route('admin.reviewers.index')->with('success', $message);
    }

    public function destroy($id)
    {
        $reviewer = User::whereIn('role', ['reviewer', 'viewer'])->findOrFail($id);
        $reviewer->delete();
        $message = $reviewer->role === 'viewer' ? 'تم حذف حساب الاطلاع بنجاح' : 'تم حذف حساب المراجع بنجاح';
        return redirect()->route('admin.reviewers.index')->with('success', $message);
    }
}
