@extends('layouts.website-app')

@section('page_title', 'إدارة خدماتنا')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800">إدارة قسم "ما نقدمه"</h1>
                <p class="text-slate-500 mt-1 font-medium">أضف أو عدل الخدمات المعروضة في الصفحة الرئيسية</p>
            </div>
            <a href="{{ route('admin.services.create') }}"
               class="bg-slate-900 hover:bg-blue-600 text-white font-bold px-5 py-2.5 rounded-xl transition flex items-center justify-center gap-2 text-sm hover:shadow-lg hover:shadow-blue-500/20 hover:-translate-y-0.5 duration-300">
                <i class="fas fa-plus"></i>
                إضافة خدمة جديدة
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

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($services as $service)
                <div class="main-card overflow-hidden group">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                <span class="font-bold text-lg">#{{ $service->sort_order }}</span>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.services.edit', $service->id) }}" 
                                   class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الخدمة؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2">{{ $service->title }}</h3>
                        <p class="text-slate-500 text-sm leading-relaxed mb-4 line-clamp-3">{{ $service->description }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-slate-50 border-2 border-dashed border-slate-200 rounded-3xl p-12 text-center">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                        <i class="fas fa-cube text-slate-300 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">لا توجد خدمات حالياً</h3>
                    <p class="text-slate-500 max-w-xs mx-auto mt-2">ابدأ بإضافة أول خدمة لتظهر في الصفحة الرئيسية لموقعك.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
