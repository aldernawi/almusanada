@extends('layouts.website-app')

@section('page_title', 'إدارة المراجعين')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-5xl">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">إدارة المراجعين وحسابات الاطلاع</h1>
                <p class="text-gray-600 mt-2">إنشاء وإدارة حسابات المراجعين وحسابات الاطلاع (عرض فقط)</p>
            </div>
            <a href="{{ route('admin.reviewers.create') }}"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-lg transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
                إضافة مستخدم جديد
            </a>
        </div>

        @if(session('success'))
            <div
                class="bg-green-50 border-r-4 border-green-500 text-green-800 px-6 py-4 rounded-lg mb-6 flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-50 border-b">
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase">الاسم</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase">اسم المستخدم</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase">الدور</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase">الصلاحيات</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase">تاريخ الإنشاء</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($reviewers as $reviewer)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-900 font-medium">{{ $reviewer->name }}</td>
                            <td class="px-6 py-4 text-blue-600 font-bold">{{ $reviewer->username }}</td>
                            <td class="px-6 py-4">
                                @if($reviewer->role === 'viewer')
                                    <span class="bg-indigo-100 text-indigo-700 px-2 py-1 rounded text-xs">اطلاع</span>
                                @else
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">مراجع</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                @if($reviewer->role === 'viewer')
                                    <span class="bg-gray-100 text-gray-500 px-2 py-1 rounded text-xs">عرض فقط</span>
                                @elseif($reviewer->can_view_all_transactions)
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">جميع المعاملات</span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs">محدودة</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $reviewer->created_at->format('Y/m/d') }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.reviewers.edit', $reviewer->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 font-medium transition flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        تعديل
                                    </a>
                                    
                                    <span class="text-gray-300">|</span>
                                    
                                    <form action="{{ route('admin.reviewers.destroy', $reviewer->id) }}" method="POST"
                                        onsubmit="return confirm('هل أنت متأكد من حذف هذا الحساب؟');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-800 font-medium transition flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">لا توجد حسابات حالياً</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
