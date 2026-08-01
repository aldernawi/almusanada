@extends('layouts.website-app')

@section('page_title', 'تعديل الخدمة')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('admin.services.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-800 font-semibold transition-colors mb-4">
                <svg class="w-4 h-4 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
                العودة لقائمة الخدمات
            </a>
            <h1 class="text-3xl font-bold text-slate-900">تعديل الخدمة</h1>
            <p class="text-slate-500 mt-1 font-medium">تعديل بيانات الخدمة: {{ $service->title }}</p>
        </div>

        <div class="main-card p-8">
            <form action="{{ route('admin.services.update', $service->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">عنوان الخدمة</label>
                        <input type="text" name="title" value="{{ old('title', $service->title) }}" 
                               class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 outline-none transition"
                               placeholder="مثال: تصميم مواقع احترافية" required>
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">وصف الخدمة</label>
                        <textarea name="description" rows="4" 
                                  class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 outline-none transition resize-none"
                                  placeholder="اكتب وصفاً مختصراً وواضحاً للخدمة..." required>{{ old('description', $service->description) }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">ترتيب العرض</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $service->sort_order) }}" 
                                   class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">رمز الأيقونة (اختياري)</label>
                            <input type="text" name="icon" value="{{ old('icon', $service->icon) }}" 
                                   class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 outline-none transition">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-10 pt-8 border-t border-slate-100">
                    <button type="submit" 
                            class="bg-slate-900 hover:bg-blue-600 text-white font-bold py-3 px-10 rounded-xl transition duration-300 hover:shadow-lg hover:shadow-blue-500/20 hover:-translate-y-0.5 flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        حفظ التعديلات
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
