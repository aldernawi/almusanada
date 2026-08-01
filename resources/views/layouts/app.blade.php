<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Almusanada') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            primary: {
                                DEFAULT: '#1f4277',
                                50: '#eef2f9',
                                100: '#d9e2f0',
                                200: '#b3c5e0',
                                300: '#8da9d0',
                                400: '#678bc0',
                                500: '#4f6fa8',
                                600: '#1f4277',
                                700: '#1a3866',
                                800: '#152e55',
                                900: '#102444',
                            }
                        }
                    }
                }
            }
        </script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <style>
            body {
                font-family: 'Cairo', 'Tajawal', sans-serif;
            }
            /* Custom scrollbar */
            ::-webkit-scrollbar { width: 8px; height: 8px; }
            ::-webkit-scrollbar-track { background: #f1f5f9; }
            ::-webkit-scrollbar-thumb { background: linear-gradient(180deg, #2563eb, #1e40af); border-radius: 4px; }
            ::-webkit-scrollbar-thumb:hover { background: linear-gradient(180deg, #1e40af, #1e3a8a); }
            /* Smooth transitions */
            * { scroll-behavior: smooth; }
            [x-cloak] { display: none !important; }
            /* Fade-in animation */
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(15px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in { animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1); }
            /* Card hover lift */
            .card-lift { transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease; }
            .card-lift:hover { transform: translateY(-3px); box-shadow: 0 12px 30px rgba(0,0,0,0.08); }
            /* Premium card style */
            .main-card {
                background: white; border-radius: 20px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.06); border: 1px solid #f1f5f9;
                transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            }
            .table-row { transition: all 0.2s; }
            .table-row:hover { background: #f8fafc; }
            @keyframes contentSlide { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-slate-100/50">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white/80 backdrop-blur-sm border-b border-gray-100">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="animate-fade-in">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
