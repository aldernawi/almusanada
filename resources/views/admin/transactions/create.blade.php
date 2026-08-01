@extends('layouts.website-app')

@section('page_title', 'Add Transaction')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <div class="mb-6">
            <a href="{{ route('admin.transactions.index') }}"
                class="text-blue-600 hover:text-blue-800 flex items-center gap-1 text-sm font-semibold transition">
                <i class="fas fa-arrow-right text-xs"></i>
                Back to transactions list
            </a>
        </div>

        <div class="main-card p-8">
            <h1 class="text-2xl font-extrabold text-slate-800 mb-6 pb-4 border-b border-slate-100 flex items-center gap-3"><i class="fas fa-file-medical text-blue-600"></i> Add New Transaction</h1>

            <form action="{{ route('admin.transactions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Customer (Beneficiary) <span class="text-red-500">*</span></label>
                        <select name="user_id"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none"
                            required>
                            <option value="">-- Select Customer --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('user_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} ({{ $customer->username }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        @if($customers->isEmpty())
                            <p class="text-amber-600 text-xs mt-1">No customers found. <a href="{{ route('admin.customers.create') }}" class="underline font-bold">Create a customer account first</a></p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Transaction Owner Name <span class="text-red-500">*</span></label>
                        <input type="text" name="owner_name" value="{{ old('owner_name') }}"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none"
                            placeholder="Enter full name">
                        @error('owner_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Transaction Details</label>
                        <textarea name="details" rows="4"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none resize-none"
                            placeholder="Enter transaction details...">{{ old('details') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Transaction Status <span class="text-red-500">*</span></label>
                        <select name="status"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none"
                            required>
                            <option value="">-- Select Status --</option>
                            <option value="Ready for pickup" {{ old('status') == 'Ready for pickup' ? 'selected' : '' }}>Ready for pickup</option>
                            <option value="Under Review" {{ old('status') == 'Under Review' ? 'selected' : '' }}>Under Review</option>
                            <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                            <option value="Rejected" {{ old('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="Suspended" {{ old('status') == 'Suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                        @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Attach PDF File (Optional)</label>
                        <input type="file" name="pdf" accept="application/pdf"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none">
                        @error('pdf') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-end mt-8">
                    <button type="submit"
                        class="bg-slate-900 hover:bg-blue-600 text-white font-bold px-8 py-3 rounded-xl transition hover:shadow-lg hover:shadow-blue-500/20 hover:-translate-y-0.5 duration-300 flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        Save and Generate Transaction Number
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
