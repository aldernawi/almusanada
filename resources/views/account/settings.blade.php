<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Account & Appearance Settings') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="flex gap-4 mb-6">
                <a href="{{ route('dashboard') }}" class="text-teal-600 hover:text-teal-800 text-sm font-bold flex items-center gap-1">
                    <i class="fas fa-arrow-left text-xs"></i> Back to Dashboard
                </a>
            </div>

            <!-- Settings Card -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8">
                <h2 class="text-lg font-bold text-gray-900 border-b pb-3 mb-6"><i class="fas fa-cog ml-2 text-teal-500"></i> General Dashboard Settings</h2>

                <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Settings saved successfully!');">
                    <div class="space-y-6">
                        <!-- Language and Tajawal font -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Default Language</label>
                                <select class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 text-sm">
                                    <option value="ar">Arabic - Tajawal Font</option>
                                    <option value="en" selected>English</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Timezone</label>
                                <select class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 text-sm">
                                    <option value="Asia/Riyadh" selected>(GMT+03:00) Riyadh</option>
                                    <option value="Africa/Cairo">(GMT+02:00) Cairo</option>
                                </select>
                            </div>
                        </div>

                        <!-- Interface styling options -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dashboard Layout</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="border-2 border-teal-500 rounded-xl p-4 flex items-center justify-between cursor-pointer bg-teal-50/50">
                                    <div class="flex items-center gap-3">
                                        <div class="w-4 h-4 bg-teal-600 rounded-full flex items-center justify-center text-white text-[10px]">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <span class="font-bold text-sm text-gray-900">Triple Grid Layout (JotForm Style)</span>
                                    </div>
                                    <i class="fas fa-th-large text-teal-500 text-lg"></i>
                                </label>
                                <label class="border-2 border-gray-100 rounded-xl p-4 flex items-center justify-between cursor-pointer hover:border-teal-300">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" name="layout" class="text-teal-600 focus:ring-teal-500">
                                        <span class="font-bold text-sm text-gray-800">Simplified Traditional Layout</span>
                                    </div>
                                    <i class="fas fa-list text-gray-400 text-lg"></i>
                                </label>
                            </div>
                        </div>

                        <!-- Notification Email settings -->
                        <div>
                            <div class="checkbox-wrapper flex items-center gap-2">
                                <input type="checkbox" checked id="notify" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                                <label for="notify" class="font-bold text-sm text-gray-700 cursor-pointer">Receive instant email notification for each new submission from service providers</label>
                            </div>
                        </div>

                        <!-- Submit button -->
                        <div class="pt-4 border-t flex justify-end">
                            <button type="submit" class="bg-gradient-to-l from-teal-600 to-cyan-600 hover:shadow-lg text-white font-bold text-sm px-6 py-2.5 rounded-xl transition flex items-center gap-2">
                                <i class="fas fa-save"></i> Save All Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
