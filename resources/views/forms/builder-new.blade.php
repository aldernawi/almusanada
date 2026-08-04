@php
    $submitButton = data_get($form->settings ?? [], 'submit_button', []);
    $submitButtonLabel = $submitButton['label'] ?? 'Submit Form';
    $submitButtonColor = $submitButton['color'] ?? '#6366f1';
    $defaultPlaceholders = [
        'text' => 'Enter text here',
        'textarea' => 'Enter longer text here',
        'email' => 'name@example.com',
        'number' => '0',
        'phone' => '+1 555 000 0000',
        'date' => 'YYYY-MM-DD',
        'select' => 'Choose an option...',
        'url' => 'https://example.com',
        'file' => 'Drop a file here or click to upload',
    ];
@endphp
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Builder - {{ $form->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #f3f4f6; }
        .builder-wrapper { display: flex; height: 100vh; padding-top: 60px; }
        .sidebar { width: 280px; background: #fff; border-right: 1px solid #e5e7eb; overflow-y: auto; }
        .sidebar-header { padding: 20px; border-bottom: 1px solid #e5e7eb; }
        .field-group { padding: 15px; }
        .field-group-title { font-size: 11px; font-weight: 700; color: #9ca3af; text-transform: uppercase; margin-bottom: 10px; letter-spacing: .5px; }
        .field-card { display: flex; align-items: center; padding: 12px; margin-bottom: 8px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; cursor: pointer; transition: all .2s; }
        .field-card:hover { background: #fff; border-color: #6366f1; box-shadow: 0 4px 12px rgba(99,102,241,.15); transform: translateY(-1px); }
        .field-card i { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; background: #e0e7ff; color: #6366f1; border-radius: 6px; margin-right: 10px; font-size: 14px; }
        .field-card span { font-size: 13px; font-weight: 600; color: #374151; }
        .workspace { flex: 1; background: #f3f4f6; overflow-y: auto; padding: 30px; }
        .form-preview { max-width: 800px; margin: 0 auto; background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,.08); overflow: hidden; }
        .form-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px; color: #fff; text-align: center; }
        .form-body { padding: 30px 40px; min-height: 400px; }
        .builder-field { position: relative; padding: 20px; margin-bottom: 16px; border: 2px solid transparent; border-radius: 12px; transition: all .2s; background: #fafafa; }
        .builder-field:hover { border-color: #c7d2fe; background: #f5f3ff; }
        .builder-field.active { border-color: #6366f1; background: #eef2ff; box-shadow: 0 0 0 4px rgba(99,102,241,.1); }
        .builder-field label { display: block; font-weight: 700; color: #1f2937; margin-bottom: 8px; font-size: 15px; }
        .builder-field .required { color: #ef4444; }
        .field-input { width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 14px; background: #fff; color: #9ca3af; pointer-events: none; }
        .field-actions { position: absolute; top: 8px; right: 8px; display: none; gap: 4px; }
        .builder-field:hover .field-actions { display: flex; }
        .field-actions button { width: 28px; height: 28px; border-radius: 6px; border: none; background: #fff; color: #6b7280; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,.1); font-size: 12px; }
        .field-actions button:hover { background: #ef4444; color: #fff; }
        .field-actions button.edit:hover { background: #6366f1; color: #fff; }
        .settings-panel { width: 320px; background: #fff; border-left: 1px solid #e5e7eb; overflow-y: auto; }
        .settings-header { padding: 20px; border-bottom: 1px solid #e5e7eb; }
        .settings-content { padding: 20px; }
        .setting-row { margin-bottom: 20px; }
        .setting-row label { display: block; font-size: 13px; font-weight: 700; color: #374151; margin-bottom: 6px; }
        .setting-row input, .setting-row textarea { width: 100%; padding: 10px 14px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px; transition: all .2s; }
        .setting-row input:focus, .setting-row textarea:focus { border-color: #6366f1; outline: none; }
        .setting-row textarea { resize: vertical; min-height: 80px; }
        .checkbox-wrapper { display: flex; align-items: center; gap: 8px; }
        .checkbox-wrapper input { width: auto; }
        .top-bar { position: fixed; top: 0; left: 0; right: 0; height: 60px; background: #fff; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: space-between; padding: 0 24px; z-index: 100; }
        .logo { font-size: 20px; font-weight: 800; color: #6366f1; }
        .actions { display: flex; gap: 10px; }
        .top-bar button, .top-bar a { padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer; border: none; transition: all .2s; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
        .btn-secondary { background: #f3f4f6; color: #374151; }
        .btn-secondary:hover { background: #e5e7eb; }
        .btn-primary { background: #6366f1; color: #fff; }
        .btn-primary:hover { background: #4f46e5; }
        .btn-purple { background: #8b5cf6; color: #fff; }
        .btn-purple:hover { background: #7c3aed; }
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state i { font-size: 48px; color: #d1d5db; margin-bottom: 16px; }
        .empty-state p { color: #9ca3af; font-size: 15px; }
        .tabs { display: flex; border-bottom: 1px solid #e5e7eb; }
        .tab { padding: 12px 20px; font-size: 13px; font-weight: 700; color: #9ca3af; cursor: pointer; border-bottom: 2px solid transparent; transition: all .2s; }
        .tab:hover { color: #6366f1; }
        .tab.active { color: #6366f1; border-bottom-color: #6366f1; }
        .toast { position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%) translateY(100px); background: #1f2937; color: #fff; padding: 14px 28px; border-radius: 10px; font-size: 14px; font-weight: 600; box-shadow: 0 10px 40px rgba(0,0,0,.2); opacity: 0; transition: all .3s; z-index: 1000; }
        .toast.show { transform: translateX(-50%) translateY(0); opacity: 1; }
    </style>
</head>
<body>
    <div class="top-bar">
        <div style="display: flex; align-items: center; gap: 20px;">
            <div class="logo"><i class="fas fa-wpforms"></i> Almusanada</div>
            <div style="width: 1px; height: 24px; background: #e5e7eb;"></div>
            <span style="font-size: 14px; color: #6b7280;">{{ $form->title }}</span>
            <span style="padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; {{ $form->status == 'active' ? 'background: #d1fae5; color: #059669;' : 'background: #f3f4f6; color: #6b7280;' }}">
                {{ $form->status == 'active' ? 'Published' : 'Draft' }}
            </span>
        </div>
        <div class="actions">
            <a href="{{ route('forms.index') }}" class="btn-secondary"><i class="fas fa-arrow-left"></i> Forms</a>
            <button type="button" onclick="previewForm()" class="btn-secondary"><i class="fas fa-eye"></i> Preview</button>
            <a href="{{ route('forms.share', $form) }}" class="btn-purple"><i class="fas fa-share-alt"></i> Share</a>
            <button type="button" onclick="saveForm()" class="btn-primary"><i class="fas fa-save"></i> Save</button>
        </div>
    </div>

    <div class="builder-wrapper">
        <div class="sidebar">
            <div class="sidebar-header">
                <h3 style="font-size: 16px; font-weight: 800; color: #1f2937;">Form Elements</h3>
                <p style="font-size: 12px; color: #9ca3af; margin-top: 4px;">Click an element to add it.</p>
            </div>

            <div class="field-group">
                <div class="field-group-title">Basic Fields</div>
                <div class="field-card" onclick="addField('text')"><i class="fas fa-font"></i><span>Short Text</span></div>
                <div class="field-card" onclick="addField('textarea')"><i class="fas fa-align-left"></i><span>Long Text</span></div>
                <div class="field-card" onclick="addField('email')"><i class="fas fa-envelope"></i><span>Email</span></div>
                <div class="field-card" onclick="addField('number')"><i class="fas fa-hashtag"></i><span>Number</span></div>
                <div class="field-card" onclick="addField('phone')"><i class="fas fa-phone"></i><span>Phone</span></div>
                <div class="field-card" onclick="addField('date')"><i class="fas fa-calendar"></i><span>Date</span></div>
            </div>

            <div class="field-group">
                <div class="field-group-title">Choices</div>
                <div class="field-card" onclick="addField('select')"><i class="fas fa-list"></i><span>Dropdown</span></div>
                <div class="field-card" onclick="addField('radio')"><i class="fas fa-dot-circle"></i><span>Single Choice</span></div>
                <div class="field-card" onclick="addField('checkbox')"><i class="fas fa-check-square"></i><span>Multiple Choice</span></div>
            </div>

            <div class="field-group">
                <div class="field-group-title">Files and Links</div>
                <div class="field-card" onclick="addField('file')"><i class="fas fa-file-upload"></i><span>File Upload</span></div>
                <div class="field-card" onclick="addField('url')"><i class="fas fa-link"></i><span>Website URL</span></div>
            </div>
        </div>

        <div class="workspace">
            <div class="form-preview">
                <div class="form-header">
                    <input type="text" id="form-title-input" value="{{ $form->title }}"
                        style="background: transparent; border: none; color: #fff; font-size: 28px; font-weight: 800; text-align: center; width: 100%; outline: none;"
                        onchange="updateFormTitle(this.value)">
                    <textarea id="form-description-input" rows="2"
                        style="background: transparent; border: none; color: rgba(255,255,255,.9); font-size: 15px; text-align: center; width: 100%; outline: none; resize: none; margin-top: 8px;"
                        placeholder="Add a short form description..."
                        onchange="updateFormDescription(this.value)">{{ $form->description }}</textarea>
                </div>

                <div class="form-body" id="fields-container">
                    @forelse($form->fields as $field)
                        @php
                            $defaultOptions = $field->options ? implode("\n", $field->options) : "Option 1\nOption 2\nOption 3";
                        @endphp
                        <div class="builder-field {{ $loop->first ? 'active' : '' }}"
                            data-field-id="{{ $field->id }}"
                            data-type="{{ $field->field_type }}"
                            data-label="{{ $field->label }}"
                            data-placeholder="{{ $field->placeholder }}"
                            data-help="{{ $field->help_text }}"
                            data-required="{{ $field->required ? '1' : '0' }}"
                            data-options="{{ e($defaultOptions) }}"
                            onclick="selectField({{ $field->id }})">
                            <div class="field-actions">
                                <button type="button" class="edit" onclick="event.stopPropagation(); selectField({{ $field->id }})"><i class="fas fa-pen"></i></button>
                                <button type="button" onclick="event.stopPropagation(); deleteField({{ $field->id }})"><i class="fas fa-trash"></i></button>
                            </div>
                            <label>{{ $field->label }} @if($field->required)<span class="required">*</span>@endif</label>
                            <div class="field-preview">
                                @if($field->field_type == 'radio' || $field->field_type == 'checkbox')
                                    <div style="display: flex; flex-direction: column; gap: 8px;">
                                        @foreach(($field->options ?: ['Option 1', 'Option 2', 'Option 3']) as $option)
                                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                                <input type="{{ $field->field_type }}" disabled style="width: 18px; height: 18px;">
                                                <span style="color: #374151;">{{ $option }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @elseif($field->field_type == 'file')
                                    <div class="field-input" style="text-align: center; padding: 30px;">
                                        <i class="fas fa-cloud-upload-alt" style="font-size: 24px; color: #9ca3af; display: block; margin-bottom: 8px;"></i>
                                        Drop a file here or click to upload
                                    </div>
                                @else
                                    <div class="field-input">{{ $field->placeholder ?: ($defaultPlaceholders[$field->field_type] ?? '') }}</div>
                                @endif
                            </div>
                            @if($field->help_text)
                                <p class="field-help" style="font-size: 12px; color: #9ca3af; margin-top: 6px;">{{ $field->help_text }}</p>
                            @else
                                <p class="field-help" style="font-size: 12px; color: #9ca3af; margin-top: 6px; display: none;"></p>
                            @endif
                        </div>
                    @empty
                        <div class="empty-state" id="empty-state">
                            <i class="fas fa-mouse-pointer"></i>
                            <p>Click an element from the left panel to add your first field.</p>
                        </div>
                    @endforelse
                </div>

                <div style="padding: 0 40px 30px;">
                    <button id="submit-button-preview" style="width: 100%; padding: 16px; background: {{ $submitButtonColor }}; color: #fff; border: none; border-radius: 12px; font-size: 16px; font-weight: 800; cursor: default;">
                        Submit Form <i class="fas fa-paper-plane" style="margin-left: 8px;"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="settings-panel" id="settings-panel">
            <div class="settings-header">
                <h3 style="font-size: 16px; font-weight: 800; color: #1f2937;">Field Settings</h3>
            </div>
            <div class="tabs">
                <div class="tab active" onclick="switchTab(this, 'general')">General</div>
                <div class="tab" onclick="switchTab(this, 'options')">Options</div>
                <div class="tab" onclick="switchTab(this, 'style')">Style</div>
            </div>

            <div class="settings-content" id="settings-general">
                <div class="setting-row">
                    <label>Field Label</label>
                    <input type="text" id="setting-label" placeholder="Enter field label" oninput="updateFieldSetting('label', this.value)">
                </div>
                <div class="setting-row">
                    <label>Placeholder</label>
                    <input type="text" id="setting-placeholder" placeholder="Helpful placeholder text" oninput="updateFieldSetting('placeholder', this.value)">
                </div>
                <div class="setting-row">
                    <label>Help Text</label>
                    <textarea id="setting-help" placeholder="Optional helper text shown under the field" oninput="updateFieldSetting('help_text', this.value)"></textarea>
                </div>
                <div class="setting-row">
                    <div class="checkbox-wrapper">
                        <input type="checkbox" id="setting-required" onchange="updateFieldSetting('required', this.checked)">
                        <label for="setting-required" style="margin: 0; cursor: pointer;">Required field</label>
                    </div>
                </div>
            </div>

            <div class="settings-content" id="settings-options" style="display: none;">
                <div class="setting-row">
                    <label>Choices</label>
                    <textarea id="setting-options" rows="6" placeholder="Option 1&#10;Option 2&#10;Option 3" oninput="updateFieldSetting('options', this.value)"></textarea>
                </div>
            </div>

            <div class="settings-content" id="settings-style" style="display: none;">
                <div class="setting-row">
                    <label>Submit Button Text</label>
                    <input type="text" id="submit-button-label" value="{{ $submitButtonLabel }}" maxlength="80" oninput="updateSubmitButtonSetting('label', this.value)">
                </div>
                <div class="setting-row">
                    <label>Submit Button Color</label>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <input type="color" id="submit-button-color" value="{{ $submitButtonColor }}" oninput="updateSubmitButtonSetting('color', this.value)" style="width: 48px; height: 40px; padding: 2px; cursor: pointer;">
                        <input type="text" value="{{ $submitButtonColor }}" id="submit-button-color-text" maxlength="7" oninput="updateSubmitButtonSetting('color', this.value)" style="flex: 1; direction: ltr; text-align: left;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="toast" id="toast"></div>

    <script>
        let selectedFieldId = null;
        let fieldCounter = {{ $form->fields->count() }};
        const formId = {{ $form->id }};
        let submitButtonSettings = {
            label: @json($submitButtonLabel),
            color: @json($submitButtonColor),
        };
        let submitButtonSaveTimer = null;
        const fieldLabels = {
            text: 'Short Text',
            textarea: 'Long Text',
            email: 'Email',
            number: 'Number',
            phone: 'Phone',
            date: 'Date',
            select: 'Dropdown',
            radio: 'Single Choice',
            checkbox: 'Multiple Choice',
            file: 'File Upload',
            url: 'Website URL',
        };
        const placeholderMap = {
            text: 'Enter text here',
            textarea: 'Enter longer text here',
            email: 'name@example.com',
            number: '0',
            phone: '+1 555 000 0000',
            date: 'YYYY-MM-DD',
            select: 'Choose an option...',
            url: 'https://example.com',
            file: 'Drop a file here or click to upload',
        };

        function builderRequest(url, options = {}) {
            return fetch(url, {
                ...options,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    ...(options.body ? { 'Content-Type': 'application/json' } : {}),
                    ...(options.headers || {})
                }
            }).then(async response => {
                const payload = await response.json().catch(() => ({}));
                if (!response.ok) {
                    throw new Error(payload.message || 'Unable to save changes');
                }
                return payload;
            });
        }

        function showToast(message) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
        }

        function defaultPlaceholder(type) {
            return placeholderMap[type] || '';
        }

        function renderFieldPreview(field) {
            const type = field.dataset.type;
            const label = field.dataset.label || fieldLabels[type] || type;
            const placeholder = field.dataset.placeholder || defaultPlaceholder(type);
            const help = field.dataset.help || '';
            const required = field.dataset.required === '1';
            const options = (field.dataset.options || 'Option 1\nOption 2\nOption 3')
                .split('\n')
                .map(option => option.trim())
                .filter(Boolean);

            field.querySelector('label').innerHTML = `${escapeHtml(label)} ${required ? '<span class="required">*</span>' : ''}`;

            const preview = field.querySelector('.field-preview');
            if (type === 'radio' || type === 'checkbox') {
                preview.innerHTML = `<div style="display: flex; flex-direction: column; gap: 8px;">${
                    options.map(option => `
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="${type}" disabled style="width: 18px; height: 18px;">
                            <span style="color: #374151;">${escapeHtml(option)}</span>
                        </label>
                    `).join('')
                }</div>`;
            } else if (type === 'file') {
                preview.innerHTML = `<div class="field-input" style="text-align: center; padding: 30px;">
                    <i class="fas fa-cloud-upload-alt" style="font-size: 24px; color: #9ca3af; display: block; margin-bottom: 8px;"></i>
                    Drop a file here or click to upload
                </div>`;
            } else {
                preview.innerHTML = `<div class="field-input">${escapeHtml(placeholder)}</div>`;
            }

            const helpEl = field.querySelector('.field-help');
            helpEl.textContent = help;
            helpEl.style.display = help ? 'block' : 'none';
        }

        function escapeHtml(value) {
            return String(value ?? '').replace(/[&<>"']/g, (character) => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;',
            }[character]));
        }

        function addField(type) {
            const container = document.getElementById('fields-container');
            const emptyState = document.getElementById('empty-state');
            if (emptyState) emptyState.remove();

            const tempId = 'new_' + Date.now();
            const field = document.createElement('div');
            field.className = 'builder-field active';
            field.dataset.fieldId = tempId;
            field.dataset.type = type;
            field.dataset.label = fieldLabels[type] || type;
            field.dataset.placeholder = defaultPlaceholder(type);
            field.dataset.help = '';
            field.dataset.required = '0';
            field.dataset.options = 'Option 1\nOption 2\nOption 3';
            field.onclick = function() { selectField(field.dataset.fieldId); };
            field.innerHTML = `
                <div class="field-actions">
                    <button type="button" class="edit" onclick="event.stopPropagation(); selectField('${tempId}')"><i class="fas fa-pen"></i></button>
                    <button type="button" onclick="event.stopPropagation(); deleteField('${tempId}')"><i class="fas fa-trash"></i></button>
                </div>
                <label></label>
                <div class="field-preview"></div>
                <p class="field-help" style="font-size: 12px; color: #9ca3af; margin-top: 6px; display: none;"></p>
            `;

            container.appendChild(field);
            renderFieldPreview(field);
            selectField(tempId);

            builderRequest(`/forms/${formId}/fields`, {
                method: 'POST',
                body: JSON.stringify({
                    field_type: type,
                    label: field.dataset.label,
                    placeholder: field.dataset.placeholder,
                    options: type === 'select' || type === 'radio' || type === 'checkbox'
                        ? field.dataset.options.split('\n')
                        : null,
                    order: fieldCounter++,
                })
            })
            .then(data => {
                field.dataset.fieldId = data.id;
                field.onclick = function() { selectField(data.id); };
                field.querySelector('.edit').setAttribute('onclick', `event.stopPropagation(); selectField('${data.id}')`);
                field.querySelector('.field-actions button:last-child').setAttribute('onclick', `event.stopPropagation(); deleteField('${data.id}')`);
                selectedFieldId = data.id;
                showToast('Field added');
            })
            .catch(error => showToast(error.message));
        }

        function selectField(fieldId) {
            selectedFieldId = fieldId;
            document.querySelectorAll('.builder-field').forEach(el => el.classList.remove('active'));
            const field = document.querySelector(`[data-field-id="${fieldId}"]`);
            if (!field) return;

            field.classList.add('active');
            document.getElementById('setting-label').value = field.dataset.label || '';
            document.getElementById('setting-placeholder').value = field.dataset.placeholder || '';
            document.getElementById('setting-help').value = field.dataset.help || '';
            document.getElementById('setting-required').checked = field.dataset.required === '1';
            document.getElementById('setting-options').value = field.dataset.options || '';
        }

        function deleteField(fieldId) {
            if (!confirm('Delete this field?')) return;

            const field = document.querySelector(`[data-field-id="${fieldId}"]`);
            if (field) field.remove();

            if (!fieldId.toString().startsWith('new_')) {
                builderRequest(`/forms/${formId}/fields/${fieldId}`, { method: 'DELETE' })
                    .catch(error => showToast(error.message));
            }

            selectedFieldId = null;
            showToast('Field deleted');
        }

        function updateFieldSetting(property, value) {
            if (!selectedFieldId) return;
            const field = document.querySelector(`[data-field-id="${selectedFieldId}"]`);
            if (!field) return;

            if (property === 'help_text') {
                field.dataset.help = value;
            } else if (property === 'required') {
                field.dataset.required = value ? '1' : '0';
            } else {
                field.dataset[property] = value;
            }

            renderFieldPreview(field);

            if (!selectedFieldId.toString().startsWith('new_')) {
                const payload = {};
                if (property === 'options') {
                    payload.options = value.split('\n').map(option => option.trim()).filter(Boolean);
                } else {
                    payload[property] = value;
                }

                builderRequest(`/forms/${formId}/fields/${selectedFieldId}`, {
                    method: 'PUT',
                    body: JSON.stringify(payload)
                }).catch(error => showToast(error.message));
            }
        }

        function switchTab(el, tab) {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            el.classList.add('active');
            document.querySelectorAll('.settings-content').forEach(c => c.style.display = 'none');
            document.getElementById('settings-' + tab).style.display = 'block';
        }

        function renderSubmitButtonPreview() {
            const preview = document.getElementById('submit-button-preview');
            const colorInput = document.getElementById('submit-button-color');
            const colorText = document.getElementById('submit-button-color-text');
            const labelInput = document.getElementById('submit-button-label');
            const label = submitButtonSettings.label || 'Submit Form';
            const color = /^#[0-9A-Fa-f]{6}$/.test(submitButtonSettings.color) ? submitButtonSettings.color : '#6366f1';

            preview.style.background = color;
            preview.innerHTML = `<span>${escapeHtml(label)}</span> <i class="fas fa-paper-plane" style="margin-left: 8px;"></i>`;
            colorInput.value = color;
            colorText.value = color;
            if (labelInput.value !== label) labelInput.value = label;
        }

        function updateSubmitButtonSetting(property, value) {
            submitButtonSettings[property] = property === 'color' ? value.trim() : value;
            renderSubmitButtonPreview();

            clearTimeout(submitButtonSaveTimer);
            submitButtonSaveTimer = setTimeout(saveSubmitButtonSettings, 350);
        }

        function saveSubmitButtonSettings() {
            if (!/^#[0-9A-Fa-f]{6}$/.test(submitButtonSettings.color)) {
                showToast('Enter a valid hex color');
                return Promise.resolve();
            }

            return builderRequest(`/forms/${formId}`, {
                method: 'PUT',
                body: JSON.stringify({
                    settings: {
                        submit_button: submitButtonSettings,
                    }
                })
            });
        }

        function updateFormTitle(title) {
            builderRequest(`/forms/${formId}`, {
                method: 'PUT',
                body: JSON.stringify({ title, status: 'active' })
            }).catch(error => showToast(error.message));
        }

        function updateFormDescription(description) {
            builderRequest(`/forms/${formId}`, {
                method: 'PUT',
                body: JSON.stringify({ description, status: 'active' })
            }).catch(error => showToast(error.message));
        }

        function saveForm() {
            clearTimeout(submitButtonSaveTimer);
            saveSubmitButtonSettings()
                .then(() => showToast('Form saved'))
                .catch(error => showToast(error.message));
        }

        function previewForm() {
            window.open('{{ route('forms.public', $form->slug) }}', '_blank');
        }

        document.querySelectorAll('.builder-field').forEach(renderFieldPreview);
        renderSubmitButtonPreview();
    </script>
</body>
</html>
