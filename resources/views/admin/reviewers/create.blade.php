@extends('layouts.website-app')

@section('page_title', 'Add New User')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <div class="mb-6">
            <a href="{{ route('admin.reviewers.index') }}"
                class="text-blue-600 hover:text-blue-800 flex items-center gap-1 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to users list
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-6 pb-4 border-b">Create New Account</h1>

            <form action="{{ route('admin.reviewers.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Role <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-400 transition has-[:checked]:border-green-500 has-[:checked]:bg-green-50">
                                <input type="radio" name="role" value="reviewer" checked class="text-green-600 focus:ring-green-500">
                                <div>
                                    <p class="text-sm font-bold text-gray-800">Reviewer</p>
                                    <p class="text-xs text-gray-500">Can approve/reject and edit notes</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-400 transition has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                                <input type="radio" name="role" value="viewer" class="text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <p class="text-sm font-bold text-gray-800">Viewer</p>
                                    <p class="text-xs text-gray-500">View only, no actions allowed</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none"
                            placeholder="Full name" required>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Username <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="username" value="{{ old('username') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none"
                            placeholder="Username for login to the review panel" required>
                        @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Password <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none"
                            placeholder="Password (at least 6 characters)" required>
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        <p class="text-gray-400 text-xs mt-1">Note: Password is visible here so you can share it with the user</p>
                    </div>

                    <div id="permissions-section">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Permissions</label>
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <label class="flex items-center">
                                <input type="checkbox" name="can_view_all_transactions" value="1"
                                       class="ml-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-gray-700">Allow viewing all transactions</span>
                            </label>
                            <p class="text-gray-500 text-xs mt-2 ml-6">If unchecked, the reviewer will only see their assigned transactions</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Assigned Forms</label>
                        <div class="max-h-64 overflow-y-auto border border-gray-200 rounded-lg p-4 space-y-2 bg-gray-50">
                            @forelse($forms as $form)
                                <label class="flex items-center gap-3 text-sm text-gray-700">
                                    <input type="checkbox" name="form_ids[]" value="{{ $form->id }}"
                                        {{ in_array($form->id, old('form_ids', [])) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span>{{ $form->title }}</span>
                                </label>
                            @empty
                                <p class="text-sm text-gray-500">No forms available.</p>
                            @endforelse
                        </div>
                        <p class="text-gray-500 text-xs mt-2">Select the forms this reviewer or viewer can access.</p>
                    </div>
                </div>

                <div class="flex justify-end mt-8">
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-3 rounded-lg transition">
                        Create Account
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('input[name="role"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                var permSection = document.getElementById('permissions-section');
                permSection.style.display = this.value === 'viewer' ? 'none' : 'block';
            });
        });
    </script>
@endsection
