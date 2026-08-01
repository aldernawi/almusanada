<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" dir="ltr">
        @isset($form)
            {{-- Submissions view for a specific form --}}
            <div class="main-card mb-6 p-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('medical-auditing.index') }}" class="w-11 h-11 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-colors" title="Back to forms">
                        <i class="fas fa-arrow-left text-slate-600"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-extrabold text-slate-800">{{ $form->title }}</h1>
                        <p class="text-sm text-slate-500 mt-1">Audit submissions for this form</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="bg-slate-100 text-slate-700 px-4 py-2 rounded-xl text-sm font-bold">{{ $submissions->total() }} submissions</span>
                    @php
                        $exportParams = array_filter(request()->only(['search', 'form_id', 'status', 'date_from', 'date_to']));
                        $exportQuery = $exportParams ? '?' . http_build_query($exportParams) : '';
                    @endphp
                    <a href="{{ route('medical-auditing.export', ['format' => 'csv']) }}{{ $exportQuery }}" class="bg-slate-900 hover:bg-blue-600 text-white px-3 py-2 rounded-xl text-sm font-bold flex items-center gap-1.5 transition duration-300 hover:shadow-lg hover:shadow-blue-500/20">
                        <i class="fas fa-file-csv"></i> CSV
                    </a>
                    <a href="{{ route('medical-auditing.export', ['format' => 'excel']) }}{{ $exportQuery }}" class="bg-slate-900 hover:bg-blue-600 text-white px-3 py-2 rounded-xl text-sm font-bold flex items-center gap-1.5 transition duration-300 hover:shadow-lg hover:shadow-blue-500/20">
                        <i class="fas fa-file-excel"></i> Excel
                    </a>
                </div>
            </div>

            {{-- Status summary cards --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                <div class="main-card p-5 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center"><i class="fas fa-list text-lg"></i></div>
                    <div><div class="text-2xl font-extrabold text-slate-800">{{ $counts['total'] }}</div><div class="text-xs text-slate-500 font-bold mt-0.5">Total</div></div>
                </div>
                <div class="main-card p-5 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center"><i class="fas fa-clock text-lg"></i></div>
                    <div><div class="text-2xl font-extrabold text-slate-800">{{ $counts['pending'] }}</div><div class="text-xs text-slate-500 font-bold mt-0.5">Pending</div></div>
                </div>
                <div class="main-card p-5 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center"><i class="fas fa-check text-lg"></i></div>
                    <div><div class="text-2xl font-extrabold text-slate-800">{{ $counts['approved'] }}</div><div class="text-xs text-slate-500 font-bold mt-0.5">Approved</div></div>
                </div>
                <div class="main-card p-5 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center"><i class="fas fa-times text-lg"></i></div>
                    <div><div class="text-2xl font-extrabold text-slate-800">{{ $counts['rejected'] }}</div><div class="text-xs text-slate-500 font-bold mt-0.5">Rejected</div></div>
                </div>
            </div>

            <div id="flashMessage" class="hidden mb-4 p-4 rounded-xl text-sm font-bold"></div>

            {{-- Filters --}}
            <div class="main-card p-5 mb-6">
                <form method="GET" action="{{ route('medical-auditing.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-3">
                    <input type="number" min="1" name="search" value="{{ request('search') }}" placeholder="Submission ID" class="text-sm border-slate-200 rounded-xl px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                    <input type="hidden" name="form_id" value="{{ $form->id }}">
                    <select name="status" class="text-sm border-slate-200 rounded-xl px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                        <option value="">All statuses</option>
                        <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                        <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                        <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                    </select>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="text-sm border-slate-200 rounded-xl px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                    <div class="flex gap-2">
                        <button class="flex-1 bg-slate-900 hover:bg-blue-600 text-white rounded-xl text-sm font-bold transition duration-300 hover:shadow-lg hover:shadow-blue-500/20 px-4 py-2.5">Apply</button>
                        <a href="{{ route('medical-auditing.index', ['form_id' => $form->id]) }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-bold transition">Clear</a>
                    </div>
                </form>
            </div>

            {{-- Submissions table --}}
            <div class="main-card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-5 py-4 text-left text-xs font-bold text-slate-500">Submission</th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-slate-500">Submitted At</th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-slate-500">Status</th>
                                <th class="px-5 py-4 text-center text-xs font-bold text-slate-500">Approve</th>
                                <th class="px-5 py-4 text-center text-xs font-bold text-slate-500">Reject</th>
                                <th class="px-5 py-4 text-center text-xs font-bold text-slate-500">View</th>
                                <th class="px-5 py-4 text-center text-xs font-bold text-slate-500">Attachments</th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-slate-500">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($submissions as $submission)
                                @php
                                    $attachments = $submission->submissionData->filter(fn ($d) => $d->file_data !== null);
                                    $hasAttachments = $attachments->isNotEmpty();
                                    $isApproved = $submission->status === 'approved';
                                    $isRejected = $submission->status === 'rejected';
                                    $isPending = $submission->status === 'pending';
                                @endphp
                                <tr id="submission-row-{{ $submission->id }}" class="hover:bg-slate-50/50 transition">
                                    <td class="px-5 py-4 text-sm font-extrabold text-slate-800">#{{ $submission->id }}</td>
                                    <td class="px-5 py-4 text-sm text-slate-500">{{ $submission->submitted_at?->format('Y-m-d H:i') }}</td>
                                    <td class="px-5 py-4">
                                        <span id="status-{{ $submission->id }}" class="inline-flex rounded-lg px-2.5 py-1 text-xs font-bold {{ $isApproved ? 'bg-emerald-100 text-emerald-700' : ($isRejected ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                                            {{ ['approved' => 'Approved', 'rejected' => 'Rejected', 'pending' => 'Pending'][$submission->status] ?? $submission->status }}
                                        </span>
                                    </td>
                                    {{-- Approve Column --}}
                                    <td class="px-5 py-4 text-center">
                                        @if($isApproved)
                                            <span id="approve-check-{{ $submission->id }}" class="text-green-600" title="Approved">
                                                <i class="fas fa-check-circle text-xl"></i>
                                            </span>
                                        @elseif($isPending && !auth()->user()->isViewer())
                                            <button type="button" id="approve-btn-{{ $submission->id }}" onclick="openApprove({{ $submission->id }})" title="Approve" class="w-9 h-9 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-600 flex items-center justify-center transition-colors mx-auto">
                                                <i class="fas fa-check text-sm"></i>
                                            </button>
                                        @else
                                            <span class="text-gray-300"><i class="fas fa-minus text-sm"></i></span>
                                        @endif
                                    </td>
                                    {{-- Reject Column --}}
                                    <td class="px-5 py-4 text-center">
                                        @if($isRejected)
                                            <span class="text-red-600" title="Rejected">
                                                <i class="fas fa-times-circle text-xl"></i>
                                            </span>
                                        @elseif($isPending && !auth()->user()->isViewer())
                                            <button type="button" id="reject-btn-{{ $submission->id }}" onclick="openReject({{ $submission->id }})" title="Reject" class="w-9 h-9 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center transition-colors mx-auto">
                                                <i class="fas fa-times text-sm"></i>
                                            </button>
                                        @else
                                            <span class="text-gray-300"><i class="fas fa-minus text-sm"></i></span>
                                        @endif
                                    </td>
                                    {{-- View Details Column --}}
                                    <td class="px-5 py-4 text-center">
                                        <button type="button" onclick="openAudit({{ $submission->id }})" title="View details" class="w-9 h-9 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors mx-auto">
                                            <i class="fas fa-eye text-sm"></i>
                                        </button>
                                    </td>
                                    {{-- Attachments Column --}}
                                    <td class="px-5 py-4 text-center">
                                        @if($hasAttachments)
                                            @foreach($attachments as $attachment)
                                                <button type="button" onclick="openPdfPopup('{{ route('medical-auditing.attachments.inline', [$submission, $attachment]) }}', '{{ $attachment->file_data['name'] ?? 'Attachment' }}', '{{ route('medical-auditing.attachments.show', [$submission, $attachment]) }}')" title="View attachment: {{ $attachment->file_data['name'] ?? 'Attachment' }}" class="w-9 h-9 rounded-lg bg-purple-50 hover:bg-purple-100 text-purple-600 flex items-center justify-center transition-colors mx-auto">
                                                    <i class="fas fa-paperclip text-sm"></i>
                                                </button>
                                            @endforeach
                                        @else
                                            <span class="text-gray-300"><i class="fas fa-minus text-sm"></i></span>
                                        @endif
                                    </td>
                                    {{-- Notes Column --}}
                                    <td class="px-5 py-4 text-left max-w-xs">
                                        @if(auth()->user()->isViewer())
                                            <p class="text-xs text-slate-500 whitespace-pre-wrap break-words">{{ $submission->review_notes ?: '—' }}</p>
                                        @else
                                            <textarea rows="2" maxlength="2000"
                                                class="w-full text-xs text-slate-600 border border-slate-200 rounded-lg p-2 resize-y focus:border-blue-500 focus:ring-2 focus:ring-blue-100 focus:outline-none transition"
                                                placeholder="Write a note..."
                                                onblur="saveNotes({{ $submission->id }}, this.value)"
                                            >{{ $submission->review_notes }}</textarea>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="px-6 py-16 text-center text-slate-400">No matching submissions.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($submissions->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">{{ $submissions->links() }}</div>
                @endif
            </div>
        @else
            {{-- Forms list view --}}
            <div class="main-card mb-6 p-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-800">Medical Audit Dashboard</h1>
                    <p class="text-sm text-slate-500 mt-1">Select a form to view its submissions</p>
                </div>
                <span class="bg-slate-100 text-slate-700 px-4 py-2 rounded-xl text-sm font-bold">{{ $forms->count() }} authorized forms</span>
            </div>

            <div id="flashMessage" class="hidden mb-4 p-4 rounded-xl text-sm font-bold"></div>

            @if($forms->isEmpty())
                <div class="main-card p-16 text-center">
                    <i class="fas fa-folder-open text-5xl text-slate-200 mb-4"></i>
                    <p class="text-slate-500 font-bold">No forms are assigned to you yet</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($forms as $form)
                        <a href="{{ route('medical-auditing.index', ['form_id' => $form->id]) }}" class="main-card p-6 hover:shadow-lg hover:border-blue-200 transition-all duration-300 card-lift group">
                            <div class="flex items-start justify-between mb-4">
                                <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors">
                                    <i class="fas fa-file-medical text-xl"></i>
                                </div>
                                <span class="bg-slate-100 text-slate-600 text-xs px-2.5 py-1 rounded-full font-bold">{{ $form->total_submissions }} submissions</span>
                            </div>
                            <h3 class="font-extrabold text-slate-800 text-lg mb-1 group-hover:text-blue-600 transition-colors">{{ $form->title }}</h3>
                            <p class="text-sm text-slate-400 line-clamp-2 mb-4">{{ $form->description ?? 'No description' }}</p>
                            <div class="flex items-center gap-2 text-xs font-bold">
                                @if($form->pending_count > 0)
                                    <span class="bg-amber-50 text-amber-700 px-2.5 py-1 rounded-lg">{{ $form->pending_count }} Pending</span>
                                @endif
                                @if($form->approved_count > 0)
                                    <span class="bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-lg">{{ $form->approved_count }} Approved</span>
                                @endif
                                @if($form->rejected_count > 0)
                                    <span class="bg-red-50 text-red-700 px-2.5 py-1 rounded-lg">{{ $form->rejected_count }} Rejected</span>
                                @endif
                            </div>
                            <div class="mt-4 flex items-center text-blue-600 text-sm font-bold gap-1.5">
                                <span>View submissions</span>
                                <i class="fas fa-arrow-right text-xs"></i>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        @endisset
    </div>

    <div id="auditModal" class="hidden fixed inset-0 z-50 bg-slate-950/60 p-4 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="min-h-full flex items-center justify-center">
            <div class="bg-white w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden" dir="ltr">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div><h2 id="modalTitle" class="font-extrabold text-xl text-slate-800">Submission Details</h2><p id="modalMeta" class="text-xs text-slate-500 mt-1"></p></div>
                    <button type="button" onclick="closeAudit()" class="w-10 h-10 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 text-xl transition">&times;</button>
                </div>
                <div id="modalLoading" class="p-16 text-center text-slate-400">Loading submission data...</div>
                <div id="modalContent" class="hidden">
                    <div id="fieldsContainer" class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[55vh] overflow-y-auto"></div>
                    <div class="p-6 bg-slate-50 border-t border-slate-100">
                        <div id="modalError" class="hidden text-red-600 text-sm font-bold mb-2"></div>
                        @if(auth()->user()->isViewer())
                            <p class="text-center text-sm text-slate-400 font-bold">You have view-only access to this submission.</p>
                        @else
                            <div class="flex flex-col-reverse sm:flex-row gap-3">
                                <button type="button" onclick="submitDecision('rejected')" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl font-bold transition">Reject</button>
                                <button type="button" onclick="submitDecision('approved')" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white py-3 rounded-xl font-bold transition">Approve</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Approve Modal --}}
    <div id="approveModal" class="hidden fixed inset-0 z-50 bg-slate-950/60 p-4 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="min-h-full flex items-center justify-center">
            <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden" dir="ltr">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="font-extrabold text-xl text-emerald-600">Approve</h2>
                    <button type="button" onclick="closeApprove()" class="w-10 h-10 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 text-xl transition">&times;</button>
                </div>
                <div class="p-6">
                    <p class="text-sm text-slate-500 mb-3">Submission #<span id="approveSubmissionId"></span></p>
                    <p class="text-sm text-slate-600 mb-4">Are you sure this submission is approved?</p>
                    <div id="approveError" class="hidden text-red-600 text-sm font-bold mt-2"></div>
                    <button type="button" onclick="submitApprove()" class="w-full mt-4 bg-emerald-600 text-white py-3 rounded-xl font-bold hover:bg-emerald-700 transition-colors">Confirm Approval</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Reject Modal --}}
    <div id="rejectModal" class="hidden fixed inset-0 z-50 bg-slate-950/60 p-4 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="min-h-full flex items-center justify-center">
            <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden" dir="ltr">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="font-extrabold text-xl text-red-600">Reject</h2>
                    <button type="button" onclick="closeReject()" class="w-10 h-10 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 text-xl transition">&times;</button>
                </div>
                <div class="p-6">
                    <p class="text-sm text-slate-500 mb-3">Submission #<span id="rejectSubmissionId"></span></p>
                    <p class="text-sm text-slate-600 mb-4">Are you sure this submission is rejected?</p>
                    <div id="rejectError" class="hidden text-red-600 text-sm font-bold mt-2"></div>
                    <button type="button" onclick="submitReject()" class="w-full mt-4 bg-red-600 text-white py-3 rounded-xl font-bold hover:bg-red-700 transition-colors">Confirm Rejection</button>
                </div>
            </div>
        </div>
    </div>

    {{-- PDF Popup Modal --}}
    <div id="pdfModal" class="hidden fixed inset-0 z-50 bg-slate-950/80 p-4" role="dialog" aria-modal="true">
        <div class="h-full flex flex-col max-w-5xl mx-auto">
            <div class="bg-white rounded-t-2xl px-6 py-3 flex items-center justify-between border-b border-slate-100">
                <h2 id="pdfTitle" class="font-bold text-sm text-slate-700">View Attachment</h2>
                <div class="flex items-center gap-2">
                    <a id="pdfDownloadLink" href="#" download class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-1.5 rounded-lg text-xs font-bold flex items-center gap-1.5 transition">
                        <i class="fas fa-download"></i> Download
                    </a>
                    <button type="button" onclick="closePdfPopup()" class="w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 text-xl flex items-center justify-center transition">&times;</button>
                </div>
            </div>
            <div class="flex-1 bg-white rounded-b-2xl overflow-hidden">
                <iframe id="pdfFrame" src="" class="w-full h-full border-0" style="min-height: 70vh;"></iframe>
            </div>
        </div>
    </div>

    <script>
        let activeSubmissionId = null;
        const dataUrlTemplate = @json(route('medical-auditing.data', ['submission' => '__ID__']));
        const auditUrlTemplate = @json(route('medical-auditing.audit', ['submission' => '__ID__']));
        const notesUrlTemplate = @json(route('medical-auditing.notes', ['submission' => '__ID__']));
        const csrfToken = @json(csrf_token());

        function escapeHtml(value) {
            const element = document.createElement('div');
            element.textContent = value ?? '';
            return element.innerHTML;
        }

        function showFlash(message, type) {
            const flash = document.getElementById('flashMessage');
            flash.textContent = message;
            flash.className = `mb-4 p-4 rounded-xl text-sm font-bold ${type === 'error' ? 'bg-red-50 text-red-700' : 'bg-emerald-50 text-emerald-700'}`;
            setTimeout(() => flash.classList.add('hidden'), 4000);
            flash.classList.remove('hidden');
        }

        let saveNotesTimer = null;
        async function saveNotes(id, notes) {
            try {
                const response = await fetch(notesUrlTemplate.replace('__ID__', id), {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ notes: notes.trim() || null })
                });
                const payload = await response.json();
                if (!response.ok) throw new Error(payload.message || 'Failed to save notes');
                showFlash('Notes saved');
            } catch (error) {
                showFlash(error.message, 'error');
            }
        }

        function updateRowAfterDecision(id, status) {
            const badge = document.getElementById(`status-${id}`);
            const approveCell = document.getElementById(`approve-btn-${id}`)?.closest('td');
            const rejectCell = document.getElementById(`reject-btn-${id}`)?.closest('td');
            const notesCell = approveCell?.nextElementSibling?.nextElementSibling;

            if (status === 'approved') {
                badge.textContent = 'Approved';
                badge.className = 'inline-flex rounded-lg px-2.5 py-1 text-xs font-bold bg-emerald-100 text-emerald-700';
                if (approveCell) approveCell.innerHTML = `<span class="text-emerald-600" title="Approved"><i class="fas fa-check-circle text-xl"></i></span>`;
                if (rejectCell) rejectCell.innerHTML = `<span class="text-gray-300"><i class="fas fa-minus text-sm"></i></span>`;
            } else if (status === 'rejected') {
                badge.textContent = 'Rejected';
                badge.className = 'inline-flex rounded-lg px-2.5 py-1 text-xs font-bold bg-red-100 text-red-700';
                if (rejectCell) rejectCell.innerHTML = `<span class="text-red-600" title="Rejected"><i class="fas fa-times-circle text-xl"></i></span>`;
                if (approveCell) approveCell.innerHTML = `<span class="text-gray-300"><i class="fas fa-minus text-sm"></i></span>`;
            }
        }

        function openApprove(id) {
            activeSubmissionId = id;
            document.getElementById('approveSubmissionId').textContent = id;
            document.getElementById('approveError').classList.add('hidden');
            document.getElementById('approveModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeApprove() {
            document.getElementById('approveModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            activeSubmissionId = null;
        }

        async function submitApprove() {
            const errorBox = document.getElementById('approveError');
            errorBox.classList.add('hidden');
            try {
                const response = await fetch(auditUrlTemplate.replace('__ID__', activeSubmissionId), {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ status: 'approved' })
                });
                const payload = await response.json();
                if (!response.ok) throw new Error(payload.message || 'Failed to save decision');
                updateRowAfterDecision(activeSubmissionId, 'approved');
                closeApprove();
                showFlash(payload.message);
            } catch (error) {
                errorBox.textContent = error.message;
                errorBox.classList.remove('hidden');
            }
        }

        function openReject(id) {
            activeSubmissionId = id;
            document.getElementById('rejectSubmissionId').textContent = id;
            document.getElementById('rejectError').classList.add('hidden');
            document.getElementById('rejectModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeReject() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            activeSubmissionId = null;
        }

        async function submitReject() {
            const errorBox = document.getElementById('rejectError');
            errorBox.classList.add('hidden');
            try {
                const response = await fetch(auditUrlTemplate.replace('__ID__', activeSubmissionId), {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ status: 'rejected' })
                });
                const payload = await response.json();
                if (!response.ok) throw new Error(payload.message || 'Failed to save decision');
                updateRowAfterDecision(activeSubmissionId, 'rejected');
                closeReject();
                showFlash(payload.message);
            } catch (error) {
                errorBox.textContent = error.message;
                errorBox.classList.remove('hidden');
            }
        }

        function openPdfPopup(url, name, downloadUrl) {
            document.getElementById('pdfTitle').textContent = name;
            document.getElementById('pdfFrame').src = url;
            document.getElementById('pdfDownloadLink').href = downloadUrl;
            document.getElementById('pdfModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closePdfPopup() {
            document.getElementById('pdfModal').classList.add('hidden');
            document.getElementById('pdfFrame').src = '';
            document.body.classList.remove('overflow-hidden');
        }

        async function openAudit(id) {
            activeSubmissionId = id;
            document.getElementById('auditModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            document.getElementById('modalLoading').classList.remove('hidden');
            document.getElementById('modalContent').classList.add('hidden');
            document.getElementById('modalError').classList.add('hidden');
            try {
                const response = await fetch(dataUrlTemplate.replace('__ID__', id), { headers: { Accept: 'application/json' } });
                if (!response.ok) throw new Error('Failed to load submission');
                const payload = await response.json();
                const submission = payload.data;
                document.getElementById('modalTitle').textContent = `Submission #${submission.id} - ${submission.form_title}`;
                document.getElementById('modalMeta').textContent = `Submitted at: ${submission.submitted_at ?? '-'}`;
                document.getElementById('fieldsContainer').innerHTML = submission.fields.map(field => {
                    let content;
                    if (field.file) {
                        content = `<a href="${escapeHtml(field.file.url)}" target="_blank" class="inline-flex items-center gap-2 text-blue-600 font-bold hover:underline">Download ${escapeHtml(field.file.name)} <span class="text-xs text-slate-400">(${Math.ceil(field.file.size / 1024)} KB)</span></a>`;
                    } else {
                        let value = field.value ?? '—';
                        if (field.type === 'checkbox') {
                            try { value = JSON.parse(value).join(', '); } catch (_) {}
                        }
                        content = `<p class="mt-1 text-slate-800 whitespace-pre-wrap break-words">${escapeHtml(value)}</p>`;
                    }
                    return `<div class="border border-slate-100 rounded-xl p-4 bg-slate-50"><span class="text-xs font-bold text-slate-500">${escapeHtml(field.label)}</span>${content}</div>`;
                }).join('');
                document.getElementById('modalLoading').classList.add('hidden');
                document.getElementById('modalContent').classList.remove('hidden');
            } catch (error) {
                document.getElementById('modalLoading').textContent = error.message;
            }
        }

        function closeAudit() {
            document.getElementById('auditModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            activeSubmissionId = null;
        }

        async function submitDecision(status) {
            const errorBox = document.getElementById('modalError');
            errorBox.classList.add('hidden');
            try {
                const response = await fetch(auditUrlTemplate.replace('__ID__', activeSubmissionId), {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ status })
                });
                const payload = await response.json();
                if (!response.ok) throw new Error(payload.message || Object.values(payload.errors || {})[0]?.[0] || 'Failed to save decision');
                updateRowAfterDecision(activeSubmissionId, status);
                closeAudit();
                showFlash(payload.message);
            } catch (error) {
                errorBox.textContent = error.message;
                errorBox.classList.remove('hidden');
            }
        }

        document.addEventListener('keydown', event => {
            if (event.key === 'Escape') { closeAudit(); closeApprove(); closeReject(); closePdfPopup(); }
        });
    </script>
</x-app-layout>
