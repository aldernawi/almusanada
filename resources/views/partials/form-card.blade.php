<div class="form-item-row main-card overflow-hidden card-lift relative group" data-title="{{ $form->title }}">
    <!-- Card Header with Gradient -->
    <div class="h-20 bg-gradient-to-l from-slate-800 to-slate-900 relative overflow-hidden">
        <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=%2260%22 height=%2260%22 viewBox=%220 0 60 60%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cg fill=%22none%22 fill-rule=%22evenodd%22%3E%3Cg fill=%22%23ffffff%22 fill-opacity=%220.4%22%3E%3Cpath d=%22M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        <div class="absolute top-3 left-3 flex gap-2">
            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold backdrop-blur-sm {{ $form->status == 'active' ? 'bg-emerald-400/30 text-emerald-50' : 'bg-white/20 text-white' }}">
                {{ $form->status == 'active' ? 'Active' : 'Inactive' }}
            </span>
            @if($form->is_favorite && !$isTrashed)
                <span class="px-2 py-1 rounded-full text-[10px] bg-amber-400/30 text-amber-50"><i class="fas fa-star"></i></span>
            @endif
        </div>
        <div class="absolute bottom-3 right-3 left-3">
            <h3 class="text-white font-bold text-base truncate">{{ $form->title }}</h3>
        </div>
    </div>

    <!-- Card Body -->
    <div class="p-4">
        <p class="text-slate-500 text-xs mb-3 line-clamp-2 min-h-[32px]">{{ $form->description ?: 'No description' }}</p>

        <!-- Folders -->
        @if($form->folders->count() > 0)
            <div class="flex flex-wrap gap-1 mb-3">
                @foreach($form->folders as $f)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold" style="background-color: {{ $f->color }}15; color: {{ $f->color }}">
                        <i class="fas fa-folder text-[8px]"></i> {{ $f->name }}
                    </span>
                @endforeach
            </div>
        @endif

        <!-- Stats Row -->
        <div class="flex items-center justify-between mb-3 pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <div class="text-center bg-slate-50 rounded-lg px-2.5 py-1.5">
                    <div class="text-base font-bold text-slate-700">{{ $form->submissions_count }}</div>
                    <div class="text-[9px] text-slate-400">submissions</div>
                </div>
                <div class="text-center bg-blue-50 rounded-lg px-2.5 py-1.5">
                    <div class="text-base font-bold text-blue-600">{{ $form->fields->count() }}</div>
                    <div class="text-[9px] text-slate-400">fields</div>
                </div>
            </div>
            <div class="text-[10px] text-slate-400 text-left">
                <i class="fas fa-clock ml-1"></i>
                {{ $form->created_at->diffForHumans() }}
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-1.5">
            @if($isTrashed)
                <button onclick="restoreForm({{ $form->id }})" class="flex-1 bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white py-2 rounded-lg text-[11px] font-bold transition flex items-center justify-center gap-1">
                    <i class="fas fa-trash-restore-alt"></i> Restore
                </button>
                <button onclick="forceDeleteForm({{ $form->id }})" class="flex-1 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white py-2 rounded-lg text-[11px] font-bold transition flex items-center justify-center gap-1">
                    <i class="fas fa-times-circle"></i> Delete
                </button>
            @else
                <a href="{{ route('forms.edit', $form) }}" class="flex-1 bg-slate-100 text-slate-600 hover:bg-slate-900 hover:text-white py-2 rounded-lg text-[11px] font-bold transition flex items-center justify-center gap-1">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('medical-auditing.index', ['form_id' => $form->id]) }}" class="flex-1 bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white py-2 rounded-lg text-[11px] font-bold transition flex items-center justify-center gap-1">
                    <i class="fas fa-database"></i> Responses
                </a>
                <a href="{{ route('medical-auditing.index', ['form_id' => $form->id]) }}" class="flex-1 bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white py-2 rounded-lg text-[11px] font-bold transition flex items-center justify-center gap-1">
                    <i class="fas fa-clipboard-check"></i> Review
                </a>
                <a href="{{ route('forms.share', $form) }}" class="flex-1 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white py-2 rounded-lg text-[11px] font-bold transition flex items-center justify-center gap-1">
                    <i class="fas fa-share-alt"></i> Share
                </a>

                <!-- More Options -->
                <div class="relative" x-data="{ openOptions: false }">
                    <button @click="openOptions = !openOptions" class="bg-slate-100 text-slate-600 hover:bg-slate-200 p-2 rounded-lg text-[11px] font-bold transition w-8 h-8 flex items-center justify-center">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <div x-show="openOptions" @click.away="openOptions = false" class="absolute left-0 mt-2 w-44 bg-white rounded-xl shadow-xl border border-slate-100 py-1 z-50">
                        <button onclick="toggleFavorite({{ $form->id }}, this)" class="w-full flex items-center gap-2 px-3 py-2 text-[11px] font-bold text-slate-700 hover:bg-slate-50 text-right">
                            <i class="{{ $form->is_favorite ? 'fas fa-star text-amber-500' : 'far fa-star text-slate-400' }} w-4"></i>
                            {{ $form->is_favorite ? 'Remove from Favorites' : 'Add to Favorites' }}
                        </button>
                        <form action="{{ route('forms.duplicate', $form) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-[11px] font-bold text-slate-700 hover:bg-slate-50 text-right">
                                <i class="fas fa-clone text-slate-400 w-4"></i> Duplicate Form
                            </button>
                        </form>
                        <button onclick="toggleArchive({{ $form->id }}, this)" class="w-full flex items-center gap-2 px-3 py-2 text-[11px] font-bold text-slate-700 hover:bg-slate-50 text-right">
                            <i class="fas fa-archive text-slate-400 w-4"></i> {{ $form->archived_at ? 'Unarchive' : 'Move to Archive' }}
                        </button>
                        <button onclick="openMoveFolderModal({{ $form->id }})" class="w-full flex items-center gap-2 px-3 py-2 text-[11px] font-bold text-slate-700 hover:bg-slate-50 text-right">
                            <i class="fas fa-folder text-slate-400 w-4"></i> Move to Folder...
                        </button>
                        <hr class="my-1 border-slate-100">
                        <form action="{{ route('forms.destroy', $form) }}" method="POST" class="w-full" onsubmit="return confirm('Are you sure you want to move this form to trash?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-[11px] font-bold text-red-600 hover:bg-red-50 text-right">
                                <i class="fas fa-trash-alt text-red-400 w-4"></i> Move to Trash
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
