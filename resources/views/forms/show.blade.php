<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $form->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { font-family: 'Tajawal', sans-serif; }
        body {
            background: #080c14;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background:
                radial-gradient(ellipse at 20% 0%, rgba(37, 99, 235, 0.2) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 100%, rgba(139, 92, 246, 0.12) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 50%, rgba(6, 182, 212, 0.05) 0%, transparent 70%);
            z-index: 0;
            pointer-events: none;
        }
        .grid-pattern {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.015) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.015) 1px, transparent 1px);
            background-size: 40px 40px;
            z-index: 0;
            pointer-events: none;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            box-shadow:
                0 0 0 1px rgba(255,255,255,0.05),
                0 20px 60px rgba(0,0,0,0.3),
                0 0 80px rgba(37, 99, 235, 0.08);
            border: 1px solid rgba(37, 99, 235, 0.08);
        }
        .form-header-bg {
            background: linear-gradient(135deg, #080c14 0%, #0f172a 40%, #1e293b 100%);
            position: relative;
            overflow: hidden;
        }
        .form-header-bg::before {
            content: '';
            position: absolute;
            top: -50%; right: -20%;
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.25) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(40px);
        }
        .form-header-bg::after {
            content: '';
            position: absolute;
            bottom: -30%; left: -10%;
            width: 250px; height: 250px;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(40px);
        }
        .form-container::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, #2563eb, #3b82f6, #60a5fa, #3b82f6, #2563eb);
            background-size: 200% 100%;
            animation: shimmer 3s linear infinite;
            z-index: 10;
        }
        .logo-wrapper {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 12px 20px;
            transition: all 0.4s ease;
        }
        .logo-wrapper:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: scale(1.02);
        }
        .logo-wrapper img {
            height: 48px;
            width: auto;
            filter: brightness(0) invert(1);
            object-fit: contain;
        }
        .field-wrapper {
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
        }
        .field-wrapper:hover {
            border-color: #cbd5e1;
            background: #f1f5f9;
        }
        .field-wrapper:focus-within {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(37, 99, 235, 0.12);
            border-color: #2563eb;
            background: #ffffff;
        }
        input:focus, textarea:focus, select:focus {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1) !important;
            outline: none !important;
        }
        .field-input-base {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            transition: all 0.25s ease;
        }
        .submit-btn {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
        }
        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 60%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);
            transition: left 0.6s ease;
            z-index: 1;
        }
        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(37, 99, 235, 0.4);
        }
        .submit-btn:hover::before {
            left: 100%;
        }
        .submit-btn:active {
            transform: translateY(-1px);
        }
        .progress-track {
            height: 5px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 10px;
            overflow: hidden;
        }
        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #2563eb, #3b82f6, #1d4ed8);
            border-radius: 10px;
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 0 10px rgba(37, 99, 235, 0.5);
        }
        .progress-text {
            font-size: 11px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.6);
            letter-spacing: 0.5px;
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .progress-bar {
            background-size: 200% 100%;
            animation: shimmer 2s linear infinite;
        }
        .alert-success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border: 1px solid #6ee7b7;
        }
        .alert-error {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border: 1px solid #fca5a5;
        }
        .section-divider {
            border-right: 4px solid #2563eb;
            background: linear-gradient(90deg, rgba(37, 99, 235, 0.05) 0%, transparent 100%);
        }
        .file-drop-zone {
            border: 2px dashed #cbd5e1;
            background: #f8fafc;
            transition: all 0.3s ease;
        }
        .file-drop-zone:hover {
            border-color: #2563eb;
            background: #eff6ff;
        }
        .star-rating i { transition: all 0.2s ease; }
        .star-rating i:hover { transform: scale(1.2); }
        .radio-card, .checkbox-card {
            transition: all 0.25s ease;
            border: 1.5px solid #e2e8f0;
        }
        .radio-card:hover, .checkbox-card:hover {
            border-color: #93c5fd;
            background: #eff6ff;
        }
        .radio-card:has(input:checked), .checkbox-card:has(input:checked) {
            border-color: #2563eb;
            background: #eff6ff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.08);
        }
        .footer-brand {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
        }
        .footer-brand img {
            height: 28px;
            filter: brightness(0) invert(1);
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="grid-pattern"></div>

    <!-- Progress Bar -->
    <div class="fixed top-0 left-0 right-0 z-50 bg-[#080c14]/80 backdrop-blur-md border-b border-white/5">
        <div class="max-w-3xl mx-auto px-6 py-2.5 flex items-center gap-4">
            <div class="flex-1 progress-track">
                <div id="progress-bar" class="progress-bar" style="width: 0%"></div>
            </div>
            <span class="progress-text whitespace-nowrap"><span id="progress-percent">0</span>% مكتمل</span>
        </div>
    </div>

    <div class="max-w-3xl mx-auto pt-20 pb-8 px-4 relative z-10">
        <!-- Form Card -->
        <div class="form-container rounded-3xl overflow-hidden mb-6">
            <!-- Form Header with Logo -->
            <div class="form-header-bg px-8 py-10 text-white text-center relative z-10">
                <div class="logo-wrapper mb-5">
                    <img src="{{ asset('images/logo.png') }}" alt="المُساندة">
                </div>
                <h1 class="text-3xl font-extrabold mb-2 tracking-tight">{{ $form->title }}</h1>
                @if($form->description)
                    <p class="text-white/80 text-base max-w-xl mx-auto leading-relaxed">{{ $form->description }}</p>
                @endif
            </div>

            <div class="p-8 sm:p-10">
                @if(session('success'))
                    <div class="alert-success text-green-800 px-6 py-4 rounded-2xl mb-6 flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-check text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-base">تم الإرسال بنجاح!</h3>
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert-error text-red-800 px-6 py-4 rounded-2xl mb-6 flex items-center gap-3">
                        <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-exclamation text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-base">خطأ!</h3>
                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                <form action="{{ route('forms.submit', $form) }}" method="POST" enctype="multipart/form-data" id="form">
                    @csrf

                    @forelse($form->fields as $index => $field)
                        <div class="field-wrapper rounded-2xl p-6 mb-4" data-field-index="{{ $index }}">
                            <input type="hidden" name="fields[{{ $field->id }}][field_id]" value="{{ $field->id }}">
                            <label for="field_{{ $field->id }}" class="block text-sm font-bold text-gray-700 mb-3">
                                <span class="text-lg">{{ $field->label }}</span>
                                @if($field->required)
                                    <span class="text-red-500 mr-1">*</span>
                                @endif
                            </label>

                            @if($field->field_type === 'text')
                                <input type="text" 
                                       id="field_{{ $field->id }}" 
                                       name="fields[{{ $field->id }}][value]" 
                                       placeholder="{{ $field->placeholder ?? 'أدخل النص هنا' }}"
                                       {{ $field->required ? 'required' : '' }}
                                       class="w-full px-4 py-3.5 field-input-base text-gray-800 placeholder-gray-400">

                            @elseif($field->field_type === 'textarea')
                                <textarea id="field_{{ $field->id }}" 
                                          name="fields[{{ $field->id }}][value]" 
                                          rows="4"
                                          placeholder="{{ $field->placeholder ?? 'أدخل النص هنا' }}"
                                          {{ $field->required ? 'required' : '' }}
                                          class="w-full px-4 py-3.5 field-input-base text-gray-800 placeholder-gray-400 resize-none"></textarea>

                            @elseif($field->field_type === 'email')
                                <div class="relative">
                                    <i class="fas fa-envelope absolute right-4 top-4 text-gray-400"></i>
                                    <input type="email" 
                                           id="field_{{ $field->id }}" 
                                           name="fields[{{ $field->id }}][value]" 
                                           placeholder="{{ $field->placeholder ?? 'example@email.com' }}"
                                           {{ $field->required ? 'required' : '' }}
                                           class="w-full px-4 py-3.5 pr-12 field-input-base text-gray-800 placeholder-gray-400">
                                </div>

                            @elseif($field->field_type === 'number')
                                <input type="number" 
                                       id="field_{{ $field->id }}" 
                                       name="fields[{{ $field->id }}][value]" 
                                       placeholder="{{ $field->placeholder ?? '0' }}"
                                       {{ $field->required ? 'required' : '' }}
                                       class="w-full px-4 py-3.5 field-input-base text-gray-800 placeholder-gray-400">

                            @elseif($field->field_type === 'date')
                                <input type="date" 
                                       id="field_{{ $field->id }}" 
                                       name="fields[{{ $field->id }}][value]" 
                                       {{ $field->required ? 'required' : '' }}
                                       class="w-full px-4 py-3.5 field-input-base text-gray-800 placeholder-gray-400">

                            @elseif($field->field_type === 'time')
                                <input type="time" 
                                       id="field_{{ $field->id }}" 
                                       name="fields[{{ $field->id }}][value]" 
                                       {{ $field->required ? 'required' : '' }}
                                       class="w-full px-4 py-3.5 field-input-base text-gray-800 placeholder-gray-400">

                            @elseif($field->field_type === 'select')
                                <div class="relative">
                                    <select id="field_{{ $field->id }}" 
                                            name="fields[{{ $field->id }}][value]" 
                                            {{ $field->required ? 'required' : '' }}
                                            class="w-full px-4 py-3.5 field-input-base text-gray-800 appearance-none cursor-pointer">
                                        <option value="">اختر من القائمة...</option>
                                        @if($field->options)
                                            @foreach($field->options as $option)
                                                <option value="{{ $option }}">{{ $option }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <i class="fas fa-chevron-down absolute left-4 top-4 text-gray-400"></i>
                                </div>

                            @elseif($field->field_type === 'radio')
                                <div class="space-y-3">
                                    @if($field->options)
                                        @foreach($field->options as $option)
                                            <label class="radio-card flex items-center p-4 bg-white rounded-xl cursor-pointer">
                                                <input type="radio" 
                                                       name="fields[{{ $field->id }}][value]" 
                                                       value="{{ $option }}"
                                                       {{ $field->required ? 'required' : '' }}
                                                       class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500 ml-3">
                                                <span class="text-gray-700 font-medium">{{ $option }}</span>
                                            </label>
                                        @endforeach
                                    @endif
                                </div>

                            @elseif($field->field_type === 'checkbox')
                                <div class="space-y-3">
                                    @if($field->options)
                                        @foreach($field->options as $option)
                                            <label class="checkbox-card flex items-center p-4 bg-white rounded-xl cursor-pointer">
                                                <input type="checkbox" 
                                                       name="fields[{{ $field->id }}][value][]" 
                                                       value="{{ $option }}"
                                                       class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 ml-3">
                                                <span class="text-gray-700 font-medium">{{ $option }}</span>
                                            </label>
                                        @endforeach
                                    @endif
                                </div>

                            @elseif($field->field_type === 'file')
                                <div class="file-drop-zone rounded-2xl p-8 text-center cursor-pointer relative">
                                    <input type="file" 
                                           id="field_{{ $field->id }}" 
                                           name="fields[{{ $field->id }}][file][]" 
                                           multiple
                                           {{ $field->required ? 'required' : '' }}
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                           onchange="updateFileName(this, '{{ $field->id }}', true)">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-blue-500 mb-3"></i>
                                    <p class="text-gray-700 font-medium mb-1">اسحب الملفات هنا أو انقر للاختيار</p>
                                    <p class="text-gray-400 text-sm" id="filename-{{ $field->id }}">لم يتم اختيار ملف</p>
                                </div>

                            @elseif($field->field_type === 'phone')
                                <div class="relative">
                                    <i class="fas fa-phone absolute right-4 top-4 text-gray-400"></i>
                                    <input type="tel" 
                                           id="field_{{ $field->id }}" 
                                           name="fields[{{ $field->id }}][value]" 
                                           placeholder="{{ $field->placeholder ?? '05xxxxxxxx' }}"
                                           {{ $field->required ? 'required' : '' }}
                                           class="w-full px-4 py-3.5 pr-12 field-input-base text-gray-800 placeholder-gray-400">
                                </div>

                            @elseif($field->field_type === 'url')
                                <div class="relative">
                                    <i class="fas fa-link absolute right-4 top-4 text-gray-400"></i>
                                    <input type="url" 
                                           id="field_{{ $field->id }}" 
                                           name="fields[{{ $field->id }}][value]" 
                                           placeholder="{{ $field->placeholder ?? 'https://example.com' }}"
                                           {{ $field->required ? 'required' : '' }}
                                           class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:outline-none transition-all bg-white">
                                </div>

                            @elseif($field->field_type === 'section')
                                <div class="section-divider pr-5 py-3 rounded-xl">
                                    <h3 class="text-xl font-extrabold text-gray-900">{{ $field->label }}</h3>
                                </div>

                            @elseif($field->field_type === 'password')
                                <input type="password" 
                                       id="field_{{ $field->id }}" 
                                       name="fields[{{ $field->id }}][value]" 
                                       placeholder="{{ $field->placeholder ?? '••••••••' }}"
                                       {{ $field->required ? 'required' : '' }}
                                       class="w-full px-4 py-3.5 field-input-base text-gray-800 placeholder-gray-400">

                            @elseif($field->field_type === 'hidden')
                                <input type="hidden" 
                                       id="field_{{ $field->id }}" 
                                       name="fields[{{ $field->id }}][value]" 
                                       value="{{ $field->default_value ?? '' }}">

                            @elseif($field->field_type === 'rating' || $field->field_type === 'scale')
                                <div class="flex items-center gap-3 star-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="cursor-pointer">
                                            <input type="radio" 
                                                   name="fields[{{ $field->id }}][value]" 
                                                   value="{{ $i }}" 
                                                   {{ $field->required ? 'required' : '' }}
                                                   class="hidden peer">
                                            <i class="fas fa-star text-3xl text-gray-300 peer-checked:text-yellow-400 transition-all hover:text-yellow-300"></i>
                                        </label>
                                    @endfor
                                </div>

                            @elseif($field->field_type === 'image')
                                <div class="file-drop-zone rounded-2xl p-8 text-center cursor-pointer relative">
                                    <input type="file" 
                                           id="field_{{ $field->id }}" 
                                           name="fields[{{ $field->id }}][file][]" 
                                           accept="image/*"
                                           multiple
                                           {{ $field->required ? 'required' : '' }}
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                           onchange="updateFileName(this, '{{ $field->id }}', true)">
                                    <i class="fas fa-image text-4xl text-blue-500 mb-3"></i>
                                    <p class="text-gray-700 font-medium mb-1">اسحب الصور هنا أو انقر للاختيار</p>
                                    <p class="text-gray-400 text-sm" id="filename-{{ $field->id }}">لم يتم اختيار ملف</p>
                                </div>

                            @elseif($field->field_type === 'video')
                                <div class="file-drop-zone rounded-2xl p-8 text-center cursor-pointer relative">
                                    <input type="file" 
                                           id="field_{{ $field->id }}" 
                                           name="fields[{{ $field->id }}][file][]" 
                                           accept="video/*"
                                           multiple
                                           {{ $field->required ? 'required' : '' }}
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                           onchange="updateFileName(this, '{{ $field->id }}', true)">
                                    <i class="fas fa-video text-4xl text-blue-500 mb-3"></i>
                                    <p class="text-gray-700 font-medium mb-1">اسحب الفيديوهات هنا أو انقر للاختيار</p>
                                    <p class="text-gray-400 text-sm" id="filename-{{ $field->id }}">لم يتم اختيار ملف</p>
                                </div>

                            @elseif($field->field_type === 'price')
                                <div class="relative">
                                    <i class="fas fa-dollar-sign absolute right-4 top-4 text-gray-400"></i>
                                    <input type="number" 
                                           step="0.01" 
                                           min="0"
                                           id="field_{{ $field->id }}" 
                                           name="fields[{{ $field->id }}][value]" 
                                           placeholder="{{ $field->placeholder ?? '0.00' }}"
                                           {{ $field->required ? 'required' : '' }}
                                           class="w-full px-4 py-3.5 pr-12 field-input-base text-gray-800 placeholder-gray-400">
                                </div>

                            @elseif($field->field_type === 'html')
                                <div class="prose max-w-none text-gray-700">
                                    {{ $field->help_text ?? $field->label }}
                                </div>

                            @elseif($field->field_type === 'signature')
                                <div class="border-2 border-slate-200 rounded-2xl bg-white overflow-hidden">
                                    <canvas id="signature-{{ $field->id }}" class="w-full h-40 cursor-crosshair"></canvas>
                                    <input type="hidden" name="fields[{{ $field->id }}][value]" id="signature-input-{{ $field->id }}">
                                    <div class="flex justify-between items-center px-4 py-2.5 border-t border-slate-100 bg-slate-50">
                                        <button type="button" onclick="clearSignature('{{ $field->id }}')" class="text-sm text-gray-500 hover:text-red-500 font-medium transition-colors">
                                            <i class="fas fa-eraser ml-1"></i> مسح
                                        </button>
                                        <span class="text-xs text-gray-400">وقّع بالماوس فوق المنطقة البيضاء</span>
                                    </div>
                                </div>

                            @endif

                            @if($field->help_text)
                                <p class="text-sm text-gray-500 mt-2 flex items-center">
                                    <i class="fas fa-info-circle ml-1"></i>
                                    {{ $field->help_text }}
                                </p>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-exclamation-circle text-4xl text-yellow-500 mb-3"></i>
                            <p class="text-gray-600">هذا النموذج لا يحتوي على حقول بعد.</p>
                        </div>
                    @endforelse

                    <div class="mt-8">
                        <button type="submit" class="submit-btn w-full text-white px-8 py-4 rounded-2xl text-lg font-extrabold shadow-lg flex items-center justify-center gap-3">
                            <i class="fas fa-paper-plane"></i>
                            إرسال النموذج
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center py-6">
            <div class="footer-brand">
                <img src="{{ asset('images/logo.png') }}" alt="المُساندة">
                <span class="text-white/60 text-sm font-medium">شركة المُساندة للتأمين </span>
            </div>
        </div>
    </div>

    <script>
        // Update progress bar
        const form = document.getElementById('form');
        const progressBar = document.getElementById('progress-bar');
        const fields = form.querySelectorAll('input, textarea, select');
        const totalFields = fields.length;

        function updateProgress() {
            let filled = 0;
            fields.forEach(field => {
                if (field.type === 'checkbox' || field.type === 'radio') {
                    if (field.checked) filled++;
                } else if (field.value) {
                    filled++;
                }
            });
            const progress = totalFields > 0 ? (filled / totalFields) * 100 : 0;
            progressBar.style.width = progress + '%';
            document.getElementById('progress-percent').textContent = Math.round(progress);
        }

        fields.forEach(field => {
            field.addEventListener('change', updateProgress);
            field.addEventListener('input', updateProgress);
        });

        // File upload display
        function updateFileName(input, fieldId, isMulti) {
            const filename = document.getElementById('filename-' + fieldId);
            if (input.files && input.files.length > 0) {
                if (isMulti && input.files.length > 1) {
                    filename.textContent = input.files.length + ' ملفات مختارة';
                } else {
                    filename.textContent = input.files[0].name;
                }
                filename.classList.remove('text-gray-400');
                filename.classList.add('text-primary-600', 'font-bold');
            }
        }

        // Signature pad
        function clearSignature(fieldId) {
            const canvas = document.getElementById('signature-' + fieldId);
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            document.getElementById('signature-input-' + fieldId).value = '';
        }

        document.querySelectorAll('canvas[id^="signature-"]').forEach(canvas => {
            const fieldId = canvas.id.replace('signature-', '');
            const ctx = canvas.getContext('2d');
            let drawing = false;

            canvas.width = canvas.offsetWidth;
            canvas.height = canvas.offsetHeight;

            canvas.addEventListener('mousedown', (e) => {
                drawing = true;
                ctx.beginPath();
                ctx.moveTo(e.offsetX, e.offsetY);
            });
            canvas.addEventListener('mousemove', (e) => {
                if (drawing) {
                    ctx.lineTo(e.offsetX, e.offsetY);
                    ctx.stroke();
                }
            });
            canvas.addEventListener('mouseup', () => {
                drawing = false;
                document.getElementById('signature-input-' + fieldId).value = canvas.toDataURL();
            });
            canvas.addEventListener('mouseleave', () => {
                drawing = false;
            });

            // Touch support
            canvas.addEventListener('touchstart', (e) => {
                e.preventDefault();
                drawing = true;
                const rect = canvas.getBoundingClientRect();
                ctx.beginPath();
                ctx.moveTo(e.touches[0].clientX - rect.left, e.touches[0].clientY - rect.top);
            });
            canvas.addEventListener('touchmove', (e) => {
                e.preventDefault();
                if (drawing) {
                    const rect = canvas.getBoundingClientRect();
                    ctx.lineTo(e.touches[0].clientX - rect.left, e.touches[0].clientY - rect.top);
                    ctx.stroke();
                }
            });
            canvas.addEventListener('touchend', () => {
                drawing = false;
                document.getElementById('signature-input-' + fieldId).value = canvas.toDataURL();
            });
        });
    </script>
</body>
</html>
