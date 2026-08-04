@extends('layouts.website-app')

@section('page_title', 'Edit User')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <div class="mb-8">
            <a href="{{ route('admin.reviewers.index') }}" 
               class="text-blue-600 hover:text-blue-800 font-medium flex items-center gap-2 mb-6 inline-block">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to users list
            </a>
            
            <h1 class="text-3xl font-bold text-gray-800">Edit {{ $reviewer->role === 'viewer' ? 'Viewer' : 'Reviewer' }} Account</h1>
            <p class="text-gray-600 mt-2">Edit account information</p>

            <div class="mt-4 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg {{ $reviewer->role === 'viewer' ? 'bg-indigo-100 text-indigo-700' : 'bg-green-100 text-green-700' }}">
                <span class="text-sm font-bold">{{ $reviewer->role === 'viewer' ? 'Role: Viewer (View Only)' : 'Role: Reviewer' }}</span>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-800 px-6 py-4 rounded-lg mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">There are errors in the data:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md p-8">
            <form action="{{ route('admin.reviewers.update', $reviewer->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Basic Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name', $reviewer->name) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none"
                                       required>
                            </div>
                            
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                    Username <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="username" name="username" value="{{ old('username', $reviewer->username) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none"
                                       required>
                            </div>
                        </div>
                    </div>

                    <!-- Permissions -->
                    @if($reviewer->role !== 'viewer')
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Permissions</h3>
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <label class="flex items-center">
                                <input type="checkbox" name="can_view_all_transactions" value="1"
                                       {{ $reviewer->can_view_all_transactions ? 'checked' : '' }}
                                       class="ml-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-gray-700">Allow viewing all transactions</span>
                            </label>
                            <p class="text-gray-500 text-xs mt-2 ml-6">If unchecked, the reviewer will only see their assigned transactions</p>
                        </div>
                    </div>
                    @else
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Permissions</h3>
                        <div class="bg-indigo-50 p-4 rounded-lg">
                            <p class="text-sm text-indigo-700">This is a viewer account - the user can only view assigned forms without any actions.</p>
                        </div>
                    </div>
                    @endif

                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Assigned Forms</h3>
                        <div class="max-h-64 overflow-y-auto border border-gray-200 rounded-lg p-4 space-y-2 bg-gray-50">
                            @forelse($forms as $form)
                                <label class="flex items-center gap-3 text-sm text-gray-700">
                                    <input type="checkbox" name="form_ids[]" value="{{ $form->id }}"
                                           {{ in_array($form->id, old('form_ids', $assignedFormIds)) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span>{{ $form->title }}</span>
                                </label>
                            @empty
                                <p class="text-sm text-gray-500">No forms available.</p>
                            @endforelse
                        </div>
                        <p class="text-gray-500 text-xs mt-2">Select the forms this reviewer or viewer can access.</p>
                    </div>

                    <!-- Password -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Change Password (Optional)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    New Password
                                </label>
                                <input type="password" id="password" name="password"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none"
                                       placeholder="Leave empty to keep current">
                            </div>
                            
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    Confirm Password
                                </label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none"
                                       placeholder="Re-enter password">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4 mt-8">
                    <a href="{{ route('admin.reviewers.index') }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
