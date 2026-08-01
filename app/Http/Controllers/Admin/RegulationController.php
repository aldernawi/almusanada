<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Regulation;
use Illuminate\Http\Request;

class RegulationController extends Controller
{
    public function index()
    {
        $regulations = Regulation::orderBy('sort_order')->get();
        return view('admin.regulations.index', compact('regulations'));
    }

    public function create()
    {
        return view('admin.regulations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'sort_order' => 'nullable|integer',
        ]);

        Regulation::create($request->all());

        return redirect()->route('admin.regulations.index')->with('success', 'Regulation added successfully.');
    }

    public function edit($id)
    {
        $regulation = Regulation::findOrFail($id);
        return view('admin.regulations.edit', compact('regulation'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'sort_order' => 'nullable|integer',
        ]);

        $regulation = Regulation::findOrFail($id);
        $regulation->update($request->all());

        return redirect()->route('admin.regulations.index')->with('success', 'Regulation updated successfully.');
    }

    public function destroy($id)
    {
        $regulation = Regulation::findOrFail($id);
        $regulation->delete();

        return redirect()->route('admin.regulations.index')->with('success', 'Regulation deleted successfully.');
    }
}
