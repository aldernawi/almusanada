@extends('layouts.website-app')

@section('page_title', 'إضافة لائحة')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <div class="mb-6">
            <a href="{{ route('admin.regulations.index') }}"
                class="text-blue-600 hover:text-blue-800 flex items-center gap-1 text-sm font-semibold transition">
                <i class="fas fa-arrow-right text-xs"></i>
                العودة إلى القائمة
            </a>
        </div>

        <div class="main-card p-8">
            <h1 class="text-2xl font-extrabold text-slate-800 mb-6 pb-4 border-b border-slate-100 flex items-center gap-3"><i class="fas fa-gavel text-amber-600"></i> إضافة لائحة جديدة</h1>

            <form action="{{ route('admin.regulations.store') }}" method="POST">
                @csrf

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">عنوان اللائحة <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none"
                            placeholder="مثال: لائحة العمل والعمال">
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">محتوى اللائحة <span class="text-red-500">*</span></label>
                        <textarea name="content" rows="10"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none resize-none"
                            placeholder="أدخل نص اللائحة هنا بالتفصيل...">{{ old('content') }}</textarea>
                        @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">ترتيب العرض</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none">
                    </div>
                </div>

                <div class="flex justify-end mt-8">
                    <button type="submit"
                        class="bg-slate-900 hover:bg-blue-600 text-white font-bold px-8 py-3 rounded-xl transition hover:shadow-lg hover:shadow-blue-500/20 hover:-translate-y-0.5 duration-300 flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        حفظ اللائحة
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
