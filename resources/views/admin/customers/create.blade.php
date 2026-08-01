@extends('layouts.website-app')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <div class="mb-6">
            <a href="{{ route('admin.customers.index') }}"
                class="text-blue-600 hover:text-blue-800 flex items-center gap-1 text-sm font-semibold transition">
                <i class="fas fa-arrow-right text-xs"></i>
                Back to customers list
            </a>
        </div>

        <div class="main-card p-8">
            <h1 class="text-2xl font-extrabold text-slate-800 mb-6 pb-4 border-b border-slate-100 flex items-center gap-3"><i class="fas fa-user-plus text-emerald-600"></i> Create New Customer Account</h1>

            <form action="{{ route('admin.customers.store') }}" method="POST">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Customer Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none"
                            placeholder="Full name" required>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Username <span class="text-red-500">*</span></label>
                        <input type="text" name="username" value="{{ old('username') }}"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none"
                            placeholder="Username for inquiry portal login" required>
                        @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Password <span class="text-red-500">*</span></label>
                        <input type="text" name="password"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none"
                            placeholder="Password (at least 6 characters)" required>
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        <p class="text-slate-400 text-xs mt-1">Note: Password is visible here so you can share it with the customer</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Permissions</label>
                        <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                            <label class="flex items-center">
                                <input type="checkbox" name="can_view_transactions" value="1"
                                       class="ml-2 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-slate-700 font-medium">Allow viewing all transactions</span>
                            </label>
                            <p class="text-slate-500 text-xs mt-2 ml-6">If unchecked, the customer will only see their own transactions</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-8">
                    <button type="submit"
                        class="bg-slate-900 hover:bg-blue-600 text-white font-bold px-8 py-3 rounded-xl transition hover:shadow-lg hover:shadow-blue-500/20 hover:-translate-y-0.5 duration-300 flex items-center gap-2">
                        <i class="fas fa-check"></i>
                        Create Account
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
