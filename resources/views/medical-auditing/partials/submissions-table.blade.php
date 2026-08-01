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
                {{ $statusLabels[$submission->status] ?? $submission->status }}
            </span>
        </td>
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
        <td class="px-5 py-4 text-center">
            <button type="button" onclick="openAudit({{ $submission->id }})" title="View details" class="w-9 h-9 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors mx-auto">
                <i class="fas fa-eye text-sm"></i>
            </button>
        </td>
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
