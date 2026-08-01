<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <!-- JotForm My Forms Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 hover:bg-blue-600 text-white rounded-xl font-bold text-sm transition duration-300 hover:shadow-lg hover:shadow-blue-500/20">
                    <i class="fas fa-wpforms"></i>
                    <span>My Forms</span>
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-slate-100 py-1 z-50">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 font-bold">
                        <i class="fas fa-file-alt text-blue-500 w-5"></i> My Forms
                    </a>
                    <a href="{{ route('medical-auditing.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 font-bold">
                        <i class="fas fa-clipboard-check text-emerald-500 w-5"></i> Approvals
                    </a>
                    <a href="{{ route('api-keys.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 font-bold">
                        <i class="fas fa-key text-amber-500 w-5"></i> API Keys
                    </a>
                    <a href="{{ route('account.usage') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 font-bold">
                        <i class="fas fa-chart-pie text-blue-500 w-5"></i> Account Usage
                    </a>
                </div>
            </div>

            <!-- Global search -->
            <div class="relative w-96 hidden md:block">
                <input type="text" id="search-input" oninput="filterForms()" placeholder="Search forms by name..." class="w-full pl-10 pr-4 py-2.5 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 text-sm transition">
                <i class="fas fa-search absolute left-3 top-3.5 text-slate-400"></i>
            </div>

            <!-- Profile and limits -->
            <div class="flex items-center gap-4">
                <a href="{{ route('account.usage') }}" class="text-xs text-slate-600 hover:text-blue-600 bg-white px-3 py-2 rounded-xl border-2 border-slate-100 hover:border-blue-200 font-bold transition flex items-center gap-1.5">
                    <i class="fas fa-database text-blue-500"></i> Usage & Subscription
                </a>
            </div>
        </div>
    </x-slot>

    <!-- JotForm Three-Section Layout -->
    <div class="py-8" x-data="{ currentTab: 'all', activeFolder: null }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-6">
                
                <!-- Left Sidebar -->
                <div class="w-full lg:w-64 flex-shrink-0">
                    <!-- Create Form Button -->
                    <a href="{{ route('forms.create') }}" class="w-full bg-slate-900 hover:bg-blue-600 text-white py-3.5 px-6 rounded-2xl font-bold text-center transition duration-300 hover:shadow-lg hover:shadow-blue-500/20 flex items-center justify-center gap-2 mb-6 text-sm">
                        <i class="fas fa-plus"></i>
                        <span>Create New Form</span>
                    </a>

                    <!-- Sidebar Links -->
                    <div class="main-card p-4 space-y-1">
                        <button @click="currentTab = 'all'; activeFolder = null" :class="currentTab === 'all' ? 'bg-blue-50 text-blue-600' : 'text-slate-700 hover:bg-slate-50'" class="w-full flex items-center justify-between p-3 rounded-xl font-bold text-sm transition text-left">
                            <span class="flex items-center gap-3">
                                <i class="fas fa-list text-slate-400 w-5"></i> All Forms
                            </span>
                            <span class="bg-slate-100 text-slate-600 text-xs px-2 py-0.5 rounded-full" id="all-count">{{ $allForms->count() }}</span>
                        </button>

                        <button @click="currentTab = 'favorites'; activeFolder = null" :class="currentTab === 'favorites' ? 'bg-blue-50 text-blue-600' : 'text-slate-700 hover:bg-slate-50'" class="w-full flex items-center justify-between p-3 rounded-xl font-bold text-sm transition text-left">
                            <span class="flex items-center gap-3">
                                <i class="fas fa-star text-amber-500 w-5"></i> Favorite Forms
                            </span>
                            <span class="bg-slate-100 text-slate-600 text-xs px-2 py-0.5 rounded-full" id="fav-count">{{ $favoriteForms->count() }}</span>
                        </button>

                        <button @click="currentTab = 'archived'; activeFolder = null" :class="currentTab === 'archived' ? 'bg-blue-50 text-blue-600' : 'text-slate-700 hover:bg-slate-50'" class="w-full flex items-center justify-between p-3 rounded-xl font-bold text-sm transition text-left">
                            <span class="flex items-center gap-3">
                                <i class="fas fa-archive text-blue-500 w-5"></i> Archive
                            </span>
                            <span class="bg-slate-100 text-slate-600 text-xs px-2 py-0.5 rounded-full" id="archived-count">{{ $archivedForms->count() }}</span>
                        </button>

                        <button @click="currentTab = 'trashed'; activeFolder = null" :class="currentTab === 'trashed' ? 'bg-blue-50 text-blue-600' : 'text-slate-700 hover:bg-slate-50'" class="w-full flex items-center justify-between p-3 rounded-xl font-bold text-sm transition text-left">
                            <span class="flex items-center gap-3">
                                <i class="fas fa-trash-alt text-red-500 w-5"></i> Trash
                            </span>
                            <span class="bg-slate-100 text-slate-600 text-xs px-2 py-0.5 rounded-full" id="trashed-count">{{ $trashedForms->count() }}</span>
                        </button>
                    </div>

                    <!-- Folders Section -->
                    <div class="main-card p-4 mt-6">
                        <div class="flex items-center justify-between mb-4 px-2">
                            <h3 class="font-bold text-slate-800 text-sm">Folders</h3>
                            <button onclick="openCreateFolderModal()" class="text-blue-600 hover:text-blue-700 text-xs font-bold">
                                <i class="fas fa-plus"></i> New
                            </button>
                        </div>
                        <div class="space-y-1" id="folders-list">
                            @forelse($folders as $folder)
                                <button @click="currentTab = 'folder'; activeFolder = {{ $folder->id }}" :class="currentTab === 'folder' && activeFolder === {{ $folder->id }} ? 'bg-blue-50 text-blue-600' : 'text-slate-700 hover:bg-slate-50'" class="w-full flex items-center justify-between p-3 rounded-xl font-bold text-sm transition text-left folder-btn" data-folder-id="{{ $folder->id }}">
                                    <span class="flex items-center gap-3">
                                        <i class="fas fa-folder w-5" style="color: {{ $folder->color }}"></i>
                                        <span class="folder-name">{{ $folder->name }}</span>
                                    </span>
                                    <span class="bg-slate-100 text-slate-600 text-xs px-2 py-0.5 rounded-full">{{ $folder->forms_count }}</span>
                                </button>
                            @empty
                                <p class="text-xs text-slate-400 text-center py-4">No folders created yet</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Main Workspace (Central Zone) -->
                <div class="flex-1">
                    <!-- Role-Specific Quick Actions -->
                    @if(auth()->user()->canViewAuditing())
                    <div class="bg-slate-900 rounded-2xl p-5 shadow-lg mb-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-bold text-lg mb-1">Medical Claims Audit</h3>
                                <p class="text-slate-300 text-sm">{{ auth()->user()->isViewer() ? 'View submissions and audit status' : 'Review pending claims and make audit decisions' }}</p>
                            </div>
                            <a href="{{ route('medical-auditing.index') }}" class="bg-white text-slate-900 font-bold px-5 py-2.5 rounded-xl text-sm hover:bg-blue-50 transition-colors flex items-center gap-2">
                                <span>Go to dashboard</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- Quick Stats Banner -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                        <div class="main-card p-5 flex items-center gap-4 card-lift">
                            <div class="w-12 h-12 bg-slate-100 text-slate-600 rounded-xl flex items-center justify-center"><i class="fas fa-file-invoice text-lg"></i></div>
                            <div>
                                <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Available Forms</div>
                                <div class="text-xl font-extrabold text-slate-800">{{ $totalForms }} <span class="text-sm font-bold text-slate-400">forms</span></div>
                            </div>
                        </div>
                        <div class="main-card p-5 flex items-center gap-4 card-lift">
                            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center"><i class="fas fa-check-circle text-lg"></i></div>
                            <div>
                                <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Submissions Received</div>
                                <div class="text-xl font-extrabold text-slate-800">{{ $totalSubmissions }} <span class="text-sm font-bold text-slate-400">submissions</span></div>
                            </div>
                        </div>
                        <div class="main-card p-5 flex items-center gap-4 card-lift">
                            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center"><i class="fas fa-clock text-lg"></i></div>
                            <div>
                                <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Active Forms</div>
                                <div class="text-xl font-extrabold text-slate-800">{{ $activeForms }} <span class="text-sm font-bold text-slate-400">forms</span></div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Submissions Quick Access -->
                    @if($recentSubmissions->count() > 0)
                    <div class="main-card p-5 mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center text-xs">
                                    <i class="fas fa-table"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-800 text-sm">Recent Submissions</h3>
                                    <p class="text-[11px] text-slate-400">Latest service provider submissions</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                @if($pendingSubmissionsCount > 0)
                                    <span class="bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full text-[11px] font-bold">{{ $pendingSubmissionsCount }} Pending</span>
                                @endif
                                @if(auth()->user()->canReview())
                                    <a href="{{ route('medical-auditing.index') }}" class="text-blue-600 hover:text-blue-700 text-xs font-bold">
                                        View all <i class="fas fa-arrow-right mr-1"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left">
                                <thead>
                                    <tr class="border-b border-slate-100">
                                        <th class="py-2 px-3 text-[11px] font-bold text-slate-400 uppercase">Form</th>
                                        <th class="py-2 px-3 text-[11px] font-bold text-slate-400 uppercase">User</th>
                                        <th class="py-2 px-3 text-[11px] font-bold text-slate-400 uppercase">Date</th>
                                        <th class="py-2 px-3 text-[11px] font-bold text-slate-400 uppercase">Status</th>
                                        <th class="py-2 px-3 text-[11px] font-bold text-slate-400 uppercase"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentSubmissions as $sub)
                                    <tr class="hover:bg-slate-50 transition border-b border-slate-50 last:border-0">
                                        <td class="py-2.5 px-3 text-xs font-bold text-slate-800">{{ $sub->form->title }}</td>
                                        <td class="py-2.5 px-3 text-xs text-slate-500">
                                            <i class="fas fa-user-circle ml-1 text-slate-300"></i> {{ $sub->user ? $sub->user->name : 'Guest' }}
                                        </td>
                                        <td class="py-2.5 px-3 text-xs text-slate-400">{{ $sub->submitted_at->diffForHumans() }}</td>
                                        <td class="py-2.5 px-3">
                                            @if($sub->status == 'pending')
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-700">Pending</span>
                                            @elseif($sub->status == 'approved')
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700">Approved</span>
                                            @else
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700">Rejected</span>
                                            @endif
                                        </td>
                                        <td class="py-2.5 px-3 text-left">
                                            @if(auth()->user()->canViewAuditing())
                                                <a href="{{ route('medical-auditing.index', ['form_id' => $sub->form->id]) }}" class="text-blue-600 hover:text-blue-700 text-xs font-bold">
                                                    {{ auth()->user()->isViewer() ? 'View' : 'Review' }}
                                                </a>
                                            @else
                                                <a href="{{ route('submissions.index', $sub->form) }}" class="text-slate-500 hover:text-slate-700 text-xs font-bold">
                                                    View
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Cards Container -->
                    <div>

                        <!-- ALL TAB -->
                        <div x-show="currentTab === 'all'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 tab-content">
                            @forelse($allForms as $form)
                                @include('partials.form-card', ['form' => $form, 'isTrashed' => false])
                            @empty
                                @include('partials.empty-forms-state')
                            @endforelse
                        </div>

                        <!-- FAVORITES TAB -->
                        <div x-show="currentTab === 'favorites'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 tab-content" style="display: none;">
                            @forelse($favoriteForms as $form)
                                @include('partials.form-card', ['form' => $form, 'isTrashed' => false])
                            @empty
                                @include('partials.empty-forms-state')
                            @endforelse
                        </div>

                        <!-- ARCHIVED TAB -->
                        <div x-show="currentTab === 'archived'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 tab-content" style="display: none;">
                            @forelse($archivedForms as $form)
                                @include('partials.form-card', ['form' => $form, 'isTrashed' => false])
                            @empty
                                @include('partials.empty-forms-state')
                            @endforelse
                        </div>

                        <!-- TRASHED TAB -->
                        <div x-show="currentTab === 'trashed'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 tab-content" style="display: none;">
                            @forelse($trashedForms as $form)
                                @include('partials.form-card', ['form' => $form, 'isTrashed' => true])
                            @empty
                                @include('partials.empty-forms-state')
                            @endforelse
                        </div>

                        <!-- FOLDER TAB (Dynamic JS based filtering) -->
                        <div x-show="currentTab === 'folder'" class="tab-content" style="display: none;">
                            @foreach($folders as $folder)
                                <div x-show="activeFolder === {{ $folder->id }}">
                                    <div class="flex justify-between items-center main-card p-4 mb-5">
                                        <h3 class="font-bold text-slate-800 text-lg"><i class="fas fa-folder-open ml-2 text-blue-500"></i>Folder: {{ $folder->name }}</h3>
                                        <button onclick="deleteFolder({{ $folder->id }})" class="text-red-500 hover:text-red-700 text-xs font-bold px-3 py-1.5 bg-red-50 hover:bg-red-100 rounded-lg transition">
                                            <i class="fas fa-trash-alt"></i> Delete Folder
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                                        @forelse($folder->forms as $form)
                                            @include('partials.form-card', ['form' => $form, 'isTrashed' => false])
                                        @empty
                                            <div class="text-center main-card p-12 col-span-full" style="border-style: dashed; border-color: #cbd5e1;">
                                                <i class="fas fa-folder-open text-4xl text-slate-300 mb-3 block"></i>
                                                <p class="text-slate-500">No forms in this folder yet.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Folder Modal -->
    <div id="create-folder-modal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-2xl p-7 w-96 shadow-2xl border border-slate-100">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-folder-plus text-blue-600"></i>
                </div>
                <h3 class="font-bold text-slate-800 text-lg">Create New Folder</h3>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Folder Name</label>
                    <input type="text" id="folder-name-input" class="w-full px-4 py-2.5 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 text-sm transition" placeholder="Folder name">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Folder Color</label>
                    <input type="color" id="folder-color-input" value="#2563eb" class="w-full h-10 p-1 border-2 border-slate-200 rounded-xl cursor-pointer bg-white">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button onclick="closeCreateFolderModal()" class="px-5 py-2.5 text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl text-sm font-bold transition">Cancel</button>
                    <button onclick="submitCreateFolder()" class="px-5 py-2.5 text-white bg-slate-900 hover:bg-blue-600 rounded-xl text-sm font-bold transition duration-300 hover:shadow-lg hover:shadow-blue-500/20">Create</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Move to Folder Modal -->
    <div id="move-folder-modal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-2xl p-7 w-96 shadow-2xl border border-slate-100">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-folder-tree text-blue-600"></i>
                </div>
                <h3 class="font-bold text-slate-800 text-lg">Move to Folder</h3>
            </div>
            <div class="space-y-4">
                <input type="hidden" id="move-form-id">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Select Folder</label>
                    <div class="space-y-2 max-h-48 overflow-y-auto" id="move-folders-options">
                        @foreach($folders as $folder)
                            <label class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl hover:bg-blue-50 cursor-pointer transition">
                                <input type="radio" name="target_folder_id" value="{{ $folder->id }}" class="text-blue-600 focus:ring-blue-500">
                                <span class="font-bold text-slate-800 text-sm flex items-center gap-2">
                                    <i class="fas fa-folder" style="color: {{ $folder->color }}"></i>
                                    {{ $folder->name }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button onclick="closeMoveFolderModal()" class="px-5 py-2.5 text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl text-sm font-bold transition">Cancel</button>
                    <button onclick="submitMoveFolder()" class="px-5 py-2.5 text-white bg-slate-900 hover:bg-blue-600 rounded-xl text-sm font-bold transition duration-300 hover:shadow-lg hover:shadow-blue-500/20">Move</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Toast Notification -->
    <div id="dashboard-toast" class="fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-slate-900/95 backdrop-blur-sm text-white px-6 py-3.5 rounded-2xl shadow-2xl flex items-center gap-3 text-sm font-bold transition-all duration-300 opacity-0 translate-y-20 z-50 border border-white/10">
        <i class="fas fa-check-circle text-emerald-400"></i>
        <span id="toast-message">Operation completed successfully!</span>
    </div>

    <script>
        function showToast(message) {
            const toast = document.getElementById('dashboard-toast');
            const msgSpan = document.getElementById('toast-message');
            msgSpan.textContent = message;
            toast.classList.remove('opacity-0', 'translate-y-20');
            toast.classList.add('opacity-100', 'translate-y-0');
            setTimeout(() => {
                toast.classList.remove('opacity-100', 'translate-y-0');
                toast.classList.add('opacity-0', 'translate-y-20');
            }, 3000);
        }

        // Search filtering
        function filterForms() {
            const query = document.getElementById('search-input').value.toLowerCase();
            document.querySelectorAll('.form-item-row').forEach(row => {
                const title = row.getAttribute('data-title').toLowerCase();
                if (title.includes(query)) {
                    row.style.display = 'block';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Folder CRUD
        function openCreateFolderModal() {
            document.getElementById('create-folder-modal').classList.remove('hidden');
        }
        function closeCreateFolderModal() {
            document.getElementById('create-folder-modal').classList.add('hidden');
        }
        function submitCreateFolder() {
            const name = document.getElementById('folder-name-input').value;
            const color = document.getElementById('folder-color-input').value;

            if (!name) {
                alert('Please enter a folder name');
                return;
            }

            fetch('{{ route('folders.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ name, color })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }

        function deleteFolder(id) {
            if (!confirm('Are you sure you want to delete this folder? Forms inside it will not be deleted.')) return;

            fetch(`/folders/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }

        // Move to Folder dialog
        function openMoveFolderModal(formId) {
            document.getElementById('move-form-id').value = formId;
            document.getElementById('move-folder-modal').classList.remove('hidden');
        }
        function closeMoveFolderModal() {
            document.getElementById('move-folder-modal').classList.add('hidden');
        }
        function submitMoveFolder() {
            const formId = document.getElementById('move-form-id').value;
            const radios = document.getElementsByName('target_folder_id');
            let folderId = null;
            for (let radio of radios) {
                if (radio.checked) {
                    folderId = radio.value;
                    break;
                }
            }

            if (!folderId) {
                alert('Please select a folder');
                return;
            }

            fetch('{{ route('folders.add-form') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ form_id: formId, folder_id: folderId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }

        // Favorite & Archive features
        function toggleFavorite(formId, button) {
            fetch(`/forms/${formId}/favorite`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const icon = button.querySelector('i');
                    if (data.is_favorite) {
                        icon.className = 'fas fa-star text-amber-500';
                        showToast('Added to favorites');
                    } else {
                        icon.className = 'far fa-star text-gray-400';
                        showToast('Removed from favorites');
                    }
                }
            });
        }

        function toggleArchive(formId, button) {
            fetch(`/forms/${formId}/archive`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast(data.is_archived ? 'Form moved to archive' : 'Form restored from archive');
                    setTimeout(() => location.reload(), 1000);
                }
            });
        }

        // Trash recovery & force delete
        function restoreForm(formId) {
            fetch(`/forms/${formId}/restore`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast('Form restored successfully');
                    setTimeout(() => location.reload(), 1000);
                }
            });
        }

        function forceDeleteForm(formId) {
            if (!confirm('Are you sure you want to permanently delete this form? This action cannot be undone and will delete all submissions and data!')) return;

            fetch(`/forms/${formId}/force-delete`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast('Form permanently deleted');
                    setTimeout(() => location.reload(), 1000);
                }
            });
        }
    </script>
</x-app-layout>
