<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Almusanada') }} - منصة المساندة الطبية</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
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
        
        <style>
            body {
                font-family: 'Tajawal', sans-serif;
            }
            [x-cloak] { display: none !important; }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .auth-fade-in { animation: fadeIn 0.5s ease-out; }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex">
            <!-- Left Branding Panel -->
            <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800 relative overflow-hidden">
                <!-- Decorative pattern -->
                <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=%2260%22 height=%2260%22 viewBox=%220 0 60 60%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cg fill=%22none%22 fill-rule=%22evenodd%22%3E%3Cg fill=%22%23ffffff%22 fill-opacity=%220.4%22%3E%3Cpath d=%22M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
                <!-- Floating circles -->
                <div class="absolute top-20 right-20 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
                <div class="absolute bottom-20 left-20 w-80 h-80 bg-cyan-400/10 rounded-full blur-3xl"></div>
                
                <div class="relative z-10 flex flex-col justify-between p-12 text-white">
                    <!-- Logo -->
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-white/15 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                            <i class="fas fa-heartbeat text-2xl text-white"></i>
                        </div>
                        <div>
                            <div class="text-xl font-extrabold">المُساندة</div>
                            <div class="text-xs text-white/60">منصة إدارة المطالبات الطبية</div>
                        </div>
                    </div>

                    <!-- Hero Text -->
                    <div class="space-y-6">
                        <h1 class="text-4xl font-extrabold leading-tight">
                            نظام متكامل لإدارة<br>
                            مطالبات التأمين الصحي
                        </h1>
                        <p class="text-lg text-white/70 leading-relaxed max-w-md">
                            منصة آمنة وذكية لاستقبال وتدقيق وموافقة المطالبات الطبية من مزودي الخدمة بكفاءة وسرعة.
                        </p>
                        <!-- Feature pills -->
                        <div class="flex flex-wrap gap-3 pt-2">
                            <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-xl text-sm font-semibold">
                                <i class="fas fa-shield-alt text-green-300"></i>
                                <span>تشفير كامل للبيانات</span>
                            </div>
                            <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-xl text-sm font-semibold">
                                <i class="fas fa-bolt text-yellow-300"></i>
                                <span>تدقيق فوري</span>
                            </div>
                            <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-xl text-sm font-semibold">
                                <i class="fas fa-chart-line text-blue-300"></i>
                                <span>تقارير لحظية</span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="text-sm text-white/40">
                        &copy; {{ date('Y') }} المُساندة. جميع الحقوق محفوظة.
                    </div>
                </div>
            </div>

            <!-- Right Form Panel -->
            <div class="w-full lg:w-1/2 flex flex-col justify-center items-center bg-gradient-to-br from-slate-50 to-blue-50/30 px-6 py-12">
                <!-- Mobile Logo -->
                <div class="lg:hidden flex items-center gap-3 mb-8">
                    <div class="w-12 h-12 bg-gradient-to-br from-primary-600 to-primary-800 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-heartbeat text-2xl text-white"></i>
                    </div>
                    <div>
                        <div class="text-xl font-extrabold text-gray-900">المُساندة</div>
                        <div class="text-xs text-gray-500">منصة إدارة المطالبات الطبية</div>
                    </div>
                </div>

                <div class="w-full max-w-md auth-fade-in">
                    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 px-8 py-10">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
