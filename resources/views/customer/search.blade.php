@extends('layouts.customer')

@section('content')
    <div class="portal-header">
        <div class="portal-brand">
            <i class="fas fa-building-shield text-blue-400 text-lg"></i>
            Almusanada Portal - Customer Dashboard
        </div>
        <div class="portal-nav">
            <span class="text-white ml-4">Welcome, {{ Auth::user()->name }}</span>
            <form action="{{ route('customer.logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>
    </div>

    <div class="portal-content" style="max-width: 1000px;">
        <div class="portal-card">
            <h1 class="portal-title">Your Dashboard</h1>
            <p class="portal-subtitle">Track all your transactions and their statuses in one place</p>

            <div class="search-section"
                style="margin-bottom: 2rem; background: #f8fafc; padding: 1.5rem; border-radius: 16px; border: 1px solid #e2e8f0;">
                <h3 style="margin-bottom: 1rem; font-size: 1rem; color: #1e293b; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-search text-blue-600"></i> Quick search by transaction number
                </h3>
                <form action="{{ route('customer.search.result') }}" method="POST">
                    @csrf
                    <div class="search-box">
                        <input type="text" name="transaction_number" value="{{ $query ?? '' }}" class="search-input"
                            placeholder="Enter transaction number..." required>
                        <button type="submit" class="btn-search">Search</button>
                    </div>
                </form>

                @if (isset($searched))
                    @if ($transaction)
                        <div class="result-card" style="margin-top: 1.5rem; background: white;">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="result-title" style="margin-bottom: 0; border: none;">Search Result</h2>
                                <span class="status-badge 
                                                @if ($transaction->status == 'Completed') status-done
                                                @elseif($transaction->status == 'Rejected') status-rejected
                                                @elseif($transaction->status == 'Suspended') status-paused
                                                @else status-processing @endif">
                                    {{ $transaction->status }}
                                </span>
                            </div>
                            <div class="result-row">
                                <span class="result-label">Transaction Number</span>
                                <span class="result-value trx-number">{{ $transaction->transaction_number }}</span>
                            </div>
                            <div class="result-row">
                                <span class="result-label">Full Name</span>
                                <span class="result-value">{{ $transaction->owner_name }}</span>
                            </div>
                            @if ($transaction->details)
                                <div class="result-details">
                                    <span class="result-label">Details</span>
                                    <p>{{ $transaction->details }}</p>
                                </div>
                            @endif

                            @if($transaction->pdf_path)
                                <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px dashed #e2e8f0;">
                                    <a href="{{ asset($transaction->pdf_path) }}" target="_blank" 
                                       style="display: flex; align-items: center; justify-content: center; gap: 8px; background: linear-gradient(135deg, #0f172a, #1e293b); color: white; padding: 12px; border-radius: 10px; text-decoration: none; font-weight: 700; transition: all 0.3s;">
                                        <i class="fas fa-file-pdf" style="font-size: 18px;"></i>
                                        Download Transaction File (PDF)
                                    </a>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="error-box" style="margin-top: 1.5rem;">
                            <p class="error-title">Transaction not found</p>
                        </div>
                    @endif
                @endif
            </div>

            <h3 style="margin-bottom: 1.5rem; font-size: 1.25rem; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-list-check text-blue-600"></i>
                @if(isset($searched) && $searched)
                    Search Results
                @else
                    All Your Transactions ({{ $transactions->count() }})
                @endif
            </h3>
            
            @if(!isset($searched) || !$searched)
                <div style="background: #eff6ff; padding: 1rem 1.5rem; border-radius: 12px; margin-bottom: 1.5rem; border: 1px solid #dbeafe;">
                    <p style="color: #1e40af; font-size: 0.9rem; margin: 0; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-info-circle"></i>
                        <span><strong>Welcome {{ Auth::user()->name }}!</strong> 
                        You have {{ $transactions->count() }} recorded transactions. Use the quick search above to find a specific transaction.</span>
                    </p>
                </div>
            @endif
            <div style="overflow-x: auto; border-radius: 12px; border: 1px solid #e2e8f0;">
                <table style="width: 100%; border-collapse: collapse; min-width: 600px;">
                    <thead>
                        <tr style="background: #f1f5f9; text-align: left;">
                            <th style="padding: 1rem; font-size: 0.85rem; color: #64748b; border-radius: 8px 0 0 8px; font-weight: 700;">Transaction Number</th>
                            <th style="padding: 1rem; font-size: 0.85rem; color: #64748b; font-weight: 700;">Name</th>
                            <th style="padding: 1rem; font-size: 0.85rem; color: #64748b; font-weight: 700;">Status</th>
                            <th style="padding: 1rem; font-size: 0.85rem; color: #64748b; font-weight: 700;">File</th>
                            <th style="padding: 1rem; font-size: 0.85rem; color: #64748b; border-radius: 0 8px 8px 0; font-weight: 700;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions ?? [] as $trx)
                            <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;">
                                <td style="padding: 1rem; font-weight: 700; color: #2563eb; direction: ltr; text-align: left;">
                                    {{ $trx->transaction_number }}
                                </td>
                                <td style="padding: 1rem; color: #1e293b;">{{ $trx->owner_name }}</td>
                                <td style="padding: 1rem;">
                                    <span class="status-badge 
                                                @if ($trx->status == 'Completed') status-done
                                                @elseif($trx->status == 'Rejected') status-rejected
                                                @elseif($trx->status == 'Suspended') status-paused
                                                @else status-processing @endif">
                                        {{ $trx->status }}
                                    </span>
                                </td>
                                <td style="padding: 1rem; text-align: center;">
                                    @if($trx->pdf_path)
                                        <a href="{{ asset($trx->pdf_path) }}" target="_blank" 
                                           style="display: inline-flex; align-items: center; justify-content: center; gap: 6px; background: #dc2626; color: white; padding: 8px 14px; border-radius: 8px; text-decoration: none; font-size: 0.8rem; font-weight: 600; transition: all 0.3s;"
                                           onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">
                                            <i class="fas fa-file-pdf"></i>
                                            Download PDF
                                        </a>
                                    @else
                                        <span style="color: #9ca3af; font-size: 0.8rem;">No file</span>
                                    @endif
                                </td>
                                <td style="padding: 1rem; color: #9ca3af; font-size: 0.85rem;">
                                    {{ $trx->created_at->format('Y/m/d') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding: 3rem; text-align: center; color: #9ca3af;">
                                    @if(isset($searched) && $searched)
                                        No transaction found with this number
                                    @else
                                        No transactions recorded for you currently
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection