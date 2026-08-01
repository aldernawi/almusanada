<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FormFieldController extends Controller
{
    public function store(Request $request, Form $form)
    {
        Gate::authorize('update', $form);

        $validated = $request->validate([
            'field_type' => 'required|in:text,textarea,email,number,date,time,select,checkbox,radio,file,scale,signature,section,image,video,html,phone,url,password,hidden,rating,price',
            'label' => 'required|string|max:255',
            'placeholder' => 'nullable|string|max:255',
            'help_text' => 'nullable|string',
            'options' => 'nullable|array',
            'default_value' => 'nullable|string',
            'required' => 'boolean',
            'order' => 'integer',
            'validation_rules' => 'nullable|array',
            'settings' => 'nullable|array',
        ]);

        $field = $form->fields()->create($validated);

        return response()->json($field, 201);
    }

    public function update(Request $request, Form $form, FormField $field)
    {
        Gate::authorize('update', $form);

        $validated = $request->validate([
            'label' => 'sometimes|required|string|max:255',
            'placeholder' => 'nullable|string|max:255',
            'help_text' => 'nullable|string',
            'options' => 'nullable|array',
            'default_value' => 'nullable|string',
            'required' => 'boolean',
            'order' => 'integer',
            'validation_rules' => 'nullable|array',
            'settings' => 'nullable|array',
        ]);

        $field->update($validated);

        return response()->json($field);
    }

    public function destroy(Form $form, FormField $field)
    {
        Gate::authorize('update', $form);
        $field->delete();
        return response()->json(['message' => 'Field deleted successfully']);
    }

    public function reorder(Request $request, Form $form)
    {
        Gate::authorize('update', $form);

        $validated = $request->validate([
            'fields' => 'required|array',
            'fields.*.id' => 'required|exists:form_fields,id',
            'fields.*.order' => 'required|integer',
        ]);

        foreach ($validated['fields'] as $fieldData) {
            FormField::where('id', $fieldData['id'])
                ->where('form_id', $form->id)
                ->update(['order' => $fieldData['order']]);
        }

        return response()->json(['message' => 'Fields reordered successfully']);
    }
}
