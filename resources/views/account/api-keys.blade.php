<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Developer API Keys') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="flex gap-4 mb-6">
                <a href="{{ route('dashboard') }}" class="text-teal-600 hover:text-teal-800 text-sm font-bold flex items-center gap-1">
                    <i class="fas fa-arrow-left text-xs"></i> Back to Dashboard
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-xl mb-6 flex items-center gap-3">
                    <i class="fas fa-check-circle text-lg"></i>
                    <div>
                        <h3 class="font-bold text-sm">Operation completed successfully!</h3>
                        <p class="text-xs mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Generate New Key -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8">
                <h2 class="text-lg font-bold text-gray-900 border-b pb-3 mb-6"><i class="fas fa-key ml-2 text-teal-500"></i> Generate New API Key</h2>
                
                <form action="{{ route('api-keys.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Key Name (describe its purpose)</label>
                            <input type="text" name="name" required placeholder="e.g. CRM Integration" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Permissions</label>
                            <select name="permissions" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 text-sm">
                                <option value="read_only">Read-Only</option>
                                <option value="full_access">Full Access</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="bg-gradient-to-l from-teal-600 to-cyan-600 hover:shadow-lg text-white font-bold text-sm px-6 py-2.5 rounded-xl transition flex items-center gap-2">
                            <i class="fas fa-magic"></i> Generate Key
                        </button>
                    </div>
                </form>
            </div>

            <!-- List of Keys -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8">
                <h2 class="text-lg font-bold text-gray-900 border-b pb-3 mb-6"><i class="fas fa-list ml-2 text-teal-500"></i> Active Keys</h2>

                @if($apiKeys->count() > 0)
                    <div class="space-y-4">
                        @foreach($apiKeys as $key)
                            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div class="space-y-1.5 flex-1">
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-bold text-gray-900 text-sm">{{ $key->name }}</h3>
                                        <span class="px-2 py-0.5 bg-teal-100 text-teal-700 text-[10px] font-bold rounded-full">
                                            {{ $key->permissions == 'read_only' ? 'Read-Only' : 'Full Access' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <code class="text-xs bg-gray-200 px-2 py-1 rounded select-all font-mono" id="key-text-{{ $key->id }}">{{ $key->key }}</code>
                                        <button onclick="copyKey('key-text-{{ $key->id }}')" class="text-gray-500 hover:text-teal-600 text-xs">
                                            <i class="far fa-copy"></i> Copy
                                        </button>
                                    </div>
                                    <p class="text-[10px] text-gray-400">Last used: {{ $key->last_used_at ? $key->last_used_at->diffForHumans() : 'Never used' }}</p>
                                </div>
                                <div class="flex items-center">
                                    <form action="{{ route('api-keys.destroy', $key) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete and revoke this API key? External programs will no longer be able to use it!')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-bold px-3 py-1.5 bg-red-50 hover:bg-red-100 rounded-lg transition flex items-center gap-1.5">
                                            <i class="fas fa-trash-alt"></i> Delete & Revoke
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-key text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 text-sm">No API keys generated yet.</p>
                    </div>
                @endif
            </div>

            <!-- Developer Integration Example -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h2 class="text-lg font-bold text-gray-900 border-b pb-3 mb-4"><i class="fas fa-code ml-2 text-teal-500"></i> Integration Guide</h2>
                <p class="text-xs text-gray-500 mb-4">Developers can call your API endpoints programmatically using the API key to retrieve submission data as JSON:</p>
                
                <div class="bg-gray-900 rounded-xl p-4 text-left dir-ltr overflow-x-auto text-xs text-teal-300 font-mono space-y-2">
                    <p class="text-gray-400"># Fetch available forms</p>
                    <p>curl -X GET "{{ url('/api/v1/forms') }}" \</p>
                    <p>&nbsp;&nbsp;-H "X-API-Key: YOUR_API_KEY_HERE"</p>
                </div>
            </div>

        </div>
    </div>

    <script>
        function copyKey(elementId) {
            const text = document.getElementById(elementId).textContent;
            navigator.clipboard.writeText(text).then(() => {
                alert('API key copied to clipboard!');
            });
        }
    </script>
</x-app-layout>
