@extends('layouts.website-app')

@section('page_title', 'Add Team Member')
    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-blue-600 transition mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to team list
            </a>
            <h1 class="text-2xl font-extrabold text-slate-800 mb-6 pb-4 border-b border-slate-100 flex items-center gap-3"><i class="fas fa-user-plus text-blue-600"></i> Add New Team Member</h1>
        </div>

        <!-- Form Card -->
        <div class="main-card p-8">
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-xl mb-6 flex items-center gap-3">
                    <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-exclamation text-white text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-red-800 mb-1">Please correct the following errors:</h3>
                        <ul class="list-disc list-inside space-y-1 text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.employees.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none"
                        placeholder="Full name">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Job Title <span class="text-red-500">*</span></label>
                    <input type="text" name="position" value="{{ old('position') }}" required
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none"
                        placeholder="Job title">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none"
                        placeholder="email@example.com">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">LinkedIn Profile (Optional)</label>
                    <input type="url" name="linkedin" value="{{ old('linkedin') }}"
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none"
                        placeholder="https://linkedin.com/in/username">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Short Bio (Optional)</label>
                    <textarea name="bio" rows="4"
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none resize-none"
                        placeholder="Brief professional summary...">{{ old('bio') }}</textarea>
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                    <a href="{{ route('admin.dashboard') }}" 
                        class="px-6 py-3 border border-slate-200 text-slate-600 font-semibold rounded-xl hover:bg-slate-50 transition text-sm">
                        Cancel
                    </a>
                    <button type="submit" 
                        class="bg-slate-900 hover:bg-blue-600 text-white font-bold px-8 py-3 rounded-xl transition hover:shadow-lg hover:shadow-blue-500/20 hover:-translate-y-0.5 duration-300 flex items-center gap-2 text-sm">
                        <i class="fas fa-check"></i>
                        Add Member
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
