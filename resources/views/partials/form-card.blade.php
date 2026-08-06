<style>
    [x-cloak] {
        display: none !important;
    }

    .forms-grid {
        overflow: visible !important;
    }

    .form-card {
        position: relative;
        overflow: visible !important;
    }

    .form-card-options-open {
        z-index: 100;
    }
</style>

<div class="forms-grid grid grid-cols-1 auto-rows-fr items-stretch gap-6 md:grid-cols-2 xl:grid-cols-3">

    @foreach($forms as $form)

        <div
            x-data="{ openOptions: false }"
            :class="{ 'form-card-options-open': openOptions }"
            class="form-item-row form-card main-card isolate flex h-full min-w-0 flex-col
                   rounded-2xl border border-slate-200 bg-white shadow-sm
                   transition duration-300 hover:-translate-y-1 hover:shadow-xl"
            data-title="{{ $form->title }}"
        >

            {{-- رأس الكارد --}}
            <div class="relative h-24 shrink-0 overflow-hidden rounded-t-2xl bg-gradient-to-l from-slate-800 to-slate-950">

                {{-- الخلفية المزخرفة --}}
                <div
                    class="absolute inset-0 opacity-20"
                    style="background-image: url('data:image/svg+xml,%3Csvg width=%2260%22 height=%2260%22 viewBox=%220 0 60 60%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cg fill=%22none%22 fill-rule=%22evenodd%22%3E%3Cg fill=%22%23ffffff%22 fill-opacity=%220.4%22%3E%3Cpath d=%22M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"
                ></div>

                {{-- الحالة والمفضلة --}}
                <div class="absolute left-3 top-3 flex items-center gap-2">

                    <span
                        class="rounded-full px-2.5 py-1 text-[10px] font-bold backdrop-blur-sm
                        {{ $form->status === 'active'
                            ? 'bg-emerald-400/30 text-emerald-50'
                            : 'bg-white/20 text-white' }}"
                    >
                        {{ $form->status === 'active' ? 'Active' : 'Inactive' }}
                    </span>

                    @if($form->is_favorite && !$isTrashed)
                        <span class="rounded-full bg-amber-400/30 px-2 py-1 text-[10px] text-amber-50">
                            <i class="fas fa-star"></i>
                        </span>
                    @endif
                </div>

                {{-- عنوان الفورم --}}
                <div class="absolute bottom-3 left-3 right-3 min-w-0">

                    <h3
                        class="truncate text-base font-bold text-white"
                        title="{{ $form->title }}"
                    >
                        {{ $form->title }}
                    </h3>

                </div>
            </div>

            {{-- محتوى الكارد --}}
            <div class="flex min-w-0 flex-1 flex-col p-4">

                {{-- الوصف --}}
                <p class="mb-3 min-h-[40px] break-words text-xs leading-5 text-slate-500 line-clamp-2">
                    {{ $form->description ?: 'No description' }}
                </p>

                {{-- المجلدات --}}
                @if($form->folders->isNotEmpty())

                    <div class="mb-3 flex flex-wrap gap-1.5">

                        @foreach($form->folders as $folder)

                            <span
                                class="inline-flex max-w-full items-center gap-1 rounded-full px-2 py-1 text-[9px] font-bold"
                                style="background-color: {{ $folder->color }}15; color: {{ $folder->color }}"
                            >
                                <i class="fas fa-folder shrink-0 text-[8px]"></i>

                                <span class="truncate">
                                    {{ $folder->name }}
                                </span>
                            </span>

                        @endforeach

                    </div>

                @endif

                {{-- الإحصائيات --}}
                <div class="mb-4 mt-auto border-b border-slate-100 pb-4">

                    <div class="grid grid-cols-2 gap-2">

                        <div class="rounded-xl bg-slate-50 px-3 py-2 text-center">

                            <div class="text-lg font-bold text-slate-700">
                                {{ $form->submissions_count }}
                            </div>

                            <div class="text-[9px] text-slate-400">
                                Submissions
                            </div>

                        </div>

                        <div class="rounded-xl bg-blue-50 px-3 py-2 text-center">

                            <div class="text-lg font-bold text-blue-600">
                                {{ $form->fields->count() }}
                            </div>

                            <div class="text-[9px] text-slate-400">
                                Fields
                            </div>

                        </div>

                    </div>

                    <div class="mt-2 flex items-center justify-end text-[10px] text-slate-400">

                        <i class="fas fa-clock mr-1"></i>

                        {{ $form->created_at->diffForHumans() }}

                    </div>
                </div>

                {{-- الأزرار --}}
                @if($isTrashed)

                    <div class="grid grid-cols-2 gap-2">

                        <button
                            type="button"
                            onclick="restoreForm({{ $form->id }})"
                            class="flex items-center justify-center gap-1.5 rounded-lg
                                   bg-emerald-50 px-3 py-2.5 text-[11px] font-bold
                                   text-emerald-600 transition
                                   hover:bg-emerald-600 hover:text-white"
                        >
                            <i class="fas fa-trash-restore-alt"></i>

                            <span>Restore</span>
                        </button>

                        <button
                            type="button"
                            onclick="forceDeleteForm({{ $form->id }})"
                            class="flex items-center justify-center gap-1.5 rounded-lg
                                   bg-red-50 px-3 py-2.5 text-[11px] font-bold
                                   text-red-600 transition
                                   hover:bg-red-600 hover:text-white"
                        >
                            <i class="fas fa-times-circle"></i>

                            <span>Delete</span>
                        </button>

                    </div>

                @else

                    <div class="grid grid-cols-2 gap-2">

                        {{-- تعديل --}}
                        <a
                            href="{{ route('forms.edit', $form) }}"
                            class="flex min-w-0 items-center justify-center gap-1.5
                                   rounded-lg bg-slate-100 px-2 py-2.5
                                   text-[11px] font-bold text-slate-600
                                   transition hover:bg-slate-900 hover:text-white"
                        >
                            <i class="fas fa-edit shrink-0"></i>

                            <span class="truncate">
                                Edit
                            </span>
                        </a>

                        {{-- الردود --}}
                        <a
                            href="{{ route('reviewer.forms.submissions', ['form' => $form]) }}"
                            class="flex min-w-0 items-center justify-center gap-1.5
                                   rounded-lg bg-emerald-50 px-2 py-2.5
                                   text-[11px] font-bold text-emerald-600
                                   transition hover:bg-emerald-600 hover:text-white"
                        >
                            <i class="fas fa-database shrink-0"></i>

                            <span class="truncate">
                                Responses
                            </span>
                        </a>

                        {{-- المراجعة --}}
                        <a
                            href="{{ route('reviewer.forms.submissions', ['form' => $form]) }}"
                            class="flex min-w-0 items-center justify-center gap-1.5
                                   rounded-lg bg-amber-50 px-2 py-2.5
                                   text-[11px] font-bold text-amber-600
                                   transition hover:bg-amber-600 hover:text-white"
                        >
                            <i class="fas fa-clipboard-check shrink-0"></i>

                            <span class="truncate">
                                Review
                            </span>
                        </a>

                        {{-- المشاركة --}}
                        <a
                            href="{{ route('forms.share', $form) }}"
                            class="flex min-w-0 items-center justify-center gap-1.5
                                   rounded-lg bg-blue-50 px-2 py-2.5
                                   text-[11px] font-bold text-blue-600
                                   transition hover:bg-blue-600 hover:text-white"
                        >
                            <i class="fas fa-share-alt shrink-0"></i>

                            <span class="truncate">
                                Share
                            </span>
                        </a>

                        {{-- قائمة المزيد --}}
                        <div class="relative col-span-2">

                            <button
                                type="button"
                                @click.stop="openOptions = !openOptions"
                                :aria-expanded="openOptions"
                                :class="openOptions
                                    ? 'bg-slate-900 text-white'
                                    : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                class="flex w-full items-center justify-center gap-2
                                       rounded-lg px-3 py-2.5 text-[11px]
                                       font-bold transition"
                            >
                                <i class="fas fa-ellipsis-h"></i>

                                <span>
                                    More options
                                </span>

                                <i
                                    class="fas fa-chevron-down text-[9px]
                                           transition-transform duration-200"
                                    :class="{ 'rotate-180': openOptions }"
                                ></i>
                            </button>

                            {{-- Dropdown --}}
                            <div
                                x-cloak
                                x-show="openOptions"
                                @click.outside="openOptions = false"
                                @keydown.escape.window="openOptions = false"
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                                class="absolute bottom-full left-0 right-0 z-[999]
                                       mb-2 overflow-hidden rounded-xl
                                       border border-slate-200 bg-white
                                       py-1 shadow-2xl"
                            >

                                {{-- المفضلة --}}
                                <button
                                    type="button"
                                    onclick="toggleFavorite({{ $form->id }}, this)"
                                    @click="openOptions = false"
                                    class="flex w-full items-center gap-3
                                           px-4 py-2.5 text-left text-[11px]
                                           font-bold text-slate-700
                                           transition hover:bg-slate-50"
                                >
                                    <i
                                        class="{{ $form->is_favorite
                                            ? 'fas fa-star text-amber-500'
                                            : 'far fa-star text-slate-400' }}
                                            w-4 text-center"
                                    ></i>

                                    <span>
                                        {{ $form->is_favorite
                                            ? 'Remove from Favorites'
                                            : 'Add to Favorites' }}
                                    </span>
                                </button>

                                {{-- نسخ الفورم --}}
                                <form
                                    action="{{ route('forms.duplicate', $form) }}"
                                    method="POST"
                                    class="w-full"
                                >
                                    @csrf

                                    <button
                                        type="submit"
                                        class="flex w-full items-center gap-3
                                               px-4 py-2.5 text-left text-[11px]
                                               font-bold text-slate-700
                                               transition hover:bg-slate-50"
                                    >
                                        <i class="fas fa-clone w-4 text-center text-slate-400"></i>

                                        <span>
                                            Duplicate Form
                                        </span>
                                    </button>
                                </form>

                                {{-- الأرشيف --}}
                                <button
                                    type="button"
                                    onclick="toggleArchive({{ $form->id }}, this)"
                                    @click="openOptions = false"
                                    class="flex w-full items-center gap-3
                                           px-4 py-2.5 text-left text-[11px]
                                           font-bold text-slate-700
                                           transition hover:bg-slate-50"
                                >
                                    <i class="fas fa-archive w-4 text-center text-slate-400"></i>

                                    <span>
                                        {{ $form->archived_at
                                            ? 'Unarchive'
                                            : 'Move to Archive' }}
                                    </span>
                                </button>

                                {{-- النقل إلى مجلد --}}
                                <button
                                    type="button"
                                    onclick="openMoveFolderModal({{ $form->id }})"
                                    @click="openOptions = false"
                                    class="flex w-full items-center gap-3
                                           px-4 py-2.5 text-left text-[11px]
                                           font-bold text-slate-700
                                           transition hover:bg-slate-50"
                                >
                                    <i class="fas fa-folder w-4 text-center text-slate-400"></i>

                                    <span>
                                        Move to Folder...
                                    </span>
                                </button>

                                <div class="my-1 border-t border-slate-100"></div>

                                {{-- الحذف --}}
                                <form
                                    action="{{ route('forms.destroy', $form) }}"
                                    method="POST"
                                    class="w-full"
                                    onsubmit="return confirm('Are you sure you want to move this form to trash?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="flex w-full items-center gap-3
                                               px-4 py-2.5 text-left text-[11px]
                                               font-bold text-red-600
                                               transition hover:bg-red-50"
                                    >
                                        <i class="fas fa-trash-alt w-4 text-center text-red-400"></i>

                                        <span>
                                            Move to Trash
                                        </span>
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>

                @endif
            </div>
        </div>

    @endforeach

</div>
