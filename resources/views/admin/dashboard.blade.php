@extends('layouts.website-app')

@section('page_title', 'Dashboard')

@section('content')
    <div class="max-w-6xl mx-auto space-y-8">

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-xl flex items-center gap-3" style="animation: contentSlide 0.4s ease;">
                <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-check text-white text-sm"></i>
                </div>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="main-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-blue-600 text-lg"></i>
                    </div>
                    <span class="text-xs font-bold text-blue-500 bg-blue-50 px-2.5 py-1 rounded-lg">Customers</span>
                </div>
                <p class="text-3xl font-extrabold text-slate-800">{{ $customersCount }}</p>
                <p class="text-sm text-slate-500 mt-1 font-medium">Total Customers</p>
            </div>

            <div class="main-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-file-medical text-emerald-600 text-lg"></i>
                    </div>
                    <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg">Transactions</span>
                </div>
                <p class="text-3xl font-extrabold text-slate-800">{{ $transactionsCount }}</p>
                <p class="text-sm text-slate-500 mt-1 font-medium">Total Transactions</p>
            </div>

            <div class="main-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-gavel text-amber-600 text-lg"></i>
                    </div>
                    <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-lg">Regulations</span>
                </div>
                <p class="text-3xl font-extrabold text-slate-800">{{ $regulationsCount }}</p>
                <p class="text-sm text-slate-500 mt-1 font-medium">Total Regulations</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="main-card p-6 border-t-4 border-t-slate-700">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-plus text-slate-700"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Customer Management</h3>
                </div>
                <p class="text-sm text-slate-500 mb-4">Create and manage customer accounts</p>
                <div class="flex gap-2">
                    <a href="{{ route('admin.customers.create') }}" class="flex-1 text-center bg-slate-900 hover:bg-blue-600 text-white text-sm font-bold px-4 py-2 rounded-lg transition duration-300 hover:shadow-lg hover:shadow-blue-500/20">Add</a>
                    <a href="{{ route('admin.customers.index') }}" class="flex-1 text-center bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold px-4 py-2 rounded-lg transition">View All</a>
                </div>
            </div>

            <div class="main-card p-6 border-t-4 border-t-slate-700">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-file-medical text-slate-700"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Transaction Management</h3>
                </div>
                <p class="text-sm text-slate-500 mb-4">Add and track tracking numbers</p>
                <div class="flex gap-2">
                    <a href="{{ route('admin.transactions.create') }}" class="flex-1 text-center bg-slate-900 hover:bg-blue-600 text-white text-sm font-bold px-4 py-2 rounded-lg transition duration-300 hover:shadow-lg hover:shadow-blue-500/20">Add</a>
                    <a href="{{ route('admin.transactions.index') }}" class="flex-1 text-center bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold px-4 py-2 rounded-lg transition">View All</a>
                </div>
            </div>

            <div class="main-card p-6 border-t-4 border-t-slate-700">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-gavel text-slate-700"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Regulation Management</h3>
                </div>
                <p class="text-sm text-slate-500 mb-4">Add company regulations to the homepage</p>
                <div class="flex gap-2">
                    <a href="{{ route('admin.regulations.create') }}" class="flex-1 text-center bg-slate-900 hover:bg-blue-600 text-white text-sm font-bold px-4 py-2 rounded-lg transition duration-300 hover:shadow-lg hover:shadow-blue-500/20">Add</a>
                    <a href="{{ route('admin.regulations.index') }}" class="flex-1 text-center bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold px-4 py-2 rounded-lg transition">View All</a>
                </div>
            </div>
        </div>

        <!-- Settings Section -->
        <div class="main-card p-8">
            <div class="flex items-center gap-3 mb-6 pb-5 border-b border-slate-100">
                <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-cog text-slate-600"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Site Settings</h2>
                    <p class="text-sm text-slate-500">Customize homepage content</p>
                </div>
            </div>

            <form action="{{ route('admin.dashboard.update') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Company / Site Name</label>
                        <input type="text" name="company_name" value="{{ $profile->company_name }}"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-3 focus:ring-blue-100 transition outline-none text-slate-800">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Primary Color</label>
                        <div class="flex gap-3">
                            <input type="color" name="primary_color" value="{{ $profile->primary_color }}"
                                class="h-12 w-16 rounded-xl border border-slate-200 cursor-pointer">
                            <input type="text" value="{{ $profile->primary_color }}" readonly
                                class="flex-1 px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 text-slate-600 font-mono text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Font Size</label>
                        <select name="font_size" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-3 focus:ring-blue-100 transition outline-none">
                            <option value="12px" {{ $profile->font_size === '12px' ? 'selected' : '' }}>Extra Small (12px)</option>
                            <option value="13px" {{ $profile->font_size === '13px' ? 'selected' : '' }}>Small (13px)</option>
                            <option value="14px" {{ $profile->font_size === '14px' ? 'selected' : '' }}>Medium Small (14px)</option>
                            <option value="15px" {{ $profile->font_size === '15px' ? 'selected' : '' }}>Medium (15px)</option>
                            <option value="16px" {{ $profile->font_size === '16px' ? 'selected' : '' }}>Medium Large (16px)</option>
                            <option value="17px" {{ $profile->font_size === '17px' ? 'selected' : '' }}>Large (17px)</option>
                            <option value="18px" {{ $profile->font_size === '18px' ? 'selected' : '' }}>Extra Large (18px)</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Hero Section Title</label>
                        <input type="text" name="hero_title" value="{{ $profile->hero_title }}"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-3 focus:ring-blue-100 transition outline-none">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Hero Section Description</label>
                        <textarea name="hero_description" rows="3"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-3 focus:ring-blue-100 transition outline-none resize-none">{{ $profile->hero_description }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <h3 class="text-base font-bold text-slate-800 mb-4 pt-2 border-t border-slate-100">Service Cards</h3>
                    </div>

                    @foreach([1,2,3] as $i)
                    <div class="p-5 bg-slate-50 rounded-xl border border-slate-200">
                        <p class="font-bold text-blue-600 mb-3 text-sm flex items-center gap-2">
                            <span class="w-5 h-5 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs">{{ $i }}</span>
                            Card {{ $i == 1 ? 'One' : ($i == 2 ? 'Two' : 'Three') }}
                        </p>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Title</label>
                                <input type="text" name="service_{{ $i }}_title" value="{{ $profile->{'service_' . $i . '_title'} }}"
                                    class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Description</label>
                                <textarea name="service_{{ $i }}_description" rows="2"
                                    class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none resize-none">{{ $profile->{'service_' . $i . '_description'} }}</textarea>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Footer Text</label>
                        <input type="text" name="footer_text" value="{{ $profile->footer_text }}"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-3 focus:ring-blue-100 transition outline-none">
                    </div>
                </div>

                <div class="flex justify-end mt-8 pt-6 border-t border-slate-100">
                    <button type="submit"
                        class="bg-slate-900 hover:bg-blue-600 text-white font-bold py-3 px-10 rounded-xl transition duration-300 shadow-md flex items-center gap-2 hover:shadow-lg hover:shadow-blue-500/20 hover:-translate-y-0.5">
                        <i class="fas fa-check"></i>
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
