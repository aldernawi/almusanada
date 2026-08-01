@extends('layouts.website-app')

@section('page_title', 'إدارة اللوائح')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800">إدارة اللوائح</h1>
                <p class="text-slate-500 mt-1 font-medium">عرض وإدارة لوائح العمل والأنظمة</p>
            </div>
            <a href="{{ route('admin.regulations.create') }}"
                class="bg-slate-900 hover:bg-blue-600 text-white font-bold px-5 py-2.5 rounded-xl transition flex items-center gap-2 text-sm hover:shadow-lg hover:shadow-blue-500/20 hover:-translate-y-0.5 duration-300">
                <i class="fas fa-plus"></i>
                إضافة لائحة جديدة
            </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-xl mb-6 flex items-center gap-3" style="animation: contentSlide 0.4s ease;">
                <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-check text-white text-sm"></i>
                </div>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        <div class="main-card overflow-hidden">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase w-16">الترتيب</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase">عنوان اللائحة</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase">محتوى مختصر</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($regulations as $regulation)
                        <tr class="table-row hover:bg-slate-50 transition">
                            <td class="px-6 py-4 text-slate-400 font-semibold">#{{ $regulation->sort_order }}</td>
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $regulation->title }}</td>
                            <td class="px-6 py-4 text-slate-500 text-sm italic">{{ Str::limit($regulation->content, 50) }}</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-3">
                                    <a href="{{ route('admin.regulations.edit', $regulation->id) }}"
                                        class="text-blue-600 hover:text-blue-800 font-semibold text-sm transition flex items-center gap-1"><i class="fas fa-edit text-xs"></i> تعديل</a>
                                    <form action="{{ route('admin.regulations.destroy', $regulation->id) }}" method="POST"
                                        onsubmit="return confirm('هل أنت متأكد من حذف هذه اللائحة؟');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-800 font-semibold text-sm transition flex items-center gap-1"><i class="fas fa-trash text-xs"></i> حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400">لا توجد لوائح مضافة حالياً</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
