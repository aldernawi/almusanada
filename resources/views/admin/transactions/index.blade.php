@extends('layouts.website-app')

@section('page_title', 'Transactions')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800">Manage Transactions</h1>
                <p class="text-slate-500 mt-1 font-medium">View and manage statuses and tracking numbers</p>
            </div>
            <a href="{{ route('admin.transactions.create') }}"
                class="bg-slate-900 hover:bg-blue-600 text-white font-bold px-5 py-2.5 rounded-xl transition flex items-center gap-2 text-sm hover:shadow-lg hover:shadow-blue-500/20 hover:-translate-y-0.5 duration-300">
                <i class="fas fa-plus"></i>
                Add New Transaction
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
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Transaction #</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Owner</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Customer (Account)</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Date</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transactions as $transaction)
                        <tr class="table-row hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-bold text-blue-600 flex items-center gap-2">
                                {{ $transaction->transaction_number }}
                                @if($transaction->pdf_path)
                                    <a href="{{ asset($transaction->pdf_path) }}" target="_blank" class="text-red-500 hover:text-red-700" title="Download PDF">
                                        <i class="fas fa-file-pdf text-sm"></i>
                                    </a>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-800 font-medium">{{ $transaction->owner_name }}</td>
                            <td class="px-6 py-4 text-slate-600">
                                @if($transaction->user)
                                    <span class="bg-blue-50 text-blue-700 px-2.5 py-1 rounded-lg text-xs font-bold">{{ $transaction->user->name }}</span>
                                @else
                                    <span class="text-slate-400 text-xs">Not linked</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold
                                            @if($transaction->status == 'Completed') bg-emerald-100 text-emerald-700
                                            @elseif($transaction->status == 'Ready for pickup') bg-blue-100 text-blue-700
                                            @elseif($transaction->status == 'Rejected') bg-red-100 text-red-700
                                            @elseif($transaction->status == 'Suspended') bg-slate-200 text-slate-700
                                            @else bg-amber-100 text-amber-700
                                            @endif">
                                    {{ $transaction->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500 text-sm">{{ $transaction->created_at->format('Y/m/d') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-3">
                                    <button onclick="showStatusModal({{ $transaction->id }}, '{{ $transaction->status }}')"
                                        class="text-emerald-600 hover:text-emerald-800 font-semibold text-sm transition flex items-center gap-1"><i class="fas fa-exchange-alt text-xs"></i> Status</button>
                                    <a href="{{ route('admin.transactions.edit', $transaction->id) }}"
                                        class="text-blue-600 hover:text-blue-800 font-semibold text-sm transition flex items-center gap-1"><i class="fas fa-edit text-xs"></i> Edit</a>
                                    <form action="{{ route('admin.transactions.destroy', $transaction->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-800 font-semibold text-sm transition flex items-center gap-1"><i class="fas fa-trash text-xs"></i> Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">No transactions found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Status Change Modal -->
    <div id="statusModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-6 w-96 shadow-2xl" style="animation: contentSlide 0.3s ease;">
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2"><i class="fas fa-exchange-alt text-blue-600"></i> Change Transaction Status</h3>
            <form id="statusForm" action="{{ route('admin.transactions.update.status') }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" id="transactionId" name="transaction_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">New Status</label>
                    <select name="status" class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition outline-none" required>
                        <option value="">-- Select Status --</option>
                        <option value="Ready for pickup">Ready for pickup</option>
                        <option value="Under Review">Under Review</option>
                        <option value="Completed">Completed</option>
                        <option value="Rejected">Rejected</option>
                        <option value="Suspended">Suspended</option>
                    </select>
                </div>
                
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="hideStatusModal()" 
                            class="px-5 py-2.5 text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50 font-semibold text-sm transition">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-5 py-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 font-semibold text-sm transition hover:shadow-lg">
                        Save Change
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showStatusModal(transactionId, currentStatus) {
            document.getElementById('transactionId').value = transactionId;
            document.getElementById('statusModal').style.display = 'flex';
            
            // Set current status as default
            const statusSelect = document.querySelector('select[name="status"]');
            statusSelect.value = currentStatus;
        }
        
        function hideStatusModal() {
            document.getElementById('statusModal').style.display = 'none';
        }
        
        // Close modal when clicking outside
        document.getElementById('statusModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideStatusModal();
            }
        });
    </script>
@endsection
