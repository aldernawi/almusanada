<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة الاستعلامات - المُساندة</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { font-family: 'Cairo', sans-serif; }
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
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            position: relative;
            overflow: hidden;
        }
        .premium-header::before {
            content: ''; position: absolute; top: -60px; right: -60px;
            width: 200px; height: 200px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.15) 0%, transparent 70%);
            border-radius: 50%; pointer-events: none;
        }

        .stat-card {
            background: white; border-radius: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06); border: 1px solid #f1f5f9;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.1);
        }

        .table-card {
            background: white; border-radius: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06); border: 1px solid #f1f5f9;
            overflow: hidden; position: relative;
        }
        .table-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, #2563eb, #3b82f6, #60a5fa, #3b82f6, #2563eb);
            background-size: 200% 100%; animation: shimmer 3s linear infinite;
        }
        @keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

        .table-row { transition: all 0.2s; }
        .table-row:hover { background: #f8fafc; }
        .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-processing { background: #dbeafe; color: #1e40af; }
        .status-completed { background: #d1fae5; color: #065f46; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }

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
                <img src="{{ asset('images/logo.png') }}" alt="المُساندة" class="h-10 w-auto object-contain" style="filter: brightness(0) invert(1);">
                <div class="w-px h-8 bg-white/20"></div>
                <h1 class="text-lg font-bold text-white">لوحة الاستعلامات</h1>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-sm font-semibold text-white/70">{{ Auth::user()->name }}</span>
                <form action="{{ route('query.logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-logout px-4 py-2 rounded-xl font-semibold text-sm">
                        <i class="fas fa-sign-out-alt ml-1"></i> خروج
                    </button>
                </form>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-6 py-8 content-fade-in relative z-10">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="stat-card p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-3xl font-extrabold text-slate-800">{{ $totalTransactions }}</p>
                        <p class="text-sm text-slate-500 font-semibold">إجمالي المعاملات</p>
                    </div>
                </div>
            </div>
            <div class="stat-card p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-users text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-3xl font-extrabold text-slate-800">{{ $totalCustomers }}</p>
                        <p class="text-sm text-slate-500 font-semibold">العملاء</p>
                    </div>
                </div>
            </div>
            <div class="stat-card p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-clock text-amber-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-3xl font-extrabold text-slate-800">{{ $statusStats['Pending'] }}</p>
                        <p class="text-sm text-slate-500 font-semibold">قيد الانتظار</p>
                    </div>
                </div>
            </div>
            <div class="stat-card p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-3xl font-extrabold text-slate-800">{{ $statusStats['Completed'] }}</p>
                        <p class="text-sm text-slate-500 font-semibold">مكتملة</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="table-card">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-800">أحدث المعاملات</h2>
                <a href="{{ route('query.transactions.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700 transition">
                    عرض الكل <i class="fas fa-arrow-left text-xs"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 text-right">
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase">رقم المعاملة</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase">العميل</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase">المالك</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase">الحالة</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentTransactions as $transaction)
                            <tr class="table-row">
                                <td class="px-6 py-4 text-sm font-bold text-slate-800">{{ $transaction->transaction_number }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $transaction->user->name ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $transaction->owner_name }}</td>
                                <td class="px-6 py-4">
                                    <span class="status-badge status-{{ $transaction->status }}">{{ $transaction->status }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500">{{ $transaction->created_at->format('Y/m/d') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400">لا توجد معاملات</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
