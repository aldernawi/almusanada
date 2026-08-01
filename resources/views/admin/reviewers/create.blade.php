@extends('layouts.website-app')

@section('page_title', 'إنشاء مستخدم جديد')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <div class="mb-6">
            <a href="{{ route('admin.reviewers.index') }}"
                class="text-blue-600 hover:text-blue-800 flex items-center gap-1 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                العودة إلى قائمة المستخدمين
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-6 pb-4 border-b">إنشاء حساب جديد</h1>

            <form action="{{ route('admin.reviewers.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">الدور <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-400 transition has-[:checked]:border-green-500 has-[:checked]:bg-green-50">
                                <input type="radio" name="role" value="reviewer" checked class="text-green-600 focus:ring-green-500">
                                <div>
                                    <p class="text-sm font-bold text-gray-800">مراجع</p>
                                    <p class="text-xs text-gray-500">يقدر يعمل موافقة/رفض وتعديل الملاحظات</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-400 transition has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                                <input type="radio" name="role" value="viewer" class="text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <p class="text-sm font-bold text-gray-800">اطلاع</p>
                                    <p class="text-xs text-gray-500">عرض فقط بدون أي إجراءات</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">الاسم <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none"
                            placeholder="الاسم الكامل" required>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">اسم المستخدم <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="username" value="{{ old('username') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none"
                            placeholder="اسم المستخدم للدخول إلى لوحة المراجعة" required>
                        @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">كلمة المرور <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none"
                            placeholder="كلمة المرور (6 أحرف على الأقل)" required>
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        <p class="text-gray-400 text-xs mt-1">ملاحظة: كلمة المرور ظاهرة هنا لتتمكن من إعطائها للمستخدم</p>
                    </div>

                    <div id="permissions-section">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">الصلاحيات</label>
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <label class="flex items-center">
                                <input type="checkbox" name="can_view_all_transactions" value="1"
                                       class="ml-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-gray-700">السماح برؤية جميع المعاملات</span>
                            </label>
                            <p class="text-gray-500 text-xs mt-2 ml-6">إذا لم يتم تحديده، سيرى المراجع معاملاته فقط</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-8">
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-3 rounded-lg transition">
                        إنشاء الحساب
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('input[name="role"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                var permSection = document.getElementById('permissions-section');
                permSection.style.display = this.value === 'viewer' ? 'none' : 'block';
            });
        });
    </script>
@endsection
