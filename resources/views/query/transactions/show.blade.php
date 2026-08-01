<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Details - {{ $transaction->transaction_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #f1f5f9; }
        .detail-card { background: white; border-radius: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); border: 1px solid #f1f5f9; }
        .detail-row { display: flex; justify-content: space-between; align-items: center; padding: 16px 0; border-bottom: 1px solid #f1f5f9; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-size: 14px; font-weight: 600; color: #64748b; }
        .detail-value { font-size: 15px; font-weight: 700; color: #1e293b; }
        .status-badge { padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 700; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-processing { background: #dbeafe; color: #1e40af; }
        .status-completed { background: #d1fae5; color: #065f46; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body class="min-h-screen">
    <!-- Header -->
    <header class="bg-white border-b border-slate-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/logo.png') }}" alt="Almusanada" class="h-10 w-auto object-contain">
                <div class="w-px h-8 bg-slate-200"></div>
                <h1 class="text-lg font-bold text-slate-800">Transaction Details</h1>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('query.transactions.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">
                    <i class="fas fa-arrow-right ml-1"></i> Back
                </a>
                <form action="{{ route('query.logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-blue-50 text-blue-600 rounded-xl font-semibold text-sm hover:bg-blue-100 transition">
                        <i class="fas fa-sign-out-alt ml-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <div class="max-w-3xl mx-auto px-6 py-8">
        <div class="detail-card p-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-800">{{ $transaction->transaction_number }}</h2>
                    <p class="text-slate-500 text-sm mt-1">{{ $transaction->created_at->format('Y/m/d H:i') }}</p>
                </div>
                <span class="status-badge status-{{ $transaction->status }}">{{ $transaction->status }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Customer</span>
                <span class="detail-value">{{ $transaction->user->name ?? '—' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Owner Name</span>
                <span class="detail-value">{{ $transaction->owner_name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Details</span>
                <span class="detail-value text-left max-w-md">{{ $transaction->details ?? '—' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status</span>
                <span class="status-badge status-{{ $transaction->status }}">{{ $transaction->status }}</span>
            </div>

            @if($transaction->pdf_path)
                <div class="mt-6">
                    <a href="{{ asset($transaction->pdf_path) }}" target="_blank" class="inline-flex items-center gap-2 px-6 py-3 bg-red-50 text-red-600 rounded-xl font-semibold text-sm hover:bg-red-100 transition">
                        <i class="fas fa-file-pdf text-lg"></i> View Attached File
                    </a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
