<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Form;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
        ]);

        $folder = auth()->user()->folders()->create([
            'name' => $validated['name'],
            'color' => $validated['color'] ?? '#6366f1',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء المجلد بنجاح',
            'folder' => $folder
        ], 201);
    }

    public function destroy(Folder $folder)
    {
        if ($folder->user_id !== auth()->id()) {
            abort(403);
        }

        $folder->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف المجلد بنجاح'
        ]);
    }

    public function addForm(Request $request)
    {
        $validated = $request->validate([
            'form_id' => 'required|exists:forms,id',
            'folder_id' => 'required|exists:folders,id',
        ]);

        $form = Form::findOrFail($validated['form_id']);
        $folder = Folder::findOrFail($validated['folder_id']);

        if ($form->user_id !== auth()->id() || $folder->user_id !== auth()->id()) {
            abort(403);
        }

        // Check if already in folder
        if (!$folder->forms()->where('form_id', $form->id)->exists()) {
            $folder->forms()->attach($form->id);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم نقل النموذج للمجلد بنجاح'
        ]);
    }

    public function removeForm(Request $request)
    {
        $validated = $request->validate([
            'form_id' => 'required|exists:forms,id',
            'folder_id' => 'required|exists:folders,id',
        ]);

        $form = Form::findOrFail($validated['form_id']);
        $folder = Folder::findOrFail($validated['folder_id']);

        if ($form->user_id !== auth()->id() || $folder->user_id !== auth()->id()) {
            abort(403);
        }

        $folder->forms()->detach($form->id);

        return response()->json([
            'success' => true,
            'message' => 'تم إزالة النموذج من المجلد بنجاح'
        ]);
    }
}
