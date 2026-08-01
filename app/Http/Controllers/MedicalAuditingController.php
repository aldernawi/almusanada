<?php

namespace App\Http\Controllers;

use App\Events\MedicalAuditCompleted;
use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\SubmissionData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MedicalAuditingController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $this->ensureAuditor($user);

        $formIds = $user->isAdmin()
            ? Form::query()->pluck('id')
            : $user->assignedForms()->pluck('forms.id');

        if ($request->filled('form_id')) {
            $formId = $request->integer('form_id');
            $form = Form::findOrFail($formId);
            $this->authorizeSubmission(FormSubmission::make()->setAttribute('form_id', $formId));

            $query = FormSubmission::query()
                ->with(['form:id,title', 'reviewer:id,name', 'submissionData' => function ($q) {
                    $q->whereHas('field', fn ($f) => $f->where('field_type', 'file'))
                      ->whereNotNull('file_data');
                }])
                ->where('form_id', $formId)
                ->latest('submitted_at');

            if ($request->filled('status')) {
                $query->where('status', $request->string('status'));
            }

            if ($request->filled('search')) {
                $query->whereKey($request->integer('search'));
            }

            if ($request->filled('date_from')) {
                $query->whereDate('submitted_at', '>=', $request->date('date_from'));
            }

            if ($request->filled('date_to')) {
                $query->whereDate('submitted_at', '<=', $request->date('date_to'));
            }

            $submissions = $query->paginate(30)->withQueryString();

            $counts = [
                'total' => FormSubmission::where('form_id', $formId)->count(),
                'pending' => FormSubmission::where('form_id', $formId)->where('status', 'pending')->count(),
                'approved' => FormSubmission::where('form_id', $formId)->where('status', 'approved')->count(),
                'rejected' => FormSubmission::where('form_id', $formId)->where('status', 'rejected')->count(),
            ];

            return view('medical-auditing.index', compact('submissions', 'form', 'counts'));
        }

        $forms = Form::query()
            ->whereIn('id', $formIds)
            ->withCount([
                'submissions as total_submissions',
                'submissions as pending_count' => fn ($q) => $q->where('status', 'pending'),
                'submissions as approved_count' => fn ($q) => $q->where('status', 'approved'),
                'submissions as rejected_count' => fn ($q) => $q->where('status', 'rejected'),
            ])
            ->orderBy('title')
            ->get(['id', 'title', 'description']);

        return view('medical-auditing.index', compact('forms'));
    }

    public function data(FormSubmission $submission)
    {
        $this->authorizeSubmission($submission);
        $submission->load(['form:id,title', 'reviewer:id,name', 'submissionData.field']);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $submission->id,
                'status' => $submission->status,
                'submitted_at' => $submission->submitted_at?->format('Y-m-d H:i'),
                'reviewed_at' => $submission->reviewed_at?->format('Y-m-d H:i'),
                'review_notes' => $submission->review_notes,
                'reviewer_name' => $submission->reviewer?->name,
                'form_title' => $submission->form->title,
                'fields' => $submission->submissionData->map(function (SubmissionData $data) use ($submission) {
                    $isFile = $data->field?->field_type === 'file' && $data->file_data;

                    return [
                        'label' => $data->field?->label ?? 'Deleted field',
                        'type' => $data->field?->field_type ?? 'text',
                        'value' => $isFile ? null : $data->value,
                        'file' => $isFile ? [
                            'name' => $data->file_data['name'] ?? 'Attachment',
                            'size' => $data->file_data['size'] ?? 0,
                            'url' => route('medical-auditing.attachments.show', [$submission, $data]),
                        ] : null,
                    ];
                })->values(),
            ],
        ]);
    }

    public function audit(Request $request, FormSubmission $submission)
    {
        $this->authorizeSubmission($submission);

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'notes' => 'nullable|string|max:2000',
        ]);

        $submission->update([
            'status' => $validated['status'],
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        MedicalAuditCompleted::dispatch($submission->form, $submission, $validated['status'], Auth::user());

        return response()->json([
            'success' => true,
            'message' => 'Submission status updated successfully',
            'data' => [
                'status' => $submission->status,
                'reviewed_at' => $submission->reviewed_at->format('Y-m-d H:i'),
                'reviewer_name' => Auth::user()->name,
            ],
        ]);
    }

    public function updateNotes(Request $request, FormSubmission $submission)
    {
        $this->authorizeSubmission($submission);

        $validated = $request->validate([
            'notes' => 'nullable|string|max:2000',
        ]);

        $submission->update([
            'review_notes' => $validated['notes'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notes saved',
        ]);
    }

    public function attachment(FormSubmission $submission, SubmissionData $data)
    {
        $this->authorizeSubmission($submission);

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

    public function attachmentInline(FormSubmission $submission, SubmissionData $data)
    {
        $this->authorizeSubmission($submission);

        if ($data->submission_id !== $submission->id || !$data->file_data) {
            abort(404);
        }

        $path = $data->file_data['path'] ?? $data->value;
        if (!$path || !Storage::disk('local')->exists($path)) {
            abort(404, 'File not found');
        }

        $mimeType = $data->file_data['type'] ?? Storage::disk('local')->mimeType($path);

        return response(Storage::disk('local')->get($path), 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . ($data->file_data['name'] ?? basename($path)) . '"',
        ]);
    }

    public function export(Request $request, string $format)
    {
        $user = Auth::user();
        $this->ensureAuditor($user);

        $formIds = $user->isAdmin()
            ? Form::query()->pluck('id')
            : $user->assignedForms()->pluck('forms.id');

        $query = FormSubmission::query()
            ->with(['form:id,title', 'reviewer:id,name', 'submissionData.field'])
            ->whereIn('form_id', $formIds)
            ->latest('submitted_at');

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }
        if ($request->filled('form_id')) {
            $query->where('form_id', $request->integer('form_id'));
        }
        if ($request->filled('search')) {
            $query->whereKey($request->integer('search'));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('submitted_at', '>=', $request->date('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('submitted_at', '<=', $request->date('date_to'));
        }

        $submissions = $query->get();
        $statusLabels = ['approved' => 'Approved', 'rejected' => 'Rejected', 'pending' => 'Pending'];

        $headers = ['Submission ID', 'Form', 'Submitted At', 'Status', 'Reviewer', 'Reviewed At', 'Review Notes'];
        $rows = [];
        foreach ($submissions as $sub) {
            $rows[] = [
                $sub->id,
                $sub->form->title,
                $sub->submitted_at?->format('Y-m-d H:i'),
                $statusLabels[$sub->status] ?? $sub->status,
                $sub->reviewer?->name ?? '—',
                $sub->reviewed_at?->format('Y-m-d H:i') ?? '—',
                $sub->review_notes ?? '—',
            ];
        }

        $filename = 'medical-auditing-' . now()->format('Y-m-d');

        if ($format === 'csv') {
            return response()->stream(function () use ($headers, $rows) {
                $file = fopen('php://output', 'w');
                fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
                fputcsv($file, $headers);
                foreach ($rows as $row) {
                    fputcsv($file, $row);
                }
                fclose($file);
            }, 200, [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
            ]);
        }

        if ($format === 'excel') {
            $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" dir="ltr"><head><meta charset="UTF-8"></head><body>';
            $html .= '<table border="1"><thead><tr>';
            foreach ($headers as $h) {
                $html .= '<th style="background:#d1d5db;font-weight:bold;padding:8px;">' . htmlspecialchars($h) . '</th>';
            }
            $html .= '</tr></thead><tbody>';
            foreach ($rows as $row) {
                $html .= '<tr>';
                foreach ($row as $cell) {
                    $html .= '<td style="padding:6px;border:1px solid #e5e7eb;">' . htmlspecialchars($cell) . '</td>';
                }
                $html .= '</tr>';
            }
            $html .= '</tbody></table></body></html>';

            return response($html, 200, [
                'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '.xls"',
            ]);
        }

        abort(400, 'Unsupported format');
    }

    private function ensureAuditor($user): void
    {
        if (!$user || (!$user->isAdmin() && !$user->isReviewer())) {
            abort(403, 'You are not authorized to access this page');
        }
    }

    private function authorizeSubmission(FormSubmission $submission): void
    {
        $user = Auth::user();
        $this->ensureAuditor($user);

        if (!$user->isAdmin() && !$user->assignedForms()->whereKey($submission->form_id)->exists()) {
            abort(403, 'This submission is not assigned to you');
        }
    }
}
