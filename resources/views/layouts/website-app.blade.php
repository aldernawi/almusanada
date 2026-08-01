<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'الشركة المساندة') }} - لوحة التحكّم</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f1f5f9;
        }

        /* Premium Sidebar */
        .premium-sidebar {
            background: linear-gradient(180deg, #080c14 0%, #0f172a 50%, #1e293b 100%);
            position: relative;
            overflow: hidden;
        }

        .premium-sidebar::before {
            content: '';
            position: absolute;
            top: -100px; right: -100px;
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .premium-sidebar::after {
            content: '';
            position: absolute;
            bottom: -80px; left: -80px;
            width: 250px; height: 250px;
            background: radial-gradient(circle, rgba(30, 58, 138, 0.12) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 16px;
            border-radius: 12px;
            color: rgba(255, 255, 255, 0.55);
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.06);
            color: white;
            transform: translateX(-3px);
        }

        .sidebar-link.active {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.2) 0%, rgba(30, 58, 138, 0.15) 100%);
            color: white;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.15);
            border: 1px solid rgba(37, 99, 235, 0.2);
        }

        .sidebar-link.active::before {
            content: '';
            position: absolute;
            right: 0; top: 50%;
            transform: translateY(-50%);
            width: 3px; height: 60%;
            background: linear-gradient(180deg, #2563eb, #3b82f6);
            border-radius: 4px;
        }

        .sidebar-link svg {
            flex-shrink: 0;
            transition: transform 0.3s;
        }

        .sidebar-link:hover svg {
            transform: scale(1.1);
        }

        /* Premium Header */
        .premium-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            box-shadow: 0 1px 0 rgba(15,23,42,0.06), 0 4px 20px rgba(0,0,0,0.03);
        }

        /* Main Card */
        .main-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            border: 1px solid #f1f5f9;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .main-card:hover {
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Logo glow */
        .logo-glow {
            filter: drop-shadow(0 0 15px rgba(37, 99, 235, 0.2));
            animation: gentlePulse 4s infinite ease-in-out;
        }

        @keyframes gentlePulse {
            0%, 100% { filter: drop-shadow(0 0 15px rgba(37, 99, 235, 0.2)); }
            50% { filter: drop-shadow(0 0 25px rgba(37, 99, 235, 0.35)); }
        }

        /* User avatar */
        .user-avatar {
            background: linear-gradient(135deg, #1e293b 0%, #080c14 100%);
            box-shadow: 0 4px 12px rgba(8, 12, 20, 0.2);
        }

        /* Content animation */
        .content-fade-in {
            animation: contentSlide 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes contentSlide {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body class="bg-slate-100 overflow-hidden">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-72 premium-sidebar p-6 flex flex-col hidden lg:flex z-50">
            <!-- Brand -->
            <div class="mb-10 px-2 flex justify-center relative z-10">
                <img src="{{ asset('images/logo.png') }}" alt="شركة المساندة" class="h-28 w-auto logo-glow" style="filter: brightness(0) invert(1) drop-shadow(0 0 15px rgba(37, 99, 235, 0.2));">
            </div>

            <!-- Navigation -->
            <nav class="space-y-1 flex-1 relative z-10">
                <p class="text-xs font-bold text-white/30 uppercase tracking-wider px-2 mb-3">القائمة الرئيسية</p>
                <a href="{{ route('admin.dashboard') }}"
                    class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    الرئيسية
                </a>
                <a href="{{ route('admin.transactions.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    المعاملات
                </a>
                <a href="{{ route('admin.services.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                    </svg>
                    خدماتنا
                </a>
                <a href="{{ route('admin.regulations.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.regulations.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    اللوائح
                </a>
                <a href="{{ route('admin.customers.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 01-9-4.354m3 5.918A8.982 8.982 0 0121 16v-1a6 6 0 00-6-6h-1.071"/>
                    </svg>
                    العملاء
                </a>
                <a href="{{ route('admin.employees.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    الفريق
                </a>
                <a href="{{ route('admin.reviewers.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.reviewers.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    المراجعون والاطلاع
                </a>
            </nav>

            <!-- Logout -->
            <div class="pt-6 border-t border-white/10 relative z-10">
                <a href="{{ url('/') }}" target="_blank"
                    class="sidebar-link mb-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    زيارة الموقع
                </a>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="sidebar-link w-full" style="color: #fca5a5;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        تسجيل الخروج
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-h-screen">
            <!-- Header -->
            <header class="h-[68px] premium-header border-b border-slate-100 flex items-center justify-between px-6 lg:px-8 shrink-0">
                <!-- Mobile Brand -->
                <div class="lg:hidden flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="الشركة المساندة" class="h-16 w-auto">
                </div>

                <!-- Breadcrumb -->
                <div class="hidden lg:flex items-center gap-2 text-sm text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>لوحة التحكّم</span>
                    <svg class="w-3 h-3 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-slate-800 font-semibold">@yield('page_title', 'الرئيسية')</span>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-3">
                    <!-- User Info -->
                    <div class="flex items-center gap-3 pr-3 border-r border-slate-100">
                        <div class="w-9 h-9 user-avatar rounded-full flex items-center justify-center text-white font-bold text-sm">
                            {{ mb_substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="hidden md:block">
                            <p class="text-sm font-bold text-slate-800 leading-tight">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-400">مدير النظام</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6 lg:p-8 content-fade-in">
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>