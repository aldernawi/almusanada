<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Form Submissions: {{ $form->title }}</h1>
                <a href="{{ route('forms.edit', $form) }}" class="text-teal-600 hover:text-teal-900">Back to Form</a>
            </div>
            <div class="flex space-x-2 space-x-reverse">
                <a href="{{ route('submissions.export', [$form, 'csv']) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    Export CSV
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($submissions as $submission)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $submission->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $submission->user ? $submission->user->name : 'Guest' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $submission->submitted_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($submission->status == 'completed') bg-green-100 text-green-800
                                    @elseif($submission->status == 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $submission->status == 'completed' ? 'Completed' : ($submission->status == 'pending' ? 'Pending' : 'Spam') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2 space-x-reverse">
                                    <a href="{{ route('submissions.show', [$form, $submission]) }}" class="text-teal-600 hover:text-teal-900">View</a>
                                    <form action="{{ route('submissions.destroy', [$form, $submission]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this submission?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No submissions yet
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $submissions->links() }}
    </div>
</x-app-layout>
