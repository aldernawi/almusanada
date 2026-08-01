<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiKeyController extends Controller
{
    public function index()
    {
        $apiKeys = auth()->user()->apiKeys()->latest()->get();
        return view('account.api-keys', compact('apiKeys'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'required|in:read_only,full_access',
        ]);

        $randomKey = 'jot_' . Str::random(40);

        $apiKey = auth()->user()->apiKeys()->create([
            'name' => $validated['name'],
            'key' => $randomKey,
            'permissions' => $validated['permissions'],
        ]);

        return redirect()->back()->with('success', 'تم توليد مفتاح الـ API بنجاح: ' . $randomKey);
    }

    public function destroy(ApiKey $apiKey)
    {
        if ($apiKey->user_id !== auth()->id()) {
            abort(403);
        }

        $apiKey->delete();

        return redirect()->back()->with('success', 'تم حذف مفتاح الـ API بنجاح');
    }
}
