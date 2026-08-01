<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('System Usage Statistics') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="flex gap-4 mb-6">
                <a href="{{ route('dashboard') }}" class="text-teal-600 hover:text-teal-800 text-sm font-bold flex items-center gap-1">
                    <i class="fas fa-arrow-left text-xs"></i> Back to Dashboard
                </a>
            </div>

            <!-- Profile & Active Package -->
            <div class="bg-gradient-to-l from-teal-600 to-cyan-600 rounded-2xl p-7 mb-8 text-white shadow-xl relative overflow-hidden">
                <div class="absolute top-0 left-0 w-48 h-48 bg-white/5 rounded-full blur-3xl"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <span class="bg-white/20 text-white px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Overview</span>
                        <h1 class="text-3xl font-extrabold mt-3">System Statistics & Usage Rates</h1>
                        <p class="text-teal-100 text-sm mt-1">Track uploaded data volume, active forms, and received submissions in real time.</p>
                    </div>
                    <div class="hidden md:block">
                        <div class="w-20 h-20 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                            <i class="fas fa-chart-line text-4xl text-yellow-300"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Consumption Counters -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8 space-y-8">
                <h2 class="text-lg font-bold text-gray-900 border-b pb-3"><i class="fas fa-chart-pie ml-2 text-teal-500"></i> Active Counters</h2>

                <!-- Forms limit -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-bold text-gray-700 text-sm">Forms count</span>
                        <span class="text-sm font-bold text-gray-900">{{ $totalForms }} active forms</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3">
                        <div class="bg-teal-600 h-3 rounded-full transition-all duration-500" style="width: {{ min(100, $totalForms * 10) }}%"></div>
                    </div>
                </div>

                <!-- Submissions limit -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-bold text-gray-700 text-sm">Total submissions</span>
                        <span class="text-sm font-bold text-gray-900">{{ $totalSubmissions }} submissions received</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3">
                        <div class="bg-green-500 h-3 rounded-full transition-all duration-500" style="width: {{ min(100, $totalSubmissions) }}%"></div>
                    </div>
                </div>

                <!-- Upload Limit -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-bold text-gray-700 text-sm">Total upload space (Attachments & Files)</span>
                        <span class="text-sm font-bold text-gray-900">{{ $uploadSizeMB }} MB</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3">
                        <div class="bg-cyan-500 h-3 rounded-full transition-all duration-500" style="width: {{ min(100, $uploadSizeMB * 10) }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Features Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-start gap-4 card-lift hover:shadow-md">
                    <div class="w-12 h-12 bg-gradient-to-br from-teal-50 to-teal-100 rounded-xl flex items-center justify-center text-teal-600 text-lg flex-shrink-0">
                        <i class="fas fa-lock-open"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-sm">Data Protection</h3>
                        <p class="text-xs text-gray-500 mt-1">Enable reCAPTCHA verification and encrypt uploaded attachment files from service providers to protect data confidentiality.</p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-start gap-4 card-lift hover:shadow-md">
                    <div class="w-12 h-12 bg-gradient-to-br from-cyan-50 to-cyan-100 rounded-xl flex items-center justify-center text-cyan-600 text-lg flex-shrink-0">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-sm">Developer API Integration</h3>
                        <p class="text-xs text-gray-500 mt-1">Built-in API integration system enabling third-party developers to pull and audit data in a fully automated way.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
