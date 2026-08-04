<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'You are not authorized to access this page');
        }

        $users = User::latest()->paginate(20);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'You are not authorized to access this page');
        }

        $forms = Form::orderBy('title')->get(['id', 'title']);

        return view('users.create', compact('forms'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'You are not authorized to access this page');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,reviewer,viewer,user'],
            'form_limit' => ['nullable', 'integer', 'min:1'],
            'submission_limit' => ['nullable', 'integer', 'min:1'],
            'upload_limit_mb' => ['nullable', 'integer', 'min:1'],
            'form_ids' => ['array'],
            'form_ids.*' => ['integer', 'exists:forms,id'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'form_limit' => $request->form_limit ?? 10,
            'submission_limit' => $request->submission_limit ?? 1000,
            'upload_limit_mb' => $request->upload_limit_mb ?? 100,
        ]);

        if (in_array($user->role, ['reviewer', 'viewer'], true)) {
            $user->assignedForms()->sync($request->input('form_ids', []));
        }

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    public function edit(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'You are not authorized to access this page');
        }

        $forms = Form::orderBy('title')->get(['id', 'title']);
        $assignedFormIds = $user->assignedForms()->pluck('forms.id')->all();

        return view('users.edit', compact('user', 'forms', 'assignedFormIds'));
    }

    public function update(Request $request, User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'You are not authorized to access this page');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:admin,reviewer,viewer,user'],
            'form_limit' => ['nullable', 'integer', 'min:1'],
            'submission_limit' => ['nullable', 'integer', 'min:1'],
            'upload_limit_mb' => ['nullable', 'integer', 'min:1'],
            'form_ids' => ['array'],
            'form_ids.*' => ['integer', 'exists:forms,id'],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'form_limit' => $request->form_limit ?? 10,
            'submission_limit' => $request->submission_limit ?? 1000,
            'upload_limit_mb' => $request->upload_limit_mb ?? 100,
        ];

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', Rules\Password::defaults()],
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        if (in_array($user->role, ['reviewer', 'viewer'], true)) {
            $user->assignedForms()->sync($request->input('form_ids', []));
        } else {
            $user->assignedForms()->detach();
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'You are not authorized to access this page');
        }

        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Cannot delete your own account');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}
