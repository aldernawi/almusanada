<?php

namespace App\Http\Controllers;

use App\Mail\FormSubmissionNotification;
use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class FormController extends Controller
{
    public function index()
    {
        $forms = auth()->user()->forms()->latest()->paginate(10);
        return view('forms.index', compact('forms'));
    }

    public function create()
    {
        return view('forms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thank_you_message' => 'nullable|string',
        ]);

        $form = auth()->user()->forms()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'slug' => Str::random(48),
            'thank_you_message' => $validated['thank_you_message'] ?? null,
            'status' => 'active',
        ]);

        return redirect()->route('forms.edit', $form)->with('success', 'Form created successfully');
    }

    public function show(Form $form)
    {
        Gate::authorize('view', $form);
        return view('forms.show', compact('form'));
    }

    public function publicShow($slug)
    {
        $form = Form::where('slug', $slug)->where('status', 'active')->with('fields')->firstOrFail();
        return view('forms.show', compact('form'));
    }

    public function edit(Form $form)
    {
        Gate::authorize('update', $form);
        return view('forms.builder-new', compact('form'));
    }

    public function update(Request $request, Form $form)
    {
        Gate::authorize('update', $form);

        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thank_you_message' => 'nullable|string',
            'status' => 'required|in:active,inactive,archived',
            'require_login' => 'boolean',
            'enable_captcha' => 'boolean',
            'webhook_url' => 'nullable|url|max:2048',
            'settings' => 'nullable|array',
            'settings.submit_button' => 'nullable|array',
            'settings.submit_button.label' => 'nullable|string|max:80',
            'settings.submit_button.color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ];

        if ($request->isJson()) {
            $rules = collect($rules)
                ->mapWithKeys(fn ($rule, $key) => [$key => 'sometimes|' . (is_array($rule) ? implode('|', $rule) : $rule)])
                ->all();
        }

        $validated = $request->validate($rules);

        if (array_key_exists('settings', $validated)) {
            $validated['settings'] = array_replace_recursive($form->settings ?? [], $validated['settings'] ?? []);
        }

        if (!$request->isJson()) {
            $validated['require_login'] = $request->boolean('require_login');
            $validated['enable_captcha'] = $request->boolean('enable_captcha');
        }

        $form->update($validated);

        if ($request->wantsJson() || $request->isJson()) {
            return response()->json([
                'success' => true,
                'form' => $form->fresh(),
            ]);
        }

        return redirect()->route('forms.edit', $form)->with('success', 'Form updated successfully');
    }

    public function destroy(Form $form)
    {
        Gate::authorize('delete', $form);
        $form->delete();
        return redirect()->route('forms.index')->with('success', 'Form deleted successfully');
    }

    public function duplicate(Form $form)
    {
        Gate::authorize('view', $form);

        $newForm = $form->replicate();
        $newForm->user_id = auth()->id();
        $newForm->title = $form->title . ' (Copy)';
        $newForm->slug = Str::random(48);
        $newForm->save();

        foreach ($form->fields as $field) {
            $newField = $field->replicate();
            $newField->form_id = $newForm->id;
            $newField->save();
        }

        return redirect()->route('forms.edit', $newForm)->with('success', 'Form duplicated successfully');
    }

    public function share(Form $form)
    {
        Gate::authorize('view', $form);
        return view('forms.share', compact('form'));
    }

    public function shareEmail(Request $request, Form $form)
    {
        Gate::authorize('view', $form);

        $validated = $request->validate([
            'emails' => 'required|string',
        ]);

        $emails = array_map('trim', explode("\n", str_replace(',', "\n", $validated['emails'])));
        $emails = array_filter($emails, function($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        });

        $formUrl = url('/f/' . $form->slug);

        foreach ($emails as $email) {
            Mail::raw(
                "Hello,\n\nThe form '{$form->title}' has been shared with you.\n\nYou can access the form via the following link:\n{$formUrl}\n\nThank you.",
                function ($message) use ($email, $form) {
                    $message->to($email)
                            ->subject('Invitation to fill out form: ' . $form->title);
                }
            );
        }

        return redirect()->route('forms.share', $form)->with('success', 'Invitations sent successfully to ' . count($emails) . ' email(s)');
    }

    public function toggleFavorite(Form $form)
    {
        Gate::authorize('update', $form);
        $form->update(['is_favorite' => !$form->is_favorite]);
        return response()->json(['success' => true, 'is_favorite' => $form->is_favorite]);
    }

    public function toggleArchive(Form $form)
    {
        Gate::authorize('update', $form);
        $archived_at = $form->archived_at ? null : now();
        $form->update(['archived_at' => $archived_at]);
        return response()->json(['success' => true, 'is_archived' => !is_null($archived_at)]);
    }

    public function restore($id)
    {
        $form = Form::onlyTrashed()->findOrFail($id);
        Gate::authorize('delete', $form);
        $form->restore();
        return response()->json(['success' => true]);
    }

    public function forceDelete($id)
    {
        $form = Form::onlyTrashed()->findOrFail($id);
        Gate::authorize('delete', $form);
        $form->forceDelete();
        return response()->json(['success' => true]);
    }
}
