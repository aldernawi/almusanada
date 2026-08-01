@extends('layouts.website-app')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-blue-600 transition mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                العودة إلى لوحة التحكم
            </a>
            <h1 class="text-3xl font-bold text-gray-800">إضافة موظف جديد</h1>
            <p class="text-gray-600 mt-2">أضف عضو جديد إلى فريق العمل</p>
        </div>

        <!-- Form Card -->
        <div class="main-card p-8">
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-xl mb-6 flex items-center gap-3">
                    <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-exclamation text-white text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-red-800 mb-1">يرجى تصحيح الأخطاء التالية:</h3>
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
                    <label class="block text-sm font-semibold text-slate-700 mb-2">الاسم الكامل <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none"
                        placeholder="أدخل الاسم الكامل">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">المسمى الوظيفي <span class="text-red-500">*</span></label>
                    <input type="text" name="position" value="{{ old('position') }}" required
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none"
                        placeholder="مثال: مطور واجهات أمامية">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">البريد الإلكتروني <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none"
                        placeholder="example@esal.com">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">رابط LinkedIn <span class="text-slate-400 text-xs">(اختياري)</span></label>
                    <input type="url" name="linkedin" value="{{ old('linkedin') }}"
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none"
                        placeholder="https://linkedin.com/in/username">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">نبذة مختصرة <span class="text-slate-400 text-xs">(اختياري)</span></label>
                    <textarea name="bio" rows="4"
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none resize-none"
                        placeholder="اكتب نبذة مختصرة عن الموظف...">{{ old('bio') }}</textarea>
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                    <a href="{{ route('admin.dashboard') }}" 
                        class="px-6 py-3 border border-slate-200 text-slate-600 font-semibold rounded-xl hover:bg-slate-50 transition text-sm">
                        إلغاء
                    </a>
                    <button type="submit" 
                        class="bg-slate-900 hover:bg-blue-600 text-white font-bold px-8 py-3 rounded-xl transition hover:shadow-lg hover:shadow-blue-500/20 hover:-translate-y-0.5 duration-300 flex items-center gap-2 text-sm">
                        <i class="fas fa-check"></i>
                        إضافة الموظف
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
