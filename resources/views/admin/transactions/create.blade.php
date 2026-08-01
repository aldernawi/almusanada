@extends('layouts.website-app')

@section('page_title', 'إضافة معاملة')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <div class="mb-6">
            <a href="{{ route('admin.transactions.index') }}"
                class="text-blue-600 hover:text-blue-800 flex items-center gap-1 text-sm font-semibold transition">
                <i class="fas fa-arrow-right text-xs"></i>
                العودة إلى قائمة المعاملات
            </a>
        </div>

        <div class="main-card p-8">
            <h1 class="text-2xl font-extrabold text-slate-800 mb-6 pb-4 border-b border-slate-100 flex items-center gap-3"><i class="fas fa-file-medical text-blue-600"></i> إضافة معاملة جديدة</h1>

            <form action="{{ route('admin.transactions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">العميل (المستفيد) <span class="text-red-500">*</span></label>
                        <select name="user_id"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none"
                            required>
                            <option value="">-- اختر العميل --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('user_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} ({{ $customer->username }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        @if($customers->isEmpty())
                            <p class="text-amber-600 text-xs mt-1">لا يوجد عملاء. <a href="{{ route('admin.customers.create') }}" class="underline font-bold">أنشئ حساب عميل أولاً</a></p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">اسم صاحب المعاملة <span class="text-red-500">*</span></label>
                        <input type="text" name="owner_name" value="{{ old('owner_name') }}"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none"
                            placeholder="أدخل الاسم الكامل">
                        @error('owner_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">تفاصيل المعاملة</label>
                        <textarea name="details" rows="4"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none resize-none"
                            placeholder="أدخل تفاصيل المعاملة...">{{ old('details') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">حالة المعاملة <span class="text-red-500">*</span></label>
                        <select name="status"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none"
                            required>
                            <option value="">-- اختر الحالة --</option>
                            <option value="جاهزة للاستلام" {{ old('status') == 'جاهزة للاستلام' ? 'selected' : '' }}>جاهزة للاستلام</option>
                            <option value="قيد المراجعة" {{ old('status') == 'قيد المراجعة' ? 'selected' : '' }}>قيد المراجعة</option>
                            <option value="تمت" {{ old('status') == 'تمت' ? 'selected' : '' }}>تمت</option>
                            <option value="مرفوضة" {{ old('status') == 'مرفوضة' ? 'selected' : '' }}>مرفوضة</option>
                            <option value="موقوفة" {{ old('status') == 'موقوفة' ? 'selected' : '' }}>موقوفة</option>
                        </select>
                        @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">إرفاق ملف PDF (اختياري)</label>
                        <input type="file" name="pdf" accept="application/pdf"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none">
                        @error('pdf') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-end mt-8">
                    <button type="submit"
                        class="bg-slate-900 hover:bg-blue-600 text-white font-bold px-8 py-3 rounded-xl transition hover:shadow-lg hover:shadow-blue-500/20 hover:-translate-y-0.5 duration-300 flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        حفظ وتوليد رقم المعاملة
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
