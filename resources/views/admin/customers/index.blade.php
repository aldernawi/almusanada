@extends('layouts.website-app')

@section('page_title', 'Customer Management')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-5xl">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800">Manage Customer Accounts</h1>
                <p class="text-slate-500 mt-1 font-medium">Create and manage inquiry portal accounts</p>
            </div>
            <a href="{{ route('admin.customers.create') }}"
                class="bg-slate-900 hover:bg-blue-600 text-white font-bold px-5 py-2.5 rounded-xl transition flex items-center gap-2 text-sm hover:shadow-lg hover:shadow-blue-500/20 hover:-translate-y-0.5 duration-300">
                <i class="fas fa-user-plus"></i>
                Add New Customer
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

        <div class="main-card overflow-hidden">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Username</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Permissions</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Created Date</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($customers as $customer)
                        <tr class="table-row hover:bg-slate-50 transition">
                            <td class="px-6 py-4 text-slate-800 font-semibold">{{ $customer->name }}</td>
                            <td class="px-6 py-4 text-blue-600 font-bold">{{ $customer->username }}</td>
                            <td class="px-6 py-4 text-slate-600">
                                @if($customer->can_view_transactions)
                                    <span class="bg-blue-50 text-blue-700 px-2.5 py-1 rounded-lg text-xs font-bold">View All Transactions</span>
                                @else
                                    <span class="text-slate-400 text-xs">Own transactions only</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-500 text-sm">{{ $customer->created_at->format('Y/m/d') }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <a href="{{ route('admin.customers.edit', $customer->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 font-semibold text-sm transition flex items-center gap-1">
                                        <i class="fas fa-edit text-xs"></i>
                                        Edit
                                    </a>
                                    
                                    <span class="text-slate-200">|</span>
                                    
                                    <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this account?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-800 font-semibold text-sm transition flex items-center gap-1">
                                            <i class="fas fa-trash text-xs"></i>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">No customer accounts found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
