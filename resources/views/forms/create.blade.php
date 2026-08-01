<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <a href="{{ route('forms.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-teal-600 transition mb-4">
                <i class="fas fa-arrow-left"></i> Back to Forms
            </a>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Create New Form</h1>
            <p class="text-gray-500 mt-1">Enter the basic information, then add fields on the edit page</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-l from-teal-500 to-cyan-600 px-6 py-4">
                <h2 class="text-white font-bold text-lg flex items-center gap-2"><i class="fas fa-file-circle-plus"></i> Form Information</h2>
            </div>
            <div class="p-6">
                <form action="{{ route('forms.store') }}" method="POST">
                    @csrf

                    <div class="mb-5">
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Form Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" required
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100 transition"
                            placeholder="e.g. Health Insurance Claim - Emergency Visit"
                            value="{{ old('title') }}">
                        @error('title')
                            <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Form Description</label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100 transition resize-none"
                            placeholder="A brief description shown at the top of the form">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="thank_you_message" class="block text-sm font-semibold text-gray-700 mb-2">Thank You Message</label>
                        <textarea name="thank_you_message" id="thank_you_message" rows="2"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100 transition resize-none"
                            placeholder="Thank you! Your form has been submitted successfully.">{{ old('thank_you_message') }}</textarea>
                        @error('thank_you_message')
                            <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('forms.index') }}" class="px-6 py-3 rounded-xl text-gray-600 hover:bg-gray-100 transition font-semibold">Cancel</a>
                        <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-l from-teal-600 to-cyan-600 text-white px-6 py-3 rounded-xl hover:shadow-lg hover:scale-105 transition-all duration-300 font-semibold">
                            <i class="fas fa-plus"></i>
                            Create Form
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
