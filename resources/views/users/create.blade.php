<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('users.store') }}">
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="role" :value="__('Role')" />
                            <select id="role" name="role" class="block mt-1 w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm">
                                <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
                                <option value="reviewer" {{ old('role') === 'reviewer' ? 'selected' : '' }}>Reviewer</option>
                                <option value="viewer" {{ old('role') === 'viewer' ? 'selected' : '' }}>Viewer</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <div id="assigned-forms-section" class="mb-4" style="display: none;">
                            <x-input-label :value="__('Assigned Forms')" />
                            <div class="mt-2 max-h-56 overflow-y-auto rounded-md border border-gray-200 bg-gray-50 p-3 space-y-2">
                                @forelse($forms as $form)
                                    <label class="flex items-center gap-2 text-sm text-gray-700">
                                        <input type="checkbox" name="form_ids[]" value="{{ $form->id }}"
                                            {{ in_array($form->id, old('form_ids', [])) ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                                        <span>{{ $form->title }}</span>
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-500">No forms available.</p>
                                @endforelse
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Select forms this reviewer or viewer can access.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <x-input-label for="form_limit" :value="__('Forms Limit')" />
                                <x-text-input id="form_limit" class="block mt-1 w-full" type="number" name="form_limit" :value="old('form_limit', 10)" min="1" />
                            </div>
                            <div>
                                <x-input-label for="submission_limit" :value="__('Submissions Limit')" />
                                <x-text-input id="submission_limit" class="block mt-1 w-full" type="number" name="submission_limit" :value="old('submission_limit', 1000)" min="1" />
                            </div>
                            <div>
                                <x-input-label for="upload_limit_mb" :value="__('Upload Limit (MB)')" />
                                <x-text-input id="upload_limit_mb" class="block mt-1 w-full" type="number" name="upload_limit_mb" :value="old('upload_limit_mb', 100)" min="1" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('users.index') }}" class="ml-4 text-gray-600 hover:text-gray-900">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Create User') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const roleSelect = document.getElementById('role');
        const assignedFormsSection = document.getElementById('assigned-forms-section');

        function syncAssignedFormsVisibility() {
            assignedFormsSection.style.display = ['reviewer', 'viewer'].includes(roleSelect.value) ? 'block' : 'none';
        }

        roleSelect.addEventListener('change', syncAssignedFormsVisibility);
        syncAssignedFormsVisibility();
    </script>
</x-app-layout>
