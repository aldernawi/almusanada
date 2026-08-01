@extends('layouts.customer')

@section('content')
    <div class="portal-header">
        <div class="portal-brand">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
            </svg>
            Internal Review Panel
        </div>
        <div class="portal-nav">
            <span class="text-white ml-4">Welcome, {{ Auth::user()->name }}</span>
            <form action="{{ route('customer.logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>
    </div>

    <div class="portal-content" style="max-width: 1200px;">
        <!-- Permission Status -->
        <div class="portal-card" style="margin-bottom: 2rem;">
            <div class="flex items-center justify-between mb-6">
                <h1 class="portal-title" style="text-align: left; font-size: 1.75rem;">Your Review Dashboard</h1>
                <div class="permission-badge">
                    @if(Auth::user()->hasPermission('view_all_transactions'))
                        <span class="bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-xl text-sm font-bold">
                            🔓 Full Access
                        </span>
                    @else
                        <span class="bg-amber-50 text-amber-700 px-3 py-1.5 rounded-xl text-sm font-bold">
                            🔒 Limited Access
                        </span>
                    @endif
                </div>
            </div>
            
            <p class="portal-subtitle" style="text-align: left; margin-bottom: 1.5rem;">Track and review all transactions and their statuses</p>

            @if(Auth::user()->hasPermission('view_all_transactions'))
                <div class="bg-emerald-50 border-r-4 border-emerald-500 text-emerald-800 px-6 py-4 rounded-xl mb-6">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-medium">You can view all transactions in the system</span>
                    </div>
                </div>
            @else
                <div class="bg-amber-50 border-r-4 border-amber-500 text-amber-800 px-6 py-4 rounded-xl mb-6">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.932-3L13.932 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.932 3z"></path>
                        </svg>
                        <span class="font-medium">You can only view your assigned transactions</span>
                    </div>
                </div>
            @endif
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="portal-card" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding: 1.5rem;">
                <div class="text-white">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                            <i class="fas fa-list text-white/80"></i>
                        </div>
                        <div class="text-3xl font-bold">{{ $stats['total'] }}</div>
                    </div>
                    <div class="text-white/70 text-sm font-medium">Total Transactions</div>
                </div>
            </div>
            
            <div class="portal-card" style="background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%); padding: 1.5rem;">
                <div class="text-white">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center">
                            <i class="fas fa-clock text-white/90"></i>
                        </div>
                        <div class="text-3xl font-bold">{{ $stats['pending'] }}</div>
                    </div>
                    <div class="text-white/70 text-sm font-medium">Pending</div>
                </div>
            </div>
            
            <div class="portal-card" style="background: linear-gradient(135deg, #1d4ed8 0%, #3b82f6 100%); padding: 1.5rem;">
                <div class="text-white">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center">
                            <i class="fas fa-check text-white/90"></i>
                        </div>
                        <div class="text-3xl font-bold">{{ $stats['approved'] }}</div>
                    </div>
                    <div class="text-white/70 text-sm font-medium">Approved</div>
                </div>
            </div>
            
            <div class="portal-card" style="background: linear-gradient(135deg, #080c14 0%, #0f172a 100%); padding: 1.5rem;">
                <div class="text-white">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                            <i class="fas fa-times text-white/80"></i>
                        </div>
                        <div class="text-3xl font-bold">{{ $stats['rejected'] }}</div>
                    </div>
                    <div class="text-white/70 text-sm font-medium">Rejected</div>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="portal-card">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-slate-800">Transactions List</h2>
                <div class="text-sm">
                    @if(Auth::user()->hasPermission('view_all_transactions'))
                        <span class="text-emerald-600 font-bold">All Transactions</span>
                    @else
                        <span class="text-amber-600 font-bold">Your Transactions Only</span>
                    @endif
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Transaction #</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Customer</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($transactions as $transaction)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 font-bold text-slate-800">{{ $transaction->transaction_number }}</td>
                                <td class="px-6 py-4 text-slate-600">
                                    @if($transaction->user)
                                        {{ $transaction->user->name }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-500">{{ $transaction->created_at->format('Y/m/d') }}</td>
                                <td class="px-6 py-4">
                                    @if($transaction->status === 'pending')
                                        <span class="bg-amber-100 text-amber-700 px-2.5 py-1 rounded-lg text-xs font-bold">Pending</span>
                                    @elseif($transaction->status === 'approved')
                                        <span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-lg text-xs font-bold">Approved</span>
                                    @elseif($transaction->status === 'rejected')
                                        <span class="bg-red-100 text-red-700 px-2.5 py-1 rounded-lg text-xs font-bold">Rejected</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($transaction->pdf_path)
                                        <a href="{{ asset('storage/' . $transaction->pdf_path) }}" 
                                           target="_blank" 
                                           class="text-blue-600 hover:text-blue-700 font-bold flex items-center gap-1 justify-center">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                            </svg>
                                            PDF
                                        </a>
                                    @else
                                        <span class="text-slate-300 text-sm">None</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                    No transactions found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
