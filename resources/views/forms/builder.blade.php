<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منشئ النماذج - {{ $form->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Tajawal', sans-serif; }
        .builder-container { height: calc(100vh - 64px); }
        .field-item { cursor: grab; transition: all 0.2s; }
        .field-item:active { cursor: grabbing; }
        .field-item:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .workspace-field { border: 2px solid transparent; transition: all 0.2s; }
        .workspace-field:hover { border-color: #1f4277; }
        .workspace-field.selected { border-color: #1f4277; background: #eef2ff; }
        .sidebar { overflow-y: auto; }
        .sidebar::-webkit-scrollbar { width: 6px; }
        .sidebar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        .sortable-ghost { opacity: 0.4; background: #e0e7ff; }
        .sortable-drag { cursor: grabbing; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Top Navigation Bar -->
    <nav class="bg-white border-b h-16 flex items-center justify-between px-6 fixed top-0 left-0 right-0 z-50">
        <div class="flex items-center space-x-4 space-x-reverse">
            <img src="{{ asset('images/logo.png') }}" alt="المُساندة" class="h-8 w-auto object-contain">
            <div class="w-px h-6 bg-gray-200"></div>
            <a href="{{ route('forms.index') }}" class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-arrow-right ml-2"></i>العودة
            </a>
            <h1 class="text-lg font-bold text-gray-900">{{ $form->title }}</h1>
            <span class="px-2 py-1 rounded text-xs {{ $form->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                {{ $form->status == 'active' ? 'منشور' : 'غير منشور' }}
            </span>
        </div>
        <div class="flex items-center space-x-2 space-x-reverse">
            <a href="{{ route('forms.share', $form) }}" class="px-4 py-2 text-cyan-700 hover:bg-cyan-100 rounded-lg">
                <i class="fas fa-share-alt ml-1"></i>مشاركة
            </a>
            <button onclick="previewForm()" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-eye ml-1"></i>معاينة
            </button>
            <button onclick="saveForm()" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700">
                <i class="fas fa-save ml-1"></i>حفظ
            </button>
            <a href="{{ route('submissions.index', $form) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                <i class="fas fa-chart-bar ml-1"></i>الردود
            </a>
        </div>
    </nav>

    <!-- Main Builder Area -->
    <div class="builder-container flex pt-16">
        <!-- Left Sidebar - Field Types -->
        <div class="w-72 bg-white border-l h-full sidebar p-4">
            <h2 class="text-sm font-bold text-gray-500 mb-4 uppercase">عناصر النموذج</h2>
            
            <div id="field-palette" class="grid grid-cols-2 gap-2">
                <div class="field-item bg-gray-50 p-3 rounded-lg border hover:border-teal-300" data-type="text">
                    <i class="fas fa-font text-teal-500 mb-1 block"></i>
                    <span class="text-xs">نص قصير</span>
                </div>
                <div class="field-item bg-gray-50 p-3 rounded-lg border hover:border-teal-300" data-type="textarea">
                    <i class="fas fa-align-left text-teal-500 mb-1 block"></i>
                    <span class="text-xs">نص طويل</span>
                </div>
                <div class="field-item bg-gray-50 p-3 rounded-lg border hover:border-teal-300" data-type="email">
                    <i class="fas fa-envelope text-teal-500 mb-1 block"></i>
                    <span class="text-xs">بريد إلكتروني</span>
                </div>
                <div class="field-item bg-gray-50 p-3 rounded-lg border hover:border-teal-300" data-type="number">
                    <i class="fas fa-hashtag text-teal-500 mb-1 block"></i>
                    <span class="text-xs">رقم</span>
                </div>
                <div class="field-item bg-gray-50 p-3 rounded-lg border hover:border-teal-300" data-type="phone">
                    <i class="fas fa-phone text-teal-500 mb-1 block"></i>
                    <span class="text-xs">هاتف</span>
                </div>
                <div class="field-item bg-gray-50 p-3 rounded-lg border hover:border-teal-300" data-type="date">
                    <i class="fas fa-calendar text-teal-500 mb-1 block"></i>
                    <span class="text-xs">تاريخ</span>
                </div>
                <div class="field-item bg-gray-50 p-3 rounded-lg border hover:border-teal-300" data-type="select">
                    <i class="fas fa-list text-teal-500 mb-1 block"></i>
                    <span class="text-xs">قائمة منسدلة</span>
                </div>
                <div class="field-item bg-gray-50 p-3 rounded-lg border hover:border-teal-300" data-type="radio">
                    <i class="fas fa-dot-circle text-teal-500 mb-1 block"></i>
                    <span class="text-xs">اختيار واحد</span>
                </div>
                <div class="field-item bg-gray-50 p-3 rounded-lg border hover:border-teal-300" data-type="checkbox">
                    <i class="fas fa-check-square text-teal-500 mb-1 block"></i>
                    <span class="text-xs">اختيار متعدد</span>
                </div>
                <div class="field-item bg-gray-50 p-3 rounded-lg border hover:border-teal-300" data-type="file">
                    <i class="fas fa-file-upload text-teal-500 mb-1 block"></i>
                    <span class="text-xs">رفع ملف</span>
                </div>
                <div class="field-item bg-gray-50 p-3 rounded-lg border hover:border-teal-300" data-type="url">
                    <i class="fas fa-link text-teal-500 mb-1 block"></i>
                    <span class="text-xs">رابط</span>
                </div>
                <div class="field-item bg-gray-50 p-3 rounded-lg border hover:border-teal-300" data-type="section">
                    <i class="fas fa-heading text-teal-500 mb-1 block"></i>
                    <span class="text-xs">عنوان قسم</span>
                </div>
            </div>
        </div>

        <!-- Center Workspace -->
        <div class="flex-1 bg-gray-100 p-8 overflow-y-auto">
            <div class="max-w-2xl mx-auto">
                <!-- Form Header -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-4">
                    <input type="text" id="form-title" value="{{ $form->title }}" 
                        class="text-2xl font-bold text-gray-900 w-full border-none focus:ring-0 text-center mb-2"
                        placeholder="عنوان النموذج">
                    <textarea id="form-description" rows="2" 
                        class="w-full text-gray-600 text-center border-none focus:ring-0 resize-none"
                        placeholder="وصف النموذج (اختياري)">{{ $form->description }}</textarea>
                </div>

                <!-- Fields Container -->
                <div id="workspace" class="space-y-3 min-h-[400px]">
                    @foreach($form->fields as $field)
                        <div class="workspace-field bg-white rounded-lg shadow-sm p-4 cursor-pointer" 
                             data-field-id="{{ $field->id }}"
                             onclick="selectField({{ $field->id }})">
                            <div class="flex items-center justify-between mb-2">
                                <label class="font-medium text-gray-700">{{ $field->label }}</label>
                                @if($field->required)
                                    <span class="text-red-500 text-sm">*</span>
                                @endif
                            </div>
                            <div class="text-gray-400 text-sm">
                                @if($field->field_type == 'text')
                                    <input type="text" disabled placeholder="{{ $field->placeholder ?? 'نص قصير' }}" class="w-full px-3 py-2 border rounded bg-gray-50">
                                @elseif($field->field_type == 'textarea')
                                    <textarea disabled placeholder="{{ $field->placeholder ?? 'نص طويل' }}" rows="3" class="w-full px-3 py-2 border rounded bg-gray-50"></textarea>
                                @elseif($field->field_type == 'email')
                                    <input type="email" disabled placeholder="{{ $field->placeholder ?? 'example@email.com' }}" class="w-full px-3 py-2 border rounded bg-gray-50">
                                @elseif($field->field_type == 'number')
                                    <input type="number" disabled placeholder="{{ $field->placeholder ?? '0' }}" class="w-full px-3 py-2 border rounded bg-gray-50">
                                @elseif($field->field_type == 'phone')
                                    <input type="tel" disabled placeholder="{{ $field->placeholder ?? '05xxxxxxxx' }}" class="w-full px-3 py-2 border rounded bg-gray-50">
                                @elseif($field->field_type == 'date')
                                    <input type="date" disabled class="w-full px-3 py-2 border rounded bg-gray-50">
                                @elseif($field->field_type == 'select')
                                    <select disabled class="w-full px-3 py-2 border rounded bg-gray-50">
                                        <option>اختر...</option>
                                        @if($field->options)
                                            @foreach($field->options as $option)
                                                <option>{{ $option }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                @elseif($field->field_type == 'radio')
                                    <div class="space-y-1">
                                        @if($field->options)
                                            @foreach($field->options as $option)
                                                <label class="flex items-center">
                                                    <input type="radio" disabled class="ml-2">
                                                    <span>{{ $option }}</span>
                                                </label>
                                            @endforeach
                                        @endif
                                    </div>
                                @elseif($field->field_type == 'checkbox')
                                    <div class="space-y-1">
                                        @if($field->options)
                                            @foreach($field->options as $option)
                                                <label class="flex items-center">
                                                    <input type="checkbox" disabled class="ml-2">
                                                    <span>{{ $option }}</span>
                                                </label>
                                            @endforeach
                                        @endif
                                    </div>
                                @elseif($field->field_type == 'file')
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                                        <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-2"></i>
                                        <p class="text-gray-500 text-sm">اسحب الملف هنا أو انقر للاختيار</p>
                                    </div>
                                @elseif($field->field_type == 'url')
                                    <input type="url" disabled placeholder="https://..." class="w-full px-3 py-2 border rounded bg-gray-50">
                                @elseif($field->field_type == 'section')
                                    <h3 class="text-lg font-bold text-gray-900 border-b pb-2">{{ $field->label }}</h3>
                                @endif
                            </div>
                            @if($field->help_text)
                                <p class="text-xs text-gray-500 mt-1">{{ $field->help_text }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Empty State -->
                @if($form->fields->count() == 0)
                    <div id="empty-state" class="text-center py-12">
                        <i class="fas fa-plus-circle text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">اسحب عنصراً من القائمة الجانبية هنا</p>
                    </div>
                @endif

                <!-- Submit Button Preview -->
                <div class="bg-white rounded-lg shadow-sm p-6 mt-4 text-center">
                    <button class="bg-teal-600 text-white px-8 py-3 rounded-lg opacity-50 cursor-not-allowed">
                        إرسال
                    </button>
                </div>
            </div>
        </div>

        <!-- Right Sidebar - Field Settings -->
        <div class="w-80 bg-white border-r h-full sidebar p-4" id="settings-panel">
            <div id="no-selection" class="text-center py-8">
                <i class="fas fa-mouse-pointer text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">اختر حقلاً لتعديل إعداداته</p>
            </div>

            <div id="field-settings" class="hidden">
                <div class="flex items-center justify-between mb-4 pb-2 border-b">
                    <h3 class="font-bold text-gray-900">إعدادات الحقل</h3>
                    <button onclick="deleteSelectedField()" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">العنوان</label>
                        <input type="text" id="setting-label" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500"
                            onchange="updateField('label', this.value)">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">النص التوضيحي (Placeholder)</label>
                        <input type="text" id="setting-placeholder" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500"
                            onchange="updateField('placeholder', this.value)">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">نص المساعدة</label>
                        <input type="text" id="setting-help" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500"
                            onchange="updateField('help_text', this.value)">
                    </div>

                    <div id="options-section" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">الخيارات (سطر لكل خيار)</label>
                        <textarea id="setting-options" rows="4" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500"
                            onchange="updateField('options', this.value)"></textarea>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="setting-required" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500"
                            onchange="updateField('required', this.checked)">
                        <label for="setting-required" class="mr-2 text-sm text-gray-700">حقل إلزامي</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let selectedFieldId = null;
        let fieldCounter = {{ $form->fields->count() }};

        // Initialize Sortable for palette (clone mode)
        const palette = document.getElementById('field-palette');
        new Sortable(palette, {
            group: {
                name: 'shared',
                pull: 'clone',
                put: false
            },
            sort: false
        });

        // Initialize Sortable for workspace
        const workspace = document.getElementById('workspace');
        new Sortable(workspace, {
            group: 'shared',
            animation: 150,
            ghostClass: 'sortable-ghost',
            dragClass: 'sortable-drag',
            onAdd: function(evt) {
                const fieldType = evt.item.dataset.type;
                const newField = createFieldElement(fieldType);
                evt.item.replaceWith(newField);
                fieldCounter++;
                selectField(newField.dataset.fieldId);
                saveNewField(fieldType, newField.dataset.fieldId);
            },
            onEnd: function(evt) {
                updateFieldOrder();
            }
        });

        function createFieldElement(type) {
            const id = 'new_' + fieldCounter;
            const div = document.createElement('div');
            div.className = 'workspace-field bg-white rounded-lg shadow-sm p-4 cursor-pointer';
            div.dataset.fieldId = id;
            div.onclick = () => selectField(id);
            
            const label = getFieldLabel(type);
            div.innerHTML = `
                <div class="flex items-center justify-between mb-2">
                    <label class="font-medium text-gray-700">${label}</label>
                </div>
                <div class="text-gray-400 text-sm">
                    ${getFieldPreview(type)}
                </div>
            `;
            return div;
        }

        function getFieldLabel(type) {
            const labels = {
                text: 'نص قصير',
                textarea: 'نص طويل',
                email: 'بريد إلكتروني',
                number: 'رقم',
                phone: 'هاتف',
                date: 'تاريخ',
                select: 'قائمة منسدلة',
                radio: 'اختيار واحد',
                checkbox: 'اختيار متعدد',
                file: 'رفع ملف',
                url: 'رابط',
                section: 'عنوان قسم'
            };
            return labels[type] || type;
        }

        function getFieldPreview(type) {
            const previews = {
                text: '<input type="text" disabled placeholder="نص قصير" class="w-full px-3 py-2 border rounded bg-gray-50">',
                textarea: '<textarea disabled placeholder="نص طويل" rows="3" class="w-full px-3 py-2 border rounded bg-gray-50"></textarea>',
                email: '<input type="email" disabled placeholder="example@email.com" class="w-full px-3 py-2 border rounded bg-gray-50">',
                number: '<input type="number" disabled placeholder="0" class="w-full px-3 py-2 border rounded bg-gray-50">',
                phone: '<input type="tel" disabled placeholder="05xxxxxxxx" class="w-full px-3 py-2 border rounded bg-gray-50">',
                date: '<input type="date" disabled class="w-full px-3 py-2 border rounded bg-gray-50">',
                select: '<select disabled class="w-full px-3 py-2 border rounded bg-gray-50"><option>اختر...</option></select>',
                radio: '<label class="flex items-center"><input type="radio" disabled class="ml-2"><span>خيار 1</span></label>',
                checkbox: '<label class="flex items-center"><input type="checkbox" disabled class="ml-2"><span>خيار 1</span></label>',
                file: '<div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center"><i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-2"></i><p class="text-gray-500 text-sm">اسحب الملف هنا</p></div>',
                url: '<input type="url" disabled placeholder="https://..." class="w-full px-3 py-2 border rounded bg-gray-50">',
                section: '<h3 class="text-lg font-bold text-gray-900 border-b pb-2">عنوان القسم</h3>'
            };
            return previews[type] || '';
        }

        function selectField(fieldId) {
            selectedFieldId = fieldId;
            document.querySelectorAll('.workspace-field').forEach(el => el.classList.remove('selected'));
            const field = document.querySelector(`[data-field-id="${fieldId}"]`);
            if (field) field.classList.add('selected');
            
            document.getElementById('no-selection').classList.add('hidden');
            document.getElementById('field-settings').classList.remove('hidden');
            
            // Load field data
            loadFieldSettings(fieldId);
        }

        function loadFieldSettings(fieldId) {
            // Fetch field data from server or cache
            // For now, reset settings
            document.getElementById('setting-label').value = '';
            document.getElementById('setting-placeholder').value = '';
            document.getElementById('setting-help').value = '';
            document.getElementById('setting-required').checked = false;
        }

        function updateField(property, value) {
            if (!selectedFieldId) return;
            
            fetch(`/forms/{{ $form->id }}/fields/${selectedFieldId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ [property]: value })
            })
            .then(response => response.json())
            .then(data => {
                // Update UI
                const field = document.querySelector(`[data-field-id="${selectedFieldId}"]`);
                if (field && property === 'label') {
                    field.querySelector('label').textContent = value;
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function saveNewField(type, tempId) {
            const data = {
                field_type: type,
                label: getFieldLabel(type),
                order: fieldCounter
            };

            fetch(`{{ route('forms.fields.store', $form) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('فشل حفظ الحقل: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                // Update temp ID with real ID
                const field = document.querySelector(`[data-field-id="${tempId}"]`);
                if (field) {
                    field.dataset.fieldId = data.id;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء حفظ الحقل: ' + error.message);
            });
        }

        function updateFieldOrder() {
            const fields = [];
            document.querySelectorAll('.workspace-field').forEach((el, index) => {
                fields.push({ id: el.dataset.fieldId, order: index });
            });

            fetch(`{{ route('forms.fields.reorder', $form) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ fields })
            })
            .catch(error => console.error('Error:', error));
        }

        function deleteSelectedField() {
            if (!selectedFieldId || !confirm('هل أنت متأكد من حذف هذا الحقل؟')) return;

            fetch(`/forms/{{ $form->id }}/fields/${selectedFieldId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(() => {
                const field = document.querySelector(`[data-field-id="${selectedFieldId}"]`);
                if (field) field.remove();
                selectedFieldId = null;
                document.getElementById('no-selection').classList.remove('hidden');
                document.getElementById('field-settings').classList.add('hidden');
            })
            .catch(error => console.error('Error:', error));
        }

        function saveForm() {
            const title = document.getElementById('form-title').value;
            const description = document.getElementById('form-description').value;

            fetch(`{{ route('forms.update', $form) }}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ title, description, status: 'active' })
            })
            .then(() => alert('تم حفظ النموذج بنجاح'))
            .catch(error => console.error('Error:', error));
        }

        function previewForm() {
            window.open('{{ route('forms.public', $form->slug) }}', '_blank');
        }
    </script>
</body>
</html>
