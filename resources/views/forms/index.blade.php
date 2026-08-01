<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">My Forms</h1>
                <p class="text-slate-500 mt-1">Manage and monitor all your electronic forms</p>
            </div>
            <a href="{{ route('forms.create') }}" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-blue-600 text-white px-6 py-3 rounded-xl transition duration-300 hover:shadow-lg hover:shadow-blue-500/20 font-bold">
                <i class="fas fa-plus"></i>
                <span>New Form</span>
            </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 px-6 py-4 rounded-xl mb-6 flex items-center gap-3">
                <i class="fas fa-check-circle text-xl"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($forms->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($forms as $form)
                    <div class="main-card overflow-hidden hover:shadow-xl transition-all duration-300 card-lift group">
                        <div class="h-28 bg-gradient-to-l from-slate-800 to-slate-900 relative overflow-hidden">
                            <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=%2260%22 height=%2260%22 viewBox=%220 0 60 60%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cg fill=%22none%22 fill-rule=%22evenodd%22%3E%3Cg fill=%22%23ffffff%22 fill-opacity=%220.4%22%3E%3Cpath d=%22M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
                            <div class="absolute top-4 left-4 flex gap-2">
                                <span class="px-3 py-1 rounded-full text-xs font-bold backdrop-blur-sm {{ $form->status == 'active' ? 'bg-emerald-400/30 text-emerald-50' : 'bg-white/20 text-white' }}">
                                    {{ $form->status == 'active' ? 'Active' : 'Inactive' }}
                                </span>
                                @if($form->is_favorite)
                                    <span class="px-2 py-1 rounded-full text-xs bg-amber-400/30 text-amber-50"><i class="fas fa-star"></i></span>
                                @endif
                            </div>
                            <div class="absolute bottom-4 right-4 left-4">
                                <h3 class="text-white font-bold text-lg truncate">{{ $form->title }}</h3>
                            </div>
                        </div>
                        <div class="p-5">
                            <p class="text-slate-500 text-sm mb-4 line-clamp-2 min-h-[40px]">{{ $form->description ?: 'No description' }}</p>
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="text-center bg-slate-50 rounded-lg px-3 py-2">
                                        <div class="text-xl font-bold text-slate-700">{{ $form->submissions->count() }}</div>
                                        <div class="text-[10px] text-slate-400">submissions</div>
                                    </div>
                                    <div class="text-center bg-blue-50 rounded-lg px-3 py-2">
                                        <div class="text-xl font-bold text-blue-600">{{ $form->fields->count() }}</div>
                                        <div class="text-[10px] text-slate-400">fields</div>
                                    </div>
                                </div>
                                <div class="text-xs text-slate-400 text-left">
                                    <i class="fas fa-clock ml-1"></i>
                                    {{ $form->created_at->diffForHumans() }}
                                </div>
                            </div>
                            <div class="flex gap-2 pt-4 border-t border-slate-100">
                                <a href="{{ route('forms.edit', $form) }}" class="flex-1 bg-slate-100 text-slate-600 py-2.5 rounded-lg text-sm font-bold hover:bg-slate-900 hover:text-white transition text-center">
                                    <i class="fas fa-pen-to-square ml-1"></i> Edit
                                </a>
                                <a href="{{ route('forms.share', $form) }}" class="flex-1 bg-blue-50 text-blue-600 py-2.5 rounded-lg text-sm font-bold hover:bg-blue-600 hover:text-white transition text-center">
                                    <i class="fas fa-share-alt ml-1"></i> Share
                                </a>
                                <a href="{{ route('submissions.index', $form) }}" class="flex-1 bg-emerald-50 text-emerald-600 py-2.5 rounded-lg text-sm font-bold hover:bg-emerald-600 hover:text-white transition text-center">
                                    <i class="fas fa-chart-bar ml-1"></i> Submissions
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-8">
                {{ $forms->links() }}
            </div>
        @else
            <div class="text-center py-20">
                <div class="w-28 h-28 bg-gradient-to-br from-slate-100 to-slate-200 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-folder-open text-5xl text-slate-400"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 mb-2">No forms yet</h3>
                <p class="text-slate-500 mb-8">Start by creating your first form and collecting data</p>
                <a href="{{ route('forms.create') }}" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-blue-600 text-white px-8 py-3.5 rounded-xl transition duration-300 hover:shadow-lg hover:shadow-blue-500/20 font-bold">
                    <i class="fas fa-plus"></i>
                    Create New Form
                </a>
            </div>
        @endif
    </div>
</x-app-layout>
