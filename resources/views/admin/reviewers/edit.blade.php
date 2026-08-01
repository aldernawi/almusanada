@extends('layouts.website-app')

@section('page_title', 'تعديل المراجع')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <div class="mb-8">
            <a href="{{ route('admin.reviewers.index') }}" 
               class="text-blue-600 hover:text-blue-800 font-medium flex items-center gap-2 mb-6 inline-block">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                العودة لقائمة المراجعين
            </a>
            
            <h1 class="text-3xl font-bold text-gray-800">تعديل بيانات المراجع</h1>
            <p class="text-gray-600 mt-2">تعديل معلومات حساب المراجع</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border-r-4 border-red-500 text-red-800 px-6 py-4 rounded-lg mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="mr-3">
                        <h3 class="text-sm font-medium text-red-800">هناك أخطاء في البيانات:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md p-8">
            <form action="{{ route('admin.reviewers.update', $reviewer->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">المعلومات الأساسية</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    اسم المراجع <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name', $reviewer->name) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none"
                                       required>
                            </div>
                            
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                    اسم المستخدم <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="username" name="username" value="{{ old('username', $reviewer->username) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none"
                                       required>
                            </div>
                        </div>
                    </div>

                    <!-- Permissions -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">الصلاحيات</h3>
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <label class="flex items-center">
                                <input type="checkbox" name="can_view_all_transactions" value="1"
                                       {{ $reviewer->can_view_all_transactions ? 'checked' : '' }}
                                       class="ml-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-gray-700">السماح برؤية جميع المعاملات</span>
                            </label>
                            <p class="text-gray-500 text-xs mt-2 ml-6">إذا لم يتم تحديده، سيرى المراجع معاملاته فقط</p>
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">تغيير كلمة المرور (اختياري)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    كلمة المرور الجديدة
                                </label>
                                <input type="password" id="password" name="password"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none"
                                       placeholder="اتركها فارغة لعدم التغيير">
                            </div>
                            
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    تأكيد كلمة المرور
                                </label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none"
                                       placeholder="أعد إدخال كلمة المرور">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4 mt-8">
                    <a href="{{ route('admin.reviewers.index') }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        إلغاء
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
