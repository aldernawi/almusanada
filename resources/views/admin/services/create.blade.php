@extends('layouts.website-app')

@section('page_title', 'Add Service')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('admin.services.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-800 font-semibold transition-colors mb-4">
                <svg class="w-4 h-4 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
                Back to services list
            </a>
            <h1 class="text-2xl font-extrabold text-slate-800 mb-6 pb-4 border-b border-slate-100 flex items-center gap-3"><i class="fas fa-plus-circle text-blue-600"></i> Add New Service</h1>
            <p class="text-slate-500 mt-1 font-medium">Fill in the data below to add a new service card on the home page</p>
        </div>

        <div class="main-card p-8">
            <form action="{{ route('admin.services.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Service Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" 
                               class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 outline-none transition"
                               placeholder="Service title" required>
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Description <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="4" 
                                  class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 outline-none transition resize-none"
                                  placeholder="Write a brief and clear description of the service" required>{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Display Order</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" 
                                   class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 outline-none transition">
                            <p class="text-slate-400 text-xs mt-2">Lower numbers appear first</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Icon Code (Optional)</label>
                            <input type="text" name="icon" value="{{ old('icon', 'star') }}" 
                                   class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 outline-none transition">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-10 pt-8 border-t border-slate-100">
                    <button type="submit" 
                            class="bg-slate-900 hover:bg-blue-600 text-white font-bold py-3 px-10 rounded-xl transition duration-300 hover:shadow-lg hover:shadow-blue-500/20 hover:-translate-y-0.5 flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        Add Service
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
