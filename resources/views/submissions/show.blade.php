<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Submission Details #{{ $submission->id }}</h1>
            <a href="{{ route('submissions.index', $form) }}" class="text-teal-600 hover:text-teal-900">Back to Submissions</a>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <span class="text-sm text-gray-500">User:</span>
                    <span class="font-medium">{{ $submission->user ? $submission->user->name : 'Guest' }}</span>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Submitted:</span>
                    <span class="font-medium">{{ $submission->submitted_at->format('Y-m-d H:i') }}</span>
                </div>
                <div>
                    <span class="text-sm text-gray-500">IP Address:</span>
                    <span class="font-medium">{{ $submission->ip_address }}</span>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Status:</span>
                    <span class="font-medium">{{ $submission->status }}</span>
                </div>
            </div>

            <hr class="my-6">

            <h2 class="text-lg font-semibold mb-4">Submission Data</h2>
            <div class="space-y-4">
                @foreach($submission->submissionData as $data)
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ $data->field->label }}
                            @if($data->field->required)
                                <span class="text-red-500">*</span>
                            @endif
                        </label>
                        @if($data->field->field_type === 'file' && $data->file_data)
                            <div class="mt-2">
                                @if(isset($data->file_data['path']))
                                    <a href="{{ route('submissions.attachments.show', [$form, $submission, $data]) }}" class="text-teal-600 hover:text-teal-900">
                                        {{ $data->file_data['name'] ?? 'Attachment' }}
                                    </a>
                                    <span class="text-sm text-gray-500 mr-2">({{ round(($data->file_data['size'] ?? 0) / 1024) }} KB)</span>
                                @else
                                    @foreach($data->file_data as $file)
                                        <div class="mb-1">
                                            <a href="{{ route('submissions.attachments.show', [$form, $submission, $data]) }}" class="text-teal-600 hover:text-teal-900">
                                                {{ $file['name'] ?? 'Attachment' }}
                                            </a>
                                            <span class="text-sm text-gray-500 mr-2">({{ round(($file['size'] ?? 0) / 1024) }} KB)</span>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        @elseif($data->field->field_type === 'checkbox')
                            <div class="mt-2">
                                @if(is_array(json_decode($data->value)))
                                    @foreach(json_decode($data->value) as $option)
                                        <span class="inline-block bg-gray-200 px-2 py-1 rounded text-sm mr-1">{{ $option }}</span>
                                    @endforeach
                                @else
                                    <span class="text-gray-900">{{ $data->value }}</span>
                                @endif
                            </div>
                        @else
                            <p class="mt-1 text-gray-900">{{ $data->value }}</p>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-6 flex justify-end space-x-2 space-x-reverse">
                <button onclick="window.print()" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                    Print
                </button>
                <form action="{{ route('submissions.destroy', [$form, $submission]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this submission?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                        Delete Submission
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
