<?php

namespace App\Http\Controllers;

use App\Events\FormSubmissionReceived;
use App\Mail\FormSubmissionNotification;
use App\Mail\SubmissionAutoresponder;
use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\SubmissionData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class FormSubmissionController extends Controller
{
    public function index(Form $form)
    {
        Gate::authorize('view', $form);
        $submissions = $form->submissions()->latest()->paginate(20);
        return view('submissions.index', compact('form', 'submissions'));
    }

    public function show(Form $form, FormSubmission $submission)
    {
        Gate::authorize('view', $form);
        $submission->load('submissionData.field');
        return view('submissions.show', compact('form', 'submission'));
    }

    public function store(Request $request, Form $form)
    {
        abort_unless($form->status === 'active' && !$form->archived_at, 404);

        $form->load('fields');

        $rules = [
            'fields' => 'required|array',
            'fields.*.field_id' => 'required|exists:form_fields,id',
            'fields.*.value' => 'nullable',
        ];

        foreach ($form->fields as $field) {
            $fieldKey = "fields.{$field->id}.value";

            if ($field->required && !in_array($field->field_type, ['file', 'image', 'video'])) {
                $rules[$fieldKey] = 'required';
            }

            if ($field->field_type === 'email') {
                $rules[$fieldKey] = ($field->required ? 'required|' : 'nullable|') . 'email';
            } elseif ($field->field_type === 'number') {
                $rules[$fieldKey] = ($field->required ? 'required|' : 'nullable|') . 'numeric';
            } elseif ($field->field_type === 'price') {
                $rules[$fieldKey] = ($field->required ? 'required|' : 'nullable|') . 'numeric|min:0';
            } elseif ($field->field_type === 'checkbox') {
                $rules[$fieldKey] = ($field->required ? 'required|array' : 'nullable|array');
            } elseif (in_array($field->field_type, ['file', 'image', 'video']) && $field->required) {
                $rules["fields.{$field->id}.file"] = 'required|array|min:1';
                $rules["fields.{$field->id}.file.*"] = 'file|max:10240';
            } else {
                $rules[$fieldKey] = ($field->required ? 'required|' : 'nullable|') . 'string';
            }
        }

        $validated = $request->validate($rules);

        // Unique field validation
        foreach ($form->fields as $field) {
            if (!($field->settings['unique'] ?? false)) {
                continue;
            }
            $value = $validated['fields'][$field->id]['value'] ?? null;
            if ($value === null || $value === '') {
                continue;
            }
            $exists = SubmissionData::where('field_id', $field->id)
                ->where('value', $value)
                ->exists();
            if ($exists) {
                return redirect()->back()
                    ->withErrors(["fields.{$field->id}.value" => "The value for field '{$field->label}' must be unique. This value is already in use."])
                    ->withInput();
            }
        }

        $fieldMap = $form->fields->keyBy('id');

        $submission = DB::transaction(function () use ($form, $validated, $request, $fieldMap) {
            $submission = $form->submissions()->create([
                'user_id' => null,
                'company_id' => null,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'submitted_at' => now(),
                'status' => 'pending',
                'metadata' => ['source' => 'public_link'],
            ]);

            foreach ($validated['fields'] as $fieldData) {
                $field = $fieldMap->get($fieldData['field_id']);
                if (!$field) {
                    continue;
                }

                $value = $fieldData['value'] ?? null;
                $fileData = null;

                if (in_array($field->field_type, ['file', 'image', 'video']) && $request->hasFile("fields.{$field->id}.file")) {
                    $files = $request->file("fields.{$field->id}.file");

                    if (!is_array($files)) {
                        $files = [$files];
                    }

                    $storedFiles = [];
                    foreach ($files as $file) {
                        $path = $file->store("medical-submissions/{$submission->id}", 'local');
                        $storedFiles[] = [
                            'path' => $path,
                            'name' => $file->getClientOriginalName(),
                            'size' => $file->getSize(),
                            'mime_type' => $file->getMimeType(),
                            'type' => $file->getMimeType(),
                        ];
                    }

                    if (count($storedFiles) > 1) {
                        $fileData = $storedFiles;
                        $value = json_encode(array_column($storedFiles, 'path'));
                    } else {
                        $fileData = $storedFiles[0];
                        $value = $storedFiles[0]['path'];
                    }
                }

                if ($field->field_type === 'checkbox' && is_array($value)) {
                    $value = json_encode($value);
                }

                SubmissionData::create([
                    'submission_id' => $submission->id,
                    'field_id' => $field->id,
                    'value' => $value,
                    'file_data' => $fileData,
                ]);
            }

            return $submission;
        });

        // Eager load relations for emails / webhook payload
        $submission->load(['submissionData.field', 'user']);

        // 1) Notify form owner
        try {
            Mail::to($form->user->email)->queue(new FormSubmissionNotification($form, $submission));
        } catch (\Throwable $e) {
            Log::error('Failed to send owner notification', ['error' => $e->getMessage()]);
        }

        // 2) Autoresponder to submitter (if any field of type "email" exists)
        $submitterEmail = null;
        foreach ($submission->submissionData as $data) {
            if ($data->field && $data->field->field_type === 'email' && filter_var($data->value, FILTER_VALIDATE_EMAIL)) {
                $submitterEmail = $data->value;
                break;
            }
        }

        if ($submitterEmail) {
            try {
                Mail::to($submitterEmail)->queue(new SubmissionAutoresponder($form, $submission));
            } catch (\Throwable $e) {
                Log::error('Failed to send autoresponder', ['error' => $e->getMessage()]);
            }
        }

        // 3) Dispatch event (will trigger webhook job)
        FormSubmissionReceived::dispatch($form, $submission);

        return redirect()->back()->with('success', $form->thank_you_message ?? 'Form submitted successfully');
    }

    public function destroy(Form $form, FormSubmission $submission)
    {
        Gate::authorize('delete', $form);
        $submission->delete();
        return redirect()->route('submissions.index', $form)->with('success', 'Submission deleted successfully');
    }

    public function attachment(Form $form, FormSubmission $submission, SubmissionData $data)
    {
        Gate::authorize('view', $form);

        if ($data->submission_id !== $submission->id || !$data->file_data) {
            abort(404);
        }

        $path = $data->file_data['path'] ?? $data->value;
        if (!$path || !Storage::disk('local')->exists($path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('local')->download(
            $path,
            $data->file_data['name'] ?? basename($path)
        );
    }

    public function export(Form $form, $format)
    {
        Gate::authorize('view', $form);

        $submissions = $form->submissions()->with('submissionData.field')->get();
        $fields = $form->fields()->orderBy('order')->get();

        if ($format === 'csv') {
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $form->title . '-submissions.csv"',
            ];

            $callback = function () use ($submissions, $fields) {
                $file = fopen('php://output', 'w');
                
                // Add BOM for UTF-8
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                // Header row
                $header = ['ID', 'Submitted At', 'User', 'IP'];
                foreach ($fields as $field) {
                    $header[] = $field->label;
                }
                fputcsv($file, $header);

                // Data rows
                foreach ($submissions as $submission) {
                    $row = [
                        $submission->id,
                        $submission->submitted_at->format('Y-m-d H:i'),
                        $submission->user ? $submission->user->name : 'Guest',
                        $submission->ip_address,
                    ];

                    foreach ($fields as $field) {
                        $data = $submission->submissionData->where('field_id', $field->id)->first();
                        if ($data) {
                            if ($field->field_type === 'checkbox' && $data->value) {
                                $row[] = implode(', ', json_decode($data->value));
                            } elseif ($field->field_type === 'file' && $data->file_data) {
                                $row[] = $data->file_data['name'];
                            } else {
                                $row[] = $data->value;
                            }
                        } else {
                            $row[] = '';
                        }
                    }

                    fputcsv($file, $row);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return response()->json(['message' => 'Export format not currently supported']);
    }
}
