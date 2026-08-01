@extends('layouts.website-app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-3xl">
    <div class="mb-6">
        <a href="{{ route('admin.transactions.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-1 text-sm font-semibold transition">
            <i class="fas fa-arrow-right text-xs"></i>
            العودة إلى قائمة المعاملات
        </a>
    </div>

    <div class="main-card p-8">
        <h1 class="text-2xl font-extrabold text-slate-800 mb-2 pb-4 border-b border-slate-100 flex items-center gap-3"><i class="fas fa-edit text-blue-600"></i> تعديل المعاملة</h1>
        <p class="text-blue-600 font-bold mb-6">رقم المعاملة: {{ $transaction->transaction_number }}</p>

        <form action="{{ route('admin.transactions.update', $transaction->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">العميل (المستفيد)</label>
                    <select name="user_id" class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none">
                        <option value="">-- بدون عميل --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ $transaction->user_id == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} ({{ $customer->username }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">اسم صاحب المعاملة <span class="text-red-500">*</span></label>
                    <input type="text" name="owner_name" value="{{ old('owner_name', $transaction->owner_name) }}"
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none">
                    @error('owner_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">تفاصيل المعاملة</label>
                    <textarea name="details" rows="4"
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none resize-none">{{ old('details', $transaction->details) }}</textarea>
                </div>

                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">تحديث ملف PDF (اختياري)</label>
                    <input type="file" name="pdf" accept="application/pdf"
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none">
                    @if($transaction->pdf_path)
                        <div class="mt-2 flex items-center gap-2 text-sm text-emerald-600">
                            <i class="fas fa-file-pdf"></i>
                            <a href="{{ asset($transaction->pdf_path) }}" target="_blank" class="underline font-semibold">عرض الملف الحالي</a>
                        </div>
                    @endif
                    @error('pdf') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end mt-8">
                <button type="submit" class="bg-slate-900 hover:bg-blue-600 text-white font-bold px-8 py-3 rounded-xl transition hover:shadow-lg hover:shadow-blue-500/20 hover:-translate-y-0.5 duration-300 flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    حفظ التعديلات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
