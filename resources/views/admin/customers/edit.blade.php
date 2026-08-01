@extends('layouts.website-app')

@section('page_title', 'تعديل العميل')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <div class="mb-8">
            <a href="{{ route('admin.customers.index') }}" 
               class="text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-2 mb-6 inline-block text-sm transition">
                <i class="fas fa-arrow-right text-xs"></i>
                العودة لقائمة العملاء
            </a>
            
            <h1 class="text-2xl font-extrabold text-slate-800">تعديل بيانات العميل</h1>
            <p class="text-slate-500 mt-1 font-medium">تعديل معلومات حساب العميل</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-xl mb-6 flex items-center gap-3">
                <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-exclamation text-white text-sm"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-red-800">هناك أخطاء في البيانات:</h3>
                    <div class="mt-1 text-sm text-red-700">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="main-card p-8">
            <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2"><i class="fas fa-info-circle text-blue-600 text-sm"></i> المعلومات الأساسية</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">
                                    اسم العميل <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name', $customer->name) }}"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none"
                                       required>
                            </div>
                            
                            <div>
                                <label for="username" class="block text-sm font-semibold text-slate-700 mb-2">
                                    اسم المستخدم <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="username" name="username" value="{{ old('username', $customer->username) }}"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none"
                                       required>
                            </div>
                        </div>
                    </div>

                    <!-- Permissions -->
                    <div>
                        <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2"><i class="fas fa-shield-alt text-blue-600 text-sm"></i> الصلاحيات</h3>
                        <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                            <label class="flex items-center">
                                <input type="checkbox" name="can_view_transactions" value="1"
                                       {{ $customer->can_view_transactions ? 'checked' : '' }}
                                       class="ml-2 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-slate-700 font-medium">السماح برؤية جميع المعاملات</span>
                            </label>
                            <p class="text-slate-500 text-xs mt-2 ml-6">إذا لم يتم تحديده، سيرى العميل معاملاته فقط</p>
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2"><i class="fas fa-key text-blue-600 text-sm"></i> تغيير كلمة المرور (اختياري)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                                    كلمة المرور الجديدة
                                </label>
                                <input type="password" id="password" name="password"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none"
                                       placeholder="اتركها فارغة لعدم التغيير">
                            </div>
                            
                            <div>
                                <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">
                                    تأكيد كلمة المرور
                                </label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none"
                                       placeholder="أعد إدخال كلمة المرور">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8">
                    <a href="{{ route('admin.customers.index') }}" 
                       class="px-6 py-3 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 font-semibold text-sm transition">
                        إلغاء
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-slate-900 text-white rounded-xl hover:bg-blue-600 font-bold text-sm transition hover:shadow-lg hover:shadow-blue-500/20 hover:-translate-y-0.5 duration-300 flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
