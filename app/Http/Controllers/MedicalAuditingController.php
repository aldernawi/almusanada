<?php

namespace App\Http\Controllers;

use App\Events\MedicalAuditCompleted;
use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\SubmissionData;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class MedicalAuditingController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $this->ensureAuditor($user);

        $formIds = $this->accessibleFormIds($user);

        if ($request->filled('form_id')) {
            $formId = $request->integer('form_id');
            $form = Form::with('fields')->findOrFail($formId);
            $this->authorizeSubmission(FormSubmission::make()->setAttribute('form_id', $formId));

            $query = $this->submissionsQuery($formIds)->where('form_id', $formId);

            $this->applyFilters($query, $request, 'search');

            $submissions = $query->paginate(30)->withQueryString();
            $fields = $form->fields;

            $counts = [
                'total' => FormSubmission::where('form_id', $formId)->count(),
                'pending' => FormSubmission::where('form_id', $formId)->where('status', 'pending')->count(),
                'approved' => FormSubmission::where('form_id', $formId)->where('status', 'approved')->count(),
                'rejected' => FormSubmission::where('form_id', $formId)->where('status', 'rejected')->count(),
            ];

            return view('medical-auditing.index', compact('submissions', 'form', 'counts', 'fields'));
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

    public function formSubmissions(Request $request, Form $form, ?FormSubmission $submission = null)
    {
        $request->merge(['form_id' => $form->id]);

        return $this->index($request);
    }

    public function search(Request $request)
    {
        $user = Auth::user();
        $this->ensureAuditor($user);

        $formIds = $this->accessibleFormIds($user);

        $query = $this->submissionsQuery($formIds);
        $fields = collect();

        if ($request->filled('form_id')) {
            $form = Form::with('fields')->findOrFail($request->integer('form_id'));
            $this->authorizeSubmission(FormSubmission::make()->setAttribute('form_id', $form->id));
            $query->where('form_id', $form->id);
            $fields = $form->fields;
        }

        $this->applyFilters($query, $request, 'q');

        $submissions = $query->paginate(30)->withQueryString();

        $statusLabels = ['approved' => 'Approved', 'rejected' => 'Rejected', 'pending' => 'Pending'];

        return response()->json([
            'success' => true,
            'html' => view('medical-auditing.partials.submissions-table', compact('submissions', 'statusLabels', 'fields'))->render(),
            'pagination' => (string) $submissions->links(),
        ]);
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
        $this->ensureNotViewer();

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
        $this->ensureNotViewer();

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

        $fileData = $this->attachmentPayload($data);
        [$disk, $path] = $this->attachmentDiskAndPath($data, $fileData);
        if (!$path || !Storage::disk($disk)->exists($path)) {
            abort(404, 'File not found');
        }

        return Storage::disk($disk)->download(
            $path,
            $fileData['name'] ?? basename($path)
        );
    }

    public function attachmentInline(FormSubmission $submission, SubmissionData $data)
    {
        $this->authorizeSubmission($submission);

        if ($data->submission_id !== $submission->id || !$data->file_data) {
            abort(404);
        }

        $fileData = $this->attachmentPayload($data);
        [$disk, $path] = $this->attachmentDiskAndPath($data, $fileData);
        if (!$path || !Storage::disk($disk)->exists($path)) {
            abort(404, 'File not found');
        }

        $mimeType = $fileData['mime_type']
            ?? $fileData['type']
            ?? Storage::disk($disk)->mimeType($path);

        return response(Storage::disk($disk)->get($path), 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . ($fileData['name'] ?? basename($path)) . '"',
        ]);
    }

    public function export(Request $request, string $format)
    {
        $user = Auth::user();
        $this->ensureAuditor($user);

        $formIds = $this->accessibleFormIds($user);

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
            $this->applySearch($query, trim($request->string('search')));
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
        if (!$user || !$user->canViewAuditing()) {
            abort(403, 'You are not authorized to access this page');
        }
    }

    private function authorizeSubmission(FormSubmission $submission): void
    {
        $user = Auth::user();
        $this->ensureAuditor($user);

        if (!$this->canSeeAllSubmissions($user) && !$user->assignedForms()->whereKey($submission->form_id)->exists()) {
            abort(403, 'This submission is not assigned to you');
        }
    }

    private function accessibleFormIds(User $user)
    {
        return $this->canSeeAllSubmissions($user)
            ? Form::query()->pluck('id')
            : $user->assignedForms()->pluck('forms.id');
    }

    private function canSeeAllSubmissions(User $user): bool
    {
        return $user->isAdmin() || ($user->isReviewer() && (bool) $user->can_view_all_transactions);
    }

    private function ensureNotViewer(): void
    {
        if (Auth::user()->isViewer()) {
            abort(403, 'Viewers can only view submissions and cannot perform actions.');
        }
    }

    private function attachmentPayload(SubmissionData $data): array
    {
        $fileData = $data->file_data;

        if (is_array($fileData) && array_is_list($fileData)) {
            $fileData = $fileData[request()->integer('file', 0)] ?? null;
        }

        return is_array($fileData) ? $fileData : [];
    }

    private function attachmentDiskAndPath(SubmissionData $data, array $fileData): array
    {
        $path = $fileData['path'] ?? $data->value;
        $disk = $fileData['disk'] ?? 'local';

        if ($path && !isset($fileData['disk']) && Storage::disk('public')->exists($path)) {
            $disk = 'public';
        }

        return [$disk, $path];
    }

    private function submissionsQuery($formIds): Builder
    {
        return FormSubmission::query()
            ->with([
                'form:id,title',
                'reviewer:id,name',
                'submissionData:id,submission_id,field_id,value,file_data',
                'submissionData.field:id,label,field_type,order',
            ])
            ->whereIn('form_id', $formIds)
            ->latest('submitted_at')
            ->latest('id');
    }

    private function applyFilters(Builder $query, Request $request, string $searchKey): void
    {
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled($searchKey)) {
            $this->applySearch($query, trim($request->string($searchKey)));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('submitted_at', '>=', $request->date('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('submitted_at', '<=', $request->date('date_to'));
        }
    }

    /**
     * Apply a search filter across submission ID, status, notes, field values,
     * reviewer name, and form title. Uses MySQL FULLTEXT (MATCH...AGAINST) for
     * the large `submission_data.value` and `review_notes` columns so search
     * stays fast even with millions of rows (avoids a LIKE '%..%' table scan).
     * Falls back to LIKE for non-MySQL connections (e.g. sqlite in tests).
     */
    private function applySearch(Builder $query, string $search): void
    {
        if ($search === '') {
            return;
        }

        $isMysql = Schema::getConnection()->getDriverName() === 'mysql';
        $booleanTerm = $this->toBooleanSearchTerm($search);

        $query->where(function (Builder $q) use ($search, $booleanTerm, $isMysql) {
            if (ctype_digit($search)) {
                $q->orWhere('id', (int) $search);
            }

            $q->orWhere('status', 'LIKE', "%{$search}%");

            if ($isMysql && $booleanTerm !== '') {
                $q->orWhereRaw('MATCH(review_notes) AGAINST (? IN BOOLEAN MODE)', [$booleanTerm]);
            } else {
                $q->orWhere('review_notes', 'LIKE', "%{$search}%");
            }

            $q->orWhereHas('submissionData', function (Builder $sq) use ($search, $booleanTerm, $isMysql) {
                if ($isMysql && $booleanTerm !== '') {
                    $sq->whereRaw('MATCH(value) AGAINST (? IN BOOLEAN MODE)', [$booleanTerm]);
                } else {
                    $sq->where('value', 'LIKE', "%{$search}%");
                }
            });

            $q->orWhereHas('reviewer', function (Builder $rq) use ($search) {
                $rq->where('name', 'LIKE', "%{$search}%");
            });

            $q->orWhereHas('form', function (Builder $fq) use ($search) {
                $fq->where('title', 'LIKE', "%{$search}%");
            });
        });
    }

    /**
     * Convert a free-text search phrase into a MySQL BOOLEAN MODE fulltext
     * expression requiring each word as a prefix match, e.g.
     * "john smith" -> "+john* +smith*". Words shorter than the FULLTEXT
     * minimum length (3 chars) are dropped since MySQL wouldn't index them
     * anyway; the LIKE fallback columns still cover short terms.
     */
    private function toBooleanSearchTerm(string $search): string
    {
        $words = preg_split('/\s+/', trim($search));

        $terms = array_filter(array_map(function ($word) {
            $clean = preg_replace('/[+\-<>()~*"@]/', '', $word);
            if ($clean === '' || mb_strlen($clean) < 3) {
                return null;
            }
            return '+' . $clean . '*';
        }, $words));

        return implode(' ', $terms);
    }
}
