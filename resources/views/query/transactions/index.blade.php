<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions - Inquiry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #f1f5f9; position: relative; overflow-x: hidden; }

        .orb { position: fixed; border-radius: 50%; filter: blur(80px); opacity: 0.4; animation: float 20s infinite ease-in-out; pointer-events: none; z-index: 0; }
        .orb-1 { top: -150px; right: -100px; width: 500px; height: 500px; background: radial-gradient(circle, rgba(37, 99, 235, 0.25) 0%, transparent 70%); }
        .orb-2 { bottom: -200px; left: -150px; width: 600px; height: 600px; background: radial-gradient(circle, rgba(30, 58, 138, 0.2) 0%, transparent 70%); animation-delay: -7s; }
        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(30px, -40px) scale(1.05); }
            50% { transform: translate(-20px, 20px) scale(0.95); }
            75% { transform: translate(40px, 30px) scale(1.02); }
        }

        .premium-header {
            background: linear-gradient(135deg, #080c14 0%, #0f172a 50%, #1e293b 100%);
            box-shadow: 0 4px 20px rgba(0,0,0,0.15); position: relative; overflow: hidden;
        }
        .premium-header::before {
            content: ''; position: absolute; top: -60px; right: -60px;
            width: 200px; height: 200px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.15) 0%, transparent 70%);
            border-radius: 50%; pointer-events: none;
        }

        .filter-card { background: white; border-radius: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); border: 1px solid #f1f5f9; position: relative; }
        .filter-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, #2563eb, #3b82f6, #60a5fa, #3b82f6, #2563eb);
            background-size: 200% 100%; animation: shimmer 3s linear infinite; border-radius: 20px 20px 0 0;
        }
        @keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

        .filter-input { padding: 10px 14px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 14px; font-weight: 600; background: #f8fafc; transition: all 0.2s; }
        .filter-input:focus { border-color: #2563eb; background: white; outline: none; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }
        .table-row { transition: all 0.2s; }
        .table-row:hover { background: #f8fafc; }
        .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-processing { background: #dbeafe; color: #1e40af; }
        .status-completed { background: #d1fae5; color: #065f46; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }

        .table-card { background: white; border-radius: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); border: 1px solid #f1f5f9; overflow: hidden; position: relative; }
        .table-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, #2563eb, #3b82f6, #60a5fa, #3b82f6, #2563eb);
            background-size: 200% 100%; animation: shimmer 3s linear infinite;
        }

        .content-fade-in { animation: contentSlide 0.5s cubic-bezier(0.16, 1, 0.3, 1); }
        @keyframes contentSlide { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }

        .btn-logout {
            background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5; transition: all 0.3s;
        }
        .btn-logout:hover { background: rgba(239, 68, 68, 0.25); color: #fee2e2; transform: translateY(-1px); }
    </style>
</head>
<body class="min-h-screen">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <!-- Header -->
    <header class="premium-header relative z-10">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between relative z-10">
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/logo.png') }}" alt="Almusanada" class="h-10 w-auto object-contain" style="filter: brightness(0) invert(1);">
                <div class="w-px h-8 bg-white/20"></div>
                <h1 class="text-lg font-bold text-white">Transactions</h1>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('query.dashboard') }}" class="text-sm font-semibold text-white/70 hover:text-white transition">Home</a>
                <form action="{{ route('query.logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-logout px-4 py-2 rounded-xl font-semibold text-sm">
                        <i class="fas fa-sign-out-alt ml-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-6 py-8 content-fade-in relative z-10">
        <!-- Filters -->
        <div class="filter-card p-6 mb-6">
            <form method="GET" action="{{ route('query.transactions.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Transaction Number</label>
                    <input type="text" name="transaction_number" value="{{ request('transaction_number') }}" class="filter-input w-full" placeholder="MS-1">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Owner Name</label>
                    <input type="text" name="owner_name" value="{{ request('owner_name') }}" class="filter-input w-full" placeholder="Name">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Status</label>
                    <select name="status" class="filter-input w-full">
                        <option value="">All</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Customer</label>
                    <select name="user_id" class="filter-input w-full">
                        <option value="">All</option>
                        @foreach($customers as $id => $name)
                            <option value="{{ $id }}" {{ request('user_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="lg:col-span-4 flex gap-3">
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl font-semibold text-sm hover:bg-blue-700 transition">
                        <i class="fas fa-search ml-1"></i> Search
                    </button>
                    <a href="{{ route('query.transactions.index') }}" class="px-6 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-semibold text-sm hover:bg-slate-200 transition">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Transactions Table -->
        <div class="table-card">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 text-left">
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase">Transaction #</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase">Customer</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase">Owner</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase">Details</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($transactions as $transaction)
                            <tr class="table-row">
                                <td class="px-6 py-4 text-sm font-bold text-slate-800">{{ $transaction->transaction_number }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $transaction->user->name ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $transaction->owner_name }}</td>
                                <td class="px-6 py-4 text-sm text-slate-500 max-w-xs truncate">{{ $transaction->details ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    <span class="status-badge status-{{ $transaction->status }}">{{ $transaction->status }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500">{{ $transaction->created_at->format('Y/m/d') }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('query.transactions.show', $transaction) }}" class="text-blue-600 hover:text-blue-700 font-semibold text-sm">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-400">No transactions</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</body>
</html>
