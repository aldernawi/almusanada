<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                    <a href="{{ route('forms.index') }}" class="hover:text-teal-600 font-medium transition-colors">Forms</a>
                    <i class="fas fa-chevron-right text-[10px] text-gray-400"></i>
                    <span>Share</span>
                </div>
                <h1 class="text-2xl font-extrabold text-gray-900">Share Form: {{ $form->title }}</h1>
                <a href="{{ route('forms.edit', $form) }}" class="text-teal-600 hover:text-teal-800 text-sm font-semibold mt-1 inline-flex items-center gap-1">
                    <i class="fas fa-arrow-left text-xs"></i> Back to Editor
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Direct Link -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-lift hover:shadow-md">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-teal-50 to-teal-100 rounded-xl flex items-center justify-center ml-3">
                        <i class="fas fa-link text-teal-600"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Direct Link</h2>
                        <p class="text-sm text-gray-500">Share this link with users</p>
                    </div>
                </div>
                <div class="bg-gray-50 p-3 rounded-xl mb-3 border border-gray-100">
                    <code class="text-sm break-all" id="direct-link">{{ url('/f/' . $form->slug) }}</code>
                </div>
                <button onclick="copyToClipboard('direct-link')" class="w-full bg-gradient-to-l from-teal-600 to-cyan-600 text-white px-4 py-2.5 rounded-xl hover:shadow-lg transition font-bold text-sm flex items-center justify-center gap-2">
                    <i class="fas fa-copy"></i> Copy Link
                </button>
            </div>

            <!-- QR Code -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-lift hover:shadow-md">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-50 to-green-100 rounded-xl flex items-center justify-center ml-3">
                        <i class="fas fa-qrcode text-green-600"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">QR Code</h2>
                        <p class="text-sm text-gray-500">Scan the code to access the form</p>
                    </div>
                </div>
                <div class="flex justify-center mb-3">
                    <div id="qrcode" class="p-4 bg-white border-2 border-gray-100 rounded-xl"></div>
                </div>
                <button onclick="downloadQR()" class="w-full bg-gradient-to-l from-green-600 to-emerald-600 text-white px-4 py-2.5 rounded-xl hover:shadow-lg transition font-bold text-sm flex items-center justify-center gap-2">
                    <i class="fas fa-download"></i> Download QR Code
                </button>
            </div>

            <!-- Embed iframe -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-lift hover:shadow-md">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl flex items-center justify-center ml-3">
                        <i class="fas fa-code text-blue-600"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Embed in Your Website</h2>
                        <p class="text-sm text-gray-500">Copy the code and paste it on your site</p>
                    </div>
                </div>
                <textarea id="embed-code" rows="4" readonly class="w-full px-3 py-2 border-2 border-gray-200 rounded-xl bg-gray-50 text-sm font-mono mb-3 focus:outline-none">&lt;iframe src="{{ url('/f/' . $form->slug) }}" width="100%" height="800" frameborder="0"&gt;&lt;/iframe&gt;</textarea>
                <button onclick="copyToClipboard('embed-code')" class="w-full bg-gradient-to-l from-blue-600 to-cyan-600 text-white px-4 py-2.5 rounded-xl hover:shadow-lg transition font-bold text-sm flex items-center justify-center gap-2">
                    <i class="fas fa-copy"></i> Copy Code
                </button>
            </div>

            <!-- Email Share -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-lift hover:shadow-md">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-cyan-50 to-cyan-100 rounded-xl flex items-center justify-center ml-3">
                        <i class="fas fa-envelope text-cyan-600"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Share by Email</h2>
                        <p class="text-sm text-gray-500">Send the link to an email list</p>
                    </div>
                </div>
                <form action="{{ route('forms.share.email', $form) }}" method="POST">
                    @csrf
                    <textarea name="emails" rows="3" placeholder="Enter emails separated by comma or new line"
                        class="w-full px-3 py-2 border-2 border-gray-200 rounded-xl mb-3 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 transition text-sm"></textarea>
                    <button type="submit" class="w-full bg-gradient-to-l from-cyan-600 to-cyan-600 text-white px-4 py-2.5 rounded-xl hover:shadow-lg transition font-bold text-sm flex items-center justify-center gap-2">
                        <i class="fas fa-paper-plane"></i> Send
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        // Generate QR Code
        const qrcode = new QRCode(document.getElementById('qrcode'), {
            text: '{{ url('/f/' . $form->slug) }}',
            width: 150,
            height: 150,
            colorDark: '#000000',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });

        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            const text = element.tagName === 'TEXTAREA' ? element.value : element.textContent;
            navigator.clipboard.writeText(text).then(() => {
                alert('Copied successfully!');
            });
        }

        function downloadQR() {
            const canvas = document.querySelector('#qrcode canvas');
            if (canvas) {
                const link = document.createElement('a');
                link.download = 'form-qr-code.png';
                link.href = canvas.toDataURL();
                link.click();
            }
        }
    </script>
</x-app-layout>
