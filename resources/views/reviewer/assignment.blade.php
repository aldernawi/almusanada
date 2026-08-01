<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                    <a href="{{ route('medical-auditing.index') }}" class="hover:text-blue-600 font-medium transition-colors">Auditing</a>
                    <i class="fas fa-chevron-right text-[10px] text-slate-400"></i>
                    <span>Reviewer Assignment</span>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-800">Reviewer Assignment</h1>
                <p class="text-slate-500 mt-1">Assign forms to reviewers to enable them to review submissions</p>
            </div>
            <a href="{{ route('medical-auditing.index') }}" class="bg-slate-100 text-slate-700 px-4 py-2.5 rounded-xl hover:bg-slate-200 transition font-bold text-sm flex items-center gap-1.5">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 px-6 py-4 rounded-xl mb-6 flex items-center gap-3">
                <i class="fas fa-check-circle text-lg"></i>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Forms Table -->
        <div class="main-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Form</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Submissions</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Assigned Reviewers</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        @forelse($forms as $form)
                            @php
                                $assignedIds = $form->reviewers->pluck('id')->toArray();
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                                            <i class="fas fa-file-medical text-slate-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800 text-sm">{{ $form->title }}</p>
                                            <p class="text-xs text-slate-400">{{ $form->description ? Str::limit($form->description, 40) : 'No description' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="bg-slate-100 text-slate-700 px-2.5 py-1 rounded-lg text-xs font-bold">{{ $form->submissions_count }} submissions</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($form->reviewers->count() > 0)
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach($form->reviewers as $reviewer)
                                                <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 px-2.5 py-1 rounded-lg text-xs font-medium border border-blue-100">
                                                    <div class="w-5 h-5 bg-blue-600 text-white rounded-full flex items-center justify-center text-[9px] font-bold">
                                                        {{ mb_substr($reviewer->name, 0, 1) }}
                                                    </div>
                                                    {{ $reviewer->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-slate-400 text-xs">Not assigned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button onclick="openAssignModal({{ $form->id }}, '{{ addslashes($form->title) }}', {{ json_encode($assignedIds) }})"
                                        class="bg-slate-900 hover:bg-blue-600 text-white px-4 py-2 rounded-xl hover:shadow-lg hover:shadow-blue-500/20 text-xs font-bold transition duration-300 inline-flex items-center gap-1.5">
                                        <i class="fas fa-user-plus"></i>
                                        Assign Reviewers
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <i class="fas fa-inbox text-4xl text-slate-200 mb-3 block"></i>
                                    <p class="text-slate-500 text-sm">No forms available</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Assignment Modal -->
    <div id="assignModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" onclick="closeAssignModal()"></div>
        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md">
            <div class="bg-white rounded-2xl shadow-2xl p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-slate-800">Assign Reviewers</h3>
                    <button onclick="closeAssignModal()" class="w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-700 transition flex items-center justify-center">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <p class="text-sm text-slate-500 mb-4" id="modalFormTitle"></p>

                <form id="assignForm" method="POST" action="">
                    @csrf
                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        @foreach($reviewers as $reviewer)
                            <label class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl hover:bg-blue-50 cursor-pointer transition border-2 border-transparent has-[:checked]:border-blue-300 has-[:checked]:bg-blue-50">
                                <input type="checkbox" name="reviewer_ids[]" value="{{ $reviewer->id }}" class="reviewer-checkbox w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                                <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold">
                                    {{ mb_substr($reviewer->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $reviewer->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $reviewer->email }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    @if($reviewers->count() === 0)
                        <div class="text-center py-8">
                            <i class="fas fa-users-slash text-3xl text-slate-200 mb-2"></i>
                            <p class="text-slate-500 text-sm">No reviewers found. Create users with the reviewer role first.</p>
                        </div>
                    @endif

                    <div class="flex gap-3 mt-6">
                        <button type="button" onclick="closeAssignModal()" class="flex-1 bg-slate-100 text-slate-700 py-2.5 rounded-xl hover:bg-slate-200 transition font-bold text-sm">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 bg-slate-900 hover:bg-blue-600 text-white py-2.5 rounded-xl hover:shadow-lg hover:shadow-blue-500/20 transition duration-300 font-bold text-sm">
                            <i class="fas fa-check ml-1"></i>
                            Save Assignment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openAssignModal(formId, formTitle, assignedIds) {
            const modal = document.getElementById('assignModal');
            const form = document.getElementById('assignForm');
            const titleEl = document.getElementById('modalFormTitle');

            form.action = `/reviewer/forms/${formId}/assign`;
            titleEl.textContent = formTitle;

            // Reset checkboxes
            document.querySelectorAll('.reviewer-checkbox').forEach(cb => {
                cb.checked = assignedIds.includes(parseInt(cb.value));
            });

            modal.classList.remove('hidden');
        }

        function closeAssignModal() {
            document.getElementById('assignModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
