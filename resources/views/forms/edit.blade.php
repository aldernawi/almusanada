<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Form: {{ $form->title }}</h1>
                <a href="{{ route('forms.index') }}" class="text-teal-600 hover:text-teal-900">Back to Forms</a>
            </div>
            <div class="flex space-x-2 space-x-reverse">
                <a href="{{ route('submissions.index', $form) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    View Submissions
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <h2 class="text-lg font-semibold mb-4">Form Settings</h2>
                    <form action="{{ route('forms.update', $form) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Form Title</label>
                            <input type="text" name="title" id="title" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500"
                                value="{{ $form->title }}">
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Form Description</label>
                            <textarea name="description" id="description" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">{{ $form->description }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" id="status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                                <option value="active" {{ $form->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $form->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="archived" {{ $form->status == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="thank_you_message" class="block text-sm font-medium text-gray-700 mb-2">Thank You Message</label>
                            <textarea name="thank_you_message" id="thank_you_message" rows="2"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">{{ $form->thank_you_message }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="require_login" value="1" {{ $form->require_login ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                                <span class="mr-2 text-sm text-gray-700">Require Login</span>
                            </label>
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="enable_captcha" value="1" {{ $form->enable_captcha ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                                <span class="mr-2 text-sm text-gray-700">Enable CAPTCHA</span>
                            </label>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold mb-4">Form Fields</h2>
                    
                    <div id="fields-container" class="space-y-4 mb-4">
                        @foreach($form->fields as $field)
                            <div class="border rounded-lg p-4 bg-gray-50" data-field-id="{{ $field->id }}">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="font-medium">{{ $field->label }}</span>
                                        <span class="text-sm text-gray-500 mr-2">({{ $field->field_type }})</span>
                                        @if($field->required)
                                            <span class="text-red-500 text-sm">*</span>
                                        @endif
                                    </div>
                                    <button onclick="deleteField({{ $field->id }})" class="text-red-600 hover:text-red-900">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t pt-4">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Add New Field</h3>
                        <div class="grid grid-cols-2 gap-2 mb-2">
                            <select id="field-type" class="px-3 py-2 border border-gray-300 rounded-md" onchange="toggleOptionsField()">
                                <option value="text">Text</option>
                                <option value="textarea">Long Text</option>
                                <option value="email">Email</option>
                                <option value="number">Number</option>
                                <option value="date">Date</option>
                                <option value="time">Time</option>
                                <option value="select">Dropdown</option>
                                <option value="checkbox">Checkbox</option>
                                <option value="radio">Radio</option>
                                <option value="file">File</option>
                                <option value="phone">Phone</option>
                                <option value="url">URL</option>
                            </select>
                            <input type="text" id="field-label" placeholder="Field name"
                                class="px-3 py-2 border border-gray-300 rounded-md">
                        </div>
                        <div id="options-container" class="mb-2 hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Options (one per line)</label>
                            <textarea id="field-options" rows="3" placeholder="Option 1&#10;Option 2&#10;Option 3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                        </div>
                        <div class="flex items-center mb-2">
                            <input type="checkbox" id="field-required" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                            <label for="field-required" class="mr-2 text-sm text-gray-700">Required</label>
                        </div>
                        <button onclick="addField()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                            Add Field
                        </button>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold mb-4">Form Link</h2>
                    <div class="bg-gray-100 p-3 rounded mb-4">
                        <code class="text-sm">{{ url('/f/' . $form->slug) }}</code>
                    </div>
                    <button onclick="copyLink()" class="w-full bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700">
                        Copy Link
                    </button>
                </div>

                <div class="bg-white shadow rounded-lg p-6 mt-6">
                    <h2 class="text-lg font-semibold mb-4">Statistics</h2>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Submissions:</span>
                            <span class="font-medium">{{ $form->submissions->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Fields:</span>
                            <span class="font-medium">{{ $form->fields->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Created:</span>
                            <span class="font-medium">{{ $form->created_at->format('Y-m-d') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleOptionsField() {
            const fieldType = document.getElementById('field-type').value;
            const optionsContainer = document.getElementById('options-container');
            
            if (fieldType === 'select' || fieldType === 'checkbox' || fieldType === 'radio') {
                optionsContainer.classList.remove('hidden');
            } else {
                optionsContainer.classList.add('hidden');
            }
        }

        function addField() {
            const fieldType = document.getElementById('field-type').value;
            const fieldLabel = document.getElementById('field-label').value;
            const fieldOptions = document.getElementById('field-options').value;
            const fieldRequired = document.getElementById('field-required').checked;
            
            if (!fieldLabel) {
                alert('Please enter a field name');
                return;
            }

            const data = {
                field_type: fieldType,
                label: fieldLabel,
                required: fieldRequired,
                order: {{ $form->fields->count() }}
            };

            if (fieldType === 'select' || fieldType === 'checkbox' || fieldType === 'radio') {
                if (fieldOptions) {
                    data.options = fieldOptions.split('\n').filter(opt => opt.trim() !== '');
                }
            }

            fetch(`{{ route('forms.fields.store', $form) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                location.reload();
            })
            .catch(error => console.error('Error:', error));
        }

        function deleteField(fieldId) {
            if (confirm('Are you sure you want to delete this field?')) {
                fetch(`/forms/{{ $form->id }}/fields/${fieldId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    location.reload();
                })
                .catch(error => console.error('Error:', error));
            }
        }

        function copyLink() {
            const link = '{{ url('/f/' . $form->slug) }}';
            navigator.clipboard.writeText(link);
            alert('Link copied');
        }
    </script>
</x-app-layout>
