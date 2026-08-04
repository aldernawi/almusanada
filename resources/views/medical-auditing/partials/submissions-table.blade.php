@php
    $tableColumnCount = 7 + ($fields->count() ?? 0);
@endphp

@forelse($submissions as $submission)
    @php
        $rowNumber = $submissions instanceof \Illuminate\Contracts\Pagination\Paginator
            ? $submissions->firstItem() + $loop->index
            : $loop->iteration;
        $dataByField = $submission->submissionData->keyBy('field_id');
        $attachments = $submission->submissionData->filter(fn ($d) => $d->file_data !== null);
        $hasAttachments = $attachments->isNotEmpty();
        $isApproved = $submission->status === 'approved';
        $isRejected = $submission->status === 'rejected';
        $isPending = $submission->status === 'pending';
    @endphp
    <tr id="submission-row-{{ $submission->id }}" class="hover:bg-slate-50/50 transition align-top">
        <td class="px-5 py-4 text-sm font-extrabold text-slate-800">{{ $rowNumber }}</td>
        <td class="px-5 py-4 text-sm text-slate-500 whitespace-nowrap">{{ $submission->submitted_at?->format('Y-m-d H:i') }}</td>

        @foreach($fields as $field)
            @php
                $data = $dataByField->get($field->id);
                $fileItems = [];
                if ($data?->file_data) {
                    $fileItems = array_is_list($data->file_data) ? $data->file_data : [$data->file_data];
                }

                $displayValue = $data?->value;
                if ($field->field_type === 'checkbox' && $displayValue) {
                    $decoded = json_decode($displayValue, true);
                    $displayValue = is_array($decoded) ? implode(', ', $decoded) : $displayValue;
                }
            @endphp
            <td class="px-5 py-4 text-sm text-slate-600 min-w-44 max-w-xs">
                @if($fileItems)
                    <div class="space-y-1">
                        @foreach($fileItems as $file)
                            <div class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 px-2 py-1 text-xs font-bold text-slate-600">
                                <i class="fas fa-paperclip text-slate-400"></i>
                                <span class="break-all">{{ $file['name'] ?? 'Attachment' }}</span>
                            </div>
                        @endforeach
                    </div>
                @elseif($displayValue !== null && $displayValue !== '')
                    <div class="whitespace-pre-wrap break-words">{{ $displayValue }}</div>
                @else
                    <span class="text-slate-300">-</span>
                @endif
            </td>
        @endforeach

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
            @if($hasAttachments)
                <div class="flex flex-wrap justify-center gap-1.5">
                    @foreach($attachments as $attachment)
                        @php
                            $files = array_is_list($attachment->file_data) ? $attachment->file_data : [$attachment->file_data];
                        @endphp
                        @foreach($files as $fileIndex => $file)
                            @php
                                $inlineUrl = route('medical-auditing.attachments.inline', [$submission, $attachment]) . '?file=' . $fileIndex;
                                $downloadUrl = route('medical-auditing.attachments.show', [$submission, $attachment]) . '?file=' . $fileIndex;
                                $fileName = $file['name'] ?? 'Attachment';
                            @endphp
                            <button type="button" onclick="openPdfPopup(@js($inlineUrl), @js($fileName), @js($downloadUrl))" title="View attachment: {{ $fileName }}" class="w-9 h-9 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 flex items-center justify-center transition-colors">
                                <i class="fas fa-paperclip text-sm"></i>
                            </button>
                        @endforeach
                    @endforeach
                </div>
            @else
                <span class="text-gray-300"><i class="fas fa-minus text-sm"></i></span>
            @endif
        </td>
        <td class="px-5 py-4 text-left min-w-60 max-w-sm">
            @if(auth()->user()->isViewer())
                <p class="text-xs text-slate-500 whitespace-pre-wrap break-words">{{ $submission->review_notes ?: '-' }}</p>
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
    <tr><td colspan="{{ $tableColumnCount }}" class="px-6 py-16 text-center text-slate-400">No matching submissions.</td></tr>
@endforelse
