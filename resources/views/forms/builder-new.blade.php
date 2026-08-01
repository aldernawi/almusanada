<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>منشئ النماذج - {{ $form->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *{font-family:'Tajawal',sans-serif;margin:0;padding:0;box-sizing:border-box}
        body{background:#f0f2f5;overflow:hidden}
        .top-bar{position:fixed;top:0;left:0;right:0;height:60px;background:#fff;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;justify-content:space-between;padding:0 24px;z-index:100;box-shadow:0 1px 3px rgba(0,0,0,.05)}
        .top-bar-left{display:flex;align-items:center;gap:16px}
        .top-bar-logo{font-size:22px;font-weight:800;background:linear-gradient(135deg,#1f4277,#8b5cf6);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
        .top-bar-divider{width:1px;height:24px;background:#e5e7eb}
        .top-bar-title{font-size:15px;font-weight:600;color:#374151;max-width:300px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
        .status-badge{padding:4px 14px;border-radius:20px;font-size:12px;font-weight:600}
        .status-active{background:#d1fae5;color:#059669}.status-inactive{background:#f3f4f6;color:#6b7280}
        .top-bar-actions{display:flex;align-items:center;gap:8px}
        .btn{padding:9px 18px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;border:none;transition:all .2s;text-decoration:none;display:inline-flex;align-items:center;gap:6px}
        .btn-ghost{background:transparent;color:#6b7280}.btn-ghost:hover{background:#f3f4f6;color:#374151}
        .btn-secondary{background:#f3f4f6;color:#374151}.btn-secondary:hover{background:#e5e7eb}
        .btn-primary{background:#1f4277;color:#fff}.btn-primary:hover{background:#4f46e5;box-shadow:0 4px 12px rgba(99,102,241,.3)}
        .btn-cyan{background:#8b5cf6;color:#fff}.btn-cyan:hover{background:#7c3aed;box-shadow:0 4px 12px rgba(139,92,246,.3)}
        .tab-nav{position:fixed;top:60px;left:0;right:0;height:48px;background:#fff;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;gap:4px;padding:0 24px;z-index:99}
        .tab-btn{padding:10px 20px;font-size:13px;font-weight:600;color:#9ca3af;cursor:pointer;border:none;background:transparent;border-bottom:2px solid transparent;transition:all .2s;display:flex;align-items:center;gap:6px}
        .tab-btn:hover{color:#1f4277}.tab-btn.active{color:#1f4277;border-bottom-color:#1f4277}
        .main-container{padding-top:108px;height:100vh;display:flex}
        .tab-content{display:none;flex:1;overflow:hidden}.tab-content.active{display:flex}
        .sidebar-left{width:280px;background:#fff;border-left:1px solid #e5e7eb;overflow-y:auto;flex-shrink:0}
        .sidebar-header{padding:18px 20px;border-bottom:1px solid #f3f4f6}
        .sidebar-header h3{font-size:15px;font-weight:700;color:#1f2937}.sidebar-header p{font-size:12px;color:#9ca3af;margin-top:3px}
        .field-group{padding:12px 16px}
        .field-group-title{font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;margin-bottom:8px;letter-spacing:.8px}
        .field-card{display:flex;align-items:center;padding:10px 12px;margin-bottom:6px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;cursor:pointer;transition:all .2s}
        .field-card:hover{background:#fff;border-color:#1f4277;box-shadow:0 2px 8px rgba(99,102,241,.12);transform:translateY(-1px)}
        .field-card i{width:30px;height:30px;display:flex;align-items:center;justify-content:center;background:#e0e7ff;color:#1f4277;border-radius:6px;margin-left:10px;font-size:13px;flex-shrink:0}
        .field-card span{font-size:13px;font-weight:500;color:#374151}
        .workspace{flex:1;background:#f0f2f5;overflow-y:auto;padding:30px}
        .form-preview{max-width:720px;margin:0 auto;background:#fff;border-radius:16px;box-shadow:0 4px 24px rgba(0,0,0,.06);overflow:hidden}
        .form-header{padding:36px 40px 28px;text-align:center;color:#fff}
        .form-header h1{font-size:26px;font-weight:700;margin-bottom:6px}.form-header p{font-size:14px;opacity:.85}
        .form-body{padding:24px 40px;min-height:300px}
        .builder-field{position:relative;padding:18px;margin-bottom:12px;border:2px solid transparent;border-radius:12px;transition:all .2s;background:#f9fafb;cursor:pointer}
        .builder-field:hover{border-color:#c7d2fe;background:#f5f3ff}
        .builder-field.active{border-color:#1f4277;background:#eef2ff;box-shadow:0 0 0 4px rgba(99,102,241,.08)}
        .builder-field .drag-handle{position:absolute;top:10px;right:10px;color:#d1d5db;cursor:grab;font-size:14px;opacity:0;transition:opacity .2s}
        .builder-field:hover .drag-handle{opacity:1}
        .builder-field .field-actions{position:absolute;top:10px;left:10px;display:none;gap:4px}
        .builder-field:hover .field-actions{display:flex}
        .field-actions button{width:28px;height:28px;border-radius:6px;border:none;background:#fff;color:#6b7280;cursor:pointer;box-shadow:0 2px 4px rgba(0,0,0,.08);font-size:12px;transition:all .15s}
        .field-actions button:hover{background:#ef4444;color:#fff}.field-actions button.edit:hover{background:#1f4277;color:#fff}
        .builder-field label{display:block;font-weight:600;color:#1f2937;margin-bottom:8px;font-size:14px}
        .builder-field .required{color:#ef4444}
        .field-input{width:100%;padding:11px 16px;border:2px solid #e5e7eb;border-radius:10px;font-size:14px;background:#fff;color:#9ca3af;pointer-events:none}
        .builder-field .help-text{font-size:12px;color:#9ca3af;margin-top:6px}
        .empty-state{text-align:center;padding:60px 20px}.empty-state i{font-size:48px;color:#d1d5db;margin-bottom:16px}.empty-state p{color:#9ca3af;font-size:15px}
        .sidebar-right{width:340px;background:#fff;border-right:1px solid #e5e7eb;overflow-y:auto;flex-shrink:0}
        .settings-header{padding:18px 20px;border-bottom:1px solid #f3f4f6}.settings-header h3{font-size:15px;font-weight:700;color:#1f2937}
        .settings-tabs{display:flex;border-bottom:1px solid #e5e7eb}
        .settings-tab{padding:10px 16px;font-size:12px;font-weight:600;color:#9ca3af;cursor:pointer;border-bottom:2px solid transparent;transition:all .2s}
        .settings-tab:hover{color:#1f4277}.settings-tab.active{color:#1f4277;border-bottom-color:#1f4277}
        .settings-content{padding:20px}.settings-content.hidden{display:none}
        .setting-row{margin-bottom:18px}.setting-row>label{display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px}
        .setting-row input,.setting-row textarea,.setting-row select{width:100%;padding:10px 14px;border:2px solid #e5e7eb;border-radius:8px;font-size:14px;transition:all .2s;background:#fff}
        .setting-row input:focus,.setting-row textarea:focus,.setting-row select:focus{border-color:#1f4277;outline:none;box-shadow:0 0 0 3px rgba(99,102,241,.1)}
        .setting-row textarea{resize:vertical;min-height:70px}
        .checkbox-wrapper{display:flex;align-items:center;gap:8px;padding:10px 14px;background:#f9fafb;border-radius:8px;cursor:pointer}
        .checkbox-wrapper input{width:18px;height:18px;cursor:pointer}.checkbox-wrapper label{margin:0;cursor:pointer;font-size:13px}
        .no-selection{text-align:center;padding:40px 20px}.no-selection i{font-size:40px;color:#e5e7eb;margin-bottom:12px}.no-selection p{color:#9ca3af;font-size:14px}
        .settings-tab-content{flex:1;overflow-y:auto;padding:30px}
        .settings-card{max-width:680px;margin:0 auto 20px;background:#fff;border-radius:16px;box-shadow:0 2px 12px rgba(0,0,0,.04);overflow:hidden}
        .settings-card-header{padding:18px 24px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;gap:10px}
        .settings-card-header i{width:36px;height:36px;display:flex;align-items:center;justify-content:center;background:#e0e7ff;color:#1f4277;border-radius:8px;font-size:16px}
        .settings-card-header h3{font-size:16px;font-weight:700;color:#1f2937}.settings-card-body{padding:24px}
        .form-group{margin-bottom:20px}.form-group>label{display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px}
        .form-group input,.form-group textarea,.form-group select{width:100%;padding:11px 16px;border:2px solid #e5e7eb;border-radius:10px;font-size:14px;transition:all .2s}
        .form-group input:focus,.form-group textarea:focus,.form-group select:focus{border-color:#1f4277;outline:none;box-shadow:0 0 0 3px rgba(99,102,241,.1)}
        .form-group .hint{font-size:12px;color:#9ca3af;margin-top:4px}
        .toggle-row{display:flex;align-items:center;justify-content:space-between;padding:14px 16px;background:#f9fafb;border-radius:10px;margin-bottom:10px}
        .toggle-row .toggle-info{display:flex;align-items:center;gap:10px}
        .toggle-row .toggle-info i{width:32px;height:32px;display:flex;align-items:center;justify-content:center;background:#e0e7ff;color:#1f4277;border-radius:8px;font-size:14px}
        .toggle-row .toggle-info h4{font-size:14px;font-weight:600;color:#1f2937}.toggle-row .toggle-info p{font-size:12px;color:#9ca3af}
        .toggle-switch{position:relative;width:44px;height:24px}.toggle-switch input{opacity:0;width:0;height:0}
        .toggle-slider{position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;background:#d1d5db;border-radius:24px;transition:.3s}
        .toggle-slider:before{content:"";position:absolute;height:18px;width:18px;right:3px;bottom:3px;background:#fff;border-radius:50%;transition:.3s}
        .toggle-switch input:checked+.toggle-slider{background:#1f4277}.toggle-switch input:checked+.toggle-slider:before{transform:translateX(-20px)}
        .theme-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px}
        .theme-card{border:3px solid transparent;border-radius:12px;overflow:hidden;cursor:pointer;transition:all .2s}
        .theme-card:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,0,0,.1)}.theme-card.selected{border-color:#1f4277}
        .theme-preview{height:60px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:600}
        .theme-label{padding:8px;text-align:center;font-size:12px;font-weight:500;color:#374151;background:#fff}
        .stats-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:20px}
        .stat-card{background:#fff;border-radius:12px;padding:20px;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,.04)}
        .stat-card .stat-icon{width:48px;height:48px;display:flex;align-items:center;justify-content:center;border-radius:12px;margin:0 auto 10px;font-size:20px}
        .stat-card .stat-value{font-size:28px;font-weight:800;color:#1f2937}.stat-card .stat-label{font-size:12px;color:#9ca3af;margin-top:2px}
        .toast{position:fixed;bottom:24px;left:50%;transform:translateX(-50%) translateY(100px);background:#1f2937;color:#fff;padding:12px 28px;border-radius:10px;font-size:14px;font-weight:500;box-shadow:0 10px 40px rgba(0,0,0,.2);opacity:0;transition:all .3s;z-index:1000;display:flex;align-items:center;gap:8px}
        .toast.show{transform:translateX(-50%) translateY(0);opacity:1}.toast.success{background:#059669}.toast.error{background:#dc2626}
        ::-webkit-scrollbar{width:6px}::-webkit-scrollbar-track{background:transparent}::-webkit-scrollbar-thumb{background:#d1d5db;border-radius:3px}::-webkit-scrollbar-thumb:hover{background:#9ca3af}
        .sortable-ghost{opacity:.4;background:#e0e7ff}.sortable-drag{cursor:grabbing;opacity:.9;box-shadow:0 8px 24px rgba(0,0,0,.15)}
    </style>
</head>
<body>
    <div class="top-bar">
        <div class="top-bar-left">
            <div class="top-bar-logo"><img src="{{ asset('images/logo.png') }}" alt="المُساندة" style="height:32px;filter:brightness(0) invert(1)"></div>
            <div class="top-bar-divider"></div>
            <span class="top-bar-title">{{ $form->title }}</span>
            <span class="status-badge {{ $form->status == 'active' ? 'status-active' : 'status-inactive' }}">{{ $form->status == 'active' ? 'منشور' : 'مسودة' }}</span>
        </div>
        <div class="top-bar-actions">
            <a href="{{ route('forms.index') }}" class="btn btn-ghost"><i class="fas fa-arrow-right"></i> النماذج</a>
            <button onclick="previewForm()" class="btn btn-secondary"><i class="fas fa-eye"></i> معاينة</button>
            <a href="{{ route('forms.share', $form) }}" class="btn btn-cyan"><i class="fas fa-share-alt"></i> نشر</a>
            <button onclick="saveAll()" class="btn btn-primary"><i class="fas fa-save"></i> حفظ</button>
        </div>
    </div>
    <div class="tab-nav">
        <button class="tab-btn active" onclick="switchMainTab(this,'builder')"><i class="fas fa-puzzle-piece"></i> منشئ الحقول</button>
        <button class="tab-btn" onclick="switchMainTab(this,'settings')"><i class="fas fa-cog"></i> إعدادات النموذج</button>
        <button class="tab-btn" onclick="switchMainTab(this,'customize')"><i class="fas fa-palette"></i> التخصيص</button>
        <button class="tab-btn" onclick="switchMainTab(this,'stats')"><i class="fas fa-chart-bar"></i> الإحصائيات</button>
    </div>
    <div class="main-container">
        <!-- BUILDER TAB -->
        <div class="tab-content active" id="tab-builder">
            <div class="sidebar-left">
                <div class="sidebar-header"><h3>عناصر النموذج</h3><p>انقر لإضافة عنصر للنموذج</p></div>
                <div class="field-group">
                    <div class="field-group-title">الحقول الأساسية</div>
                    <div class="field-card" onclick="addField('text')"><i class="fas fa-font"></i><span>نص قصير</span></div>
                    <div class="field-card" onclick="addField('textarea')"><i class="fas fa-align-left"></i><span>نص طويل</span></div>
                    <div class="field-card" onclick="addField('email')"><i class="fas fa-envelope"></i><span>بريد إلكتروني</span></div>
                    <div class="field-card" onclick="addField('number')"><i class="fas fa-hashtag"></i><span>رقم</span></div>
                    <div class="field-card" onclick="addField('phone')"><i class="fas fa-phone"></i><span>هاتف</span></div>
                    <div class="field-card" onclick="addField('date')"><i class="fas fa-calendar"></i><span>تاريخ</span></div>
                    <div class="field-card" onclick="addField('time')"><i class="fas fa-clock"></i><span>وقت</span></div>
                    <div class="field-card" onclick="addField('url')"><i class="fas fa-link"></i><span>رابط</span></div>
                    <div class="field-card" onclick="addField('price')"><i class="fas fa-dollar-sign"></i><span>السعر</span></div>
                </div>
                <div class="field-group">
                    <div class="field-group-title">الاختيارات</div>
                    <div class="field-card" onclick="addField('select')"><i class="fas fa-list"></i><span>قائمة منسدلة</span></div>
                    <div class="field-card" onclick="addField('radio')"><i class="fas fa-dot-circle"></i><span>اختيار واحد</span></div>
                    <div class="field-card" onclick="addField('checkbox')"><i class="fas fa-check-square"></i><span>اختيار متعدد</span></div>
                </div>
                <div class="field-group">
                    <div class="field-group-title">الملفات والوسائط</div>
                    <div class="field-card" onclick="addField('file')"><i class="fas fa-file-upload"></i><span>رفع ملف</span></div>
                    <div class="field-card" onclick="addField('image')"><i class="fas fa-image"></i><span>صورة</span></div>
                    <div class="field-card" onclick="addField('video')"><i class="fas fa-video"></i><span>فيديو</span></div>
                </div>
                <div class="field-group">
                    <div class="field-group-title">متقدم</div>
                    <div class="field-card" onclick="addField('section')"><i class="fas fa-heading"></i><span>عنوان قسم</span></div>
                    <div class="field-card" onclick="addField('rating')"><i class="fas fa-star"></i><span>تقييم</span></div>
                    <div class="field-card" onclick="addField('signature')"><i class="fas fa-signature"></i><span>توقيع</span></div>
                    <div class="field-card" onclick="addField('password')"><i class="fas fa-lock"></i><span>كلمة مرور</span></div>
                    <div class="field-card" onclick="addField('hidden')"><i class="fas fa-eye-slash"></i><span>حقل مخفي</span></div>
                </div>
            </div>
            <div class="workspace">
                <div class="form-preview">
                    <div class="form-header" id="form-header-preview" style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%)">
                        <h1 id="preview-title">{{ $form->title }}</h1>
                        <p id="preview-description">{{ $form->description ?: 'وصف النموذج' }}</p>
                    </div>
                    <div class="form-body" id="fields-container">
                        @forelse($form->fields as $field)
                            <div class="builder-field" data-field-id="{{ $field->id }}" data-field-type="{{ $field->field_type }}">
                                <div class="drag-handle"><i class="fas fa-grip-vertical"></i></div>
                                <div class="field-actions">
                                    <button class="edit"><i class="fas fa-pen"></i></button>
                                    <button class="del-btn"><i class="fas fa-trash"></i></button>
                                </div>
                                <label>{{ $field->label }} @if($field->required)<span class="required">*</span>@endif</label>
                                @if($field->field_type=='text')<div class="field-input">{{ $field->placeholder ?: 'أدخل النص هنا' }}</div>
                                @elseif($field->field_type=='textarea')<div class="field-input" style="min-height:70px">{{ $field->placeholder ?: 'أدخل النص الطويل هنا' }}</div>
                                @elseif($field->field_type=='email')<div class="field-input">{{ $field->placeholder ?: 'example@email.com' }}</div>
                                @elseif($field->field_type=='number')<div class="field-input">{{ $field->placeholder ?: '0' }}</div>
                                @elseif($field->field_type=='phone')<div class="field-input">{{ $field->placeholder ?: '05xxxxxxxx' }}</div>
                                @elseif($field->field_type=='date')<div class="field-input">YYYY-MM-DD</div>
                                @elseif($field->field_type=='time')<div class="field-input">HH:MM</div>
                                @elseif($field->field_type=='select')<div class="field-input">اختر من القائمة...</div>
                                @elseif($field->field_type=='url')<div class="field-input">https://example.com</div>
                                @elseif($field->field_type=='radio')
                                    <div style="display:flex;flex-direction:column;gap:6px">
                                        @if($field->options)@foreach($field->options as $option)<label style="display:flex;align-items:center;gap:8px;pointer-events:none"><input type="radio" disabled style="width:16px;height:16px"><span style="color:#374151;font-size:14px">{{ $option }}</span></label>@endforeach
                                        @else<span style="color:#9ca3af;font-size:14px">لا توجد خيارات بعد</span>@endif
                                    </div>
                                @elseif($field->field_type=='checkbox')
                                    <div style="display:flex;flex-direction:column;gap:6px">
                                        @if($field->options)@foreach($field->options as $option)<label style="display:flex;align-items:center;gap:8px;pointer-events:none"><input type="checkbox" disabled style="width:16px;height:16px"><span style="color:#374151;font-size:14px">{{ $option }}</span></label>@endforeach
                                        @else<span style="color:#9ca3af;font-size:14px">لا توجد خيارات بعد</span>@endif
                                    </div>
                                @elseif($field->field_type=='file')<div class="field-input" style="text-align:center;padding:24px"><i class="fas fa-cloud-upload-alt" style="font-size:22px;color:#9ca3af;display:block;margin-bottom:6px"></i>اسحب الملف هنا أو انقر للاختيار</div>
                                @elseif($field->field_type=='image')<div class="field-input" style="text-align:center;padding:24px"><i class="fas fa-image" style="font-size:22px;color:#9ca3af;display:block;margin-bottom:6px"></i>رفع صورة</div>
                                @elseif($field->field_type=='video')<div class="field-input" style="text-align:center;padding:24px"><i class="fas fa-video" style="font-size:22px;color:#9ca3af;display:block;margin-bottom:6px"></i>رفع فيديو</div>
                                @elseif($field->field_type=='section')<h3 style="font-size:18px;font-weight:700;color:#1f2937;border-bottom:2px solid #e5e7eb;padding-bottom:8px">{{ $field->label }}</h3>
                                @elseif($field->field_type=='rating')<div style="display:flex;gap:6px">@for($i=1;$i<=5;$i++)<i class="fas fa-star" style="font-size:24px;color:#e5e7eb"></i>@endfor</div>
                                @elseif($field->field_type=='signature')<div class="field-input" style="text-align:center;padding:24px;border-style:dashed"><i class="fas fa-signature" style="font-size:22px;color:#9ca3af;display:block;margin-bottom:6px"></i>منطقة التوقيع</div>
                                @elseif($field->field_type=='password')<div class="field-input">••••••••</div>
                                @elseif($field->field_type=='price')<div class="field-input">0.00 ر.س</div>
                                @elseif($field->field_type=='hidden')<div style="font-size:12px;color:#9ca3af;padding:8px;background:#f3f4f6;border-radius:6px"><i class="fas fa-eye-slash"></i> حقل مخفي - القيمة: {{ $field->default_value ?: 'فارغة' }}</div>
                                @endif
                                @if($field->help_text)<p class="help-text">{{ $field->help_text }}</p>@endif
                            </div>
                        @empty
                            <div class="empty-state" id="empty-state"><i class="fas fa-mouse-pointer"></i><p>انقر على عنصر من القائمة الجانبية لإضافته</p></div>
                        @endforelse
                    </div>
                    <div style="padding:0 40px 30px"><button style="width:100%;padding:14px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;border:none;border-radius:12px;font-size:15px;font-weight:700;cursor:default">إرسال <i class="fas fa-paper-plane" style="margin-right:8px"></i></button></div>
                </div>
            </div>
            <div class="sidebar-right">
                <div class="settings-header"><h3>إعدادات الحقل</h3></div>
                <div class="settings-tabs">
                    <div class="settings-tab active" onclick="switchSettingsTab(this,'general')">عام</div>
                    <div class="settings-tab" onclick="switchSettingsTab(this,'options')">خيارات</div>
                    <div class="settings-tab" onclick="switchSettingsTab(this,'advanced')">متقدم</div>
                </div>
                <div class="settings-content" id="settings-general">
                    <div class="no-selection" id="no-selection"><i class="fas fa-mouse-pointer"></i><p>اختر حقلاً من النموذج لتعديل إعداداته</p></div>
                    <div id="field-settings-general" style="display:none">
                        <div class="setting-row"><label>عنوان الحقل</label><input type="text" id="setting-label" placeholder="أدخل عنوان الحقل" oninput="updateFieldSetting('label',this.value)"></div>
                        <div class="setting-row"><label>النص التوضيحي (Placeholder)</label><input type="text" id="setting-placeholder" placeholder="نص يساعد المستخدم" oninput="updateFieldSetting('placeholder',this.value)"></div>
                        <div class="setting-row"><label>نص المساعدة</label><textarea id="setting-help" placeholder="وصف إضافي يظهر تحت الحقل..." oninput="updateFieldSetting('help_text',this.value)"></textarea></div>
                        <div class="setting-row"><div class="checkbox-wrapper"><input type="checkbox" id="setting-required" onchange="updateFieldSetting('required',this.checked)"><label for="setting-required">حقل إلزامي</label></div></div>
                    </div>
                </div>
                <div class="settings-content hidden" id="settings-options">
                    <div class="no-selection"><i class="fas fa-list-ul"></i><p>الخيارات متاحة لحقول: قائمة منسدلة، اختيار واحد، اختيار متعدد</p></div>
                    <div id="field-settings-options" style="display:none"><div class="setting-row"><label>الخيارات (سطر لكل خيار)</label><textarea id="setting-options" rows="6" placeholder="الخيار الأول&#10;الخيار الثاني&#10;الخيار الثالث" oninput="updateFieldOptions(this.value)"></textarea></div></div>
                </div>
                <div class="settings-content hidden" id="settings-advanced">
                    <div class="no-selection"><i class="fas fa-sliders-h"></i><p>إعدادات متقدمة للحقل المحدد</p></div>
                    <div id="field-settings-advanced" style="display:none">
                        <div class="setting-row"><label>القيمة الافتراضية</label><input type="text" id="setting-default" placeholder="قيمة افتراضية" oninput="updateFieldSetting('default_value',this.value)"></div>
                        <div class="setting-row"><label>نوع الحقل</label><input type="text" id="setting-type" disabled style="background:#f3f4f6;color:#9ca3af"></div>
                        <div class="setting-row"><div class="checkbox-wrapper"><input type="checkbox" id="setting-unique" onchange="updateFieldSetting('settings',this.checked ? {unique:true} : {unique:false})"><label for="setting-unique">قيمة فريدة (لا تكرار)</label></div></div>
                        <div class="setting-row"><button onclick="deleteSelectedField()" style="width:100%;padding:10px;background:#fee2e2;color:#dc2626;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;transition:all .2s" onmouseover="this.style.background='#fecaca'" onmouseout="this.style.background='#fee2e2'"><i class="fas fa-trash"></i> حذف هذا الحقل</button></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- SETTINGS TAB -->
        <div class="tab-content" id="tab-settings">
            <div class="settings-tab-content">
                <div class="settings-card">
                    <div class="settings-card-header"><i class="fas fa-info-circle"></i><h3>المعلومات الأساسية</h3></div>
                    <div class="settings-card-body">
                        <div class="form-group">
                            <label>عنوان النموذج</label>
                            <input type="text" id="settings-form-title" value="{{ $form->title }}" oninput="updatePreviewTitle(this.value)">
                        </div>
                        <div class="form-group">
                            <label>وصف النموذج</label>
                            <textarea id="settings-form-description" rows="3" oninput="updatePreviewDescription(this.value)">{{ $form->description }}</textarea>
                            <p class="hint">يظهر تحت عنوان النموذج في صفحة العرض</p>
                        </div>
                        <div class="form-group">
                            <label>حالة النموذج</label>
                            <select id="settings-form-status">
                                <option value="active" {{ $form->status == 'active' ? 'selected' : '' }}>نشط - متاح للإرسال</option>
                                <option value="inactive" {{ $form->status == 'inactive' ? 'selected' : '' }}>غير نشط - متوقف مؤقتاً</option>
                                <option value="archived" {{ $form->status == 'archived' ? 'selected' : '' }}>مؤرشف</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="settings-card">
                    <div class="settings-card-header"><i class="fas fa-shield-alt"></i><h3>الأمان والخصوصية</h3></div>
                    <div class="settings-card-body">
                        <div class="toggle-row">
                            <div class="toggle-info">
                                <i class="fas fa-user-lock"></i>
                                <div><h4>تطلب تسجيل الدخول</h4><p>فقط المستخدمون المسجلون يمكنهم الإرسال</p></div>
                            </div>
                            <label class="toggle-switch"><input type="checkbox" id="settings-require-login" {{ $form->require_login ? 'checked' : '' }} onchange="saveFormSetting('require_login', this.checked ? 1 : 0)"><span class="toggle-slider"></span></label>
                        </div>
                        <div class="toggle-row">
                            <div class="toggle-info">
                                <i class="fas fa-robot"></i>
                                <div><h4>تفعيل CAPTCHA</h4><p>حماية إضافية ضد الإرسال الآلي</p></div>
                            </div>
                            <label class="toggle-switch"><input type="checkbox" id="settings-enable-captcha" {{ $form->enable_captcha ? 'checked' : '' }} onchange="saveFormSetting('enable_captcha', this.checked ? 1 : 0)"><span class="toggle-slider"></span></label>
                        </div>
                    </div>
                </div>
                <div class="settings-card">
                    <div class="settings-card-header"><i class="fas fa-link"></i><h3>رابط النموذج</h3></div>
                    <div class="settings-card-body">
                        <div class="form-group">
                            <label>رابط المشاركة</label>
                            <div style="display:flex;gap:8px">
                                <input type="text" id="form-url" value="{{ url('/f/' . $form->slug) }}" readonly style="background:#f9fafb">
                                <button onclick="copyLink()" class="btn btn-primary" style="flex-shrink:0"><i class="fas fa-copy"></i> نسخ</button>
                            </div>
                            <p class="hint">شارك هذا الرابط مع المستخدمين لملء النموذج</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- CUSTOMIZE TAB -->
        <div class="tab-content" id="tab-customize">
            <div class="settings-tab-content">
                <div class="settings-card">
                    <div class="settings-card-header"><i class="fas fa-palette"></i><h3>مظهر النموذج</h3></div>
                    <div class="settings-card-body">
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:10px">اختر لوناً لعنوان النموذج</label>
                        <div class="theme-grid" id="theme-grid">
                            <div class="theme-card selected" onclick="selectTheme(this,'linear-gradient(135deg,#667eea 0%,#764ba2 100%)')">
                                <div class="theme-preview" style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%)">بنفسجي</div>
                            </div>
                            <div class="theme-card" onclick="selectTheme(this,'linear-gradient(135deg,#06beb6 0%,#48b1bf 100%)')">
                                <div class="theme-preview" style="background:linear-gradient(135deg,#06beb6 0%,#48b1bf 100%)">فيروزي</div>
                            </div>
                            <div class="theme-card" onclick="selectTheme(this,'linear-gradient(135deg,#f093fb 0%,#f5576c 100%)')">
                                <div class="theme-preview" style="background:linear-gradient(135deg,#f093fb 0%,#f5576c 100%)">وردي</div>
                            </div>
                            <div class="theme-card" onclick="selectTheme(this,'linear-gradient(135deg,#4facfe 0%,#00f2fe 100%)')">
                                <div class="theme-preview" style="background:linear-gradient(135deg,#4facfe 0%,#00f2fe 100%)">أزرق</div>
                            </div>
                            <div class="theme-card" onclick="selectTheme(this,'linear-gradient(135deg,#43e97b 0%,#38f9d7 100%)')">
                                <div class="theme-preview" style="background:linear-gradient(135deg,#43e97b 0%,#38f9d7 100%)">أخضر</div>
                            </div>
                            <div class="theme-card" onclick="selectTheme(this,'linear-gradient(135deg,#fa709a 0%,#fee140 100%)')">
                                <div class="theme-preview" style="background:linear-gradient(135deg,#fa709a 0%,#fee140 100%)">غروب</div>
                            </div>
                            <div class="theme-card" onclick="selectTheme(this,'linear-gradient(135deg,#30cfd0 0%,#330867 100%)')">
                                <div class="theme-preview" style="background:linear-gradient(135deg,#30cfd0 0%,#330867 100%)">ليلي</div>
                            </div>
                            <div class="theme-card" onclick="selectTheme(this,'linear-gradient(135deg,#1f2937 0%,#374151 100%)')">
                                <div class="theme-preview" style="background:linear-gradient(135deg,#1f2937 0%,#374151 100%)">داكن</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="settings-card">
                    <div class="settings-card-header"><i class="fas fa-comment-dots"></i><h3>رسالة الشكر</h3></div>
                    <div class="settings-card-body">
                        <div class="form-group">
                            <label>رسالة تظهر بعد الإرسال</label>
                            <textarea id="settings-thank-you" rows="3" placeholder="شكراً لك! تم إرسال نموذجك بنجاح." onchange="saveFormSetting('thank_you_message', this.value)">{{ $form->thank_you_message }}</textarea>
                            <p class="hint">سيظهر للمستخدم بعد إرسال النموذج بنجاح</p>
                        </div>
                    </div>
                </div>
                <div class="settings-card">
                    <div class="settings-card-header"><i class="fas fa-webhook"></i><h3>Webhook</h3></div>
                    <div class="settings-card-body">
                        <div class="form-group">
                            <label>رابط Webhook (اختياري)</label>
                            <input type="url" id="settings-webhook" placeholder="https://example.com/webhook" value="{{ $form->webhook_url ?? '' }}" onchange="saveFormSetting('webhook_url', this.value)">
                            <p class="hint">سيتم إرسال بيانات الردود إلى هذا الرابط عند كل إرسال جديد</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- STATS TAB -->
        <div class="tab-content" id="tab-stats">
            <div class="settings-tab-content">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon" style="background:#dbeafe;color:#2563eb"><i class="fas fa-inbox"></i></div>
                        <div class="stat-value">{{ $form->submissions->count() }}</div>
                        <div class="stat-label">إجمالي الردود</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background:#dcfce7;color:#16a34a"><i class="fas fa-list-check"></i></div>
                        <div class="stat-value">{{ $form->fields->count() }}</div>
                        <div class="stat-label">عدد الحقول</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background:#fef3c7;color:#d97706"><i class="fas fa-clock"></i></div>
                        <div class="stat-value" style="font-size:16px">{{ $form->created_at->diffForHumans() }}</div>
                        <div class="stat-label">تاريخ الإنشاء</div>
                    </div>
                </div>
                <div class="settings-card">
                    <div class="settings-card-header"><i class="fas fa-chart-line"></i><h3>آخر الردود</h3></div>
                    <div class="settings-card-body">
                        @php $recentSubmissions = $form->submissions()->latest()->take(5)->get(); @endphp
                        @if($recentSubmissions->count() > 0)
                            <div style="display:flex;flex-direction:column;gap:10px">
                                @foreach($recentSubmissions as $submission)
                                    <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;background:#f9fafb;border-radius:10px">
                                        <div style="display:flex;align-items:center;gap:10px">
                                            <div style="width:36px;height:36px;border-radius:50%;background:#e0e7ff;color:#1f4277;display:flex;align-items:center;justify-content:center;font-size:14px"><i class="fas fa-user"></i></div>
                                            <div>
                                                <div style="font-size:14px;font-weight:600;color:#1f2937">{{ $submission->metadata['submitted_by'] ?? 'زائر' }}</div>
                                                <div style="font-size:12px;color:#9ca3af">{{ $submission->submitted_at->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                        <span style="padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;@if($submission->status=='pending')background:#fef3c7;color:#d97706@elseif($submission->status=='approved')background:#dcfce7;color:#16a34a@elseif($submission->status=='rejected')background:#fee2e2;color:#dc2626@else background:#f3f4f6;color:#6b7280 @endif">{{ $submission->status == 'pending' ? 'بانتظار' : ($submission->status == 'approved' ? 'موافق' : ($submission->status == 'rejected' ? 'مرفوض' : $submission->status)) }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <div style="text-align:center;margin-top:16px">
                                <a href="{{ route('submissions.index', $form) }}" class="btn btn-secondary"><i class="fas fa-list"></i> عرض كل الردود</a>
                            </div>
                        @else
                            <div class="empty-state"><i class="fas fa-inbox"></i><p>لا توجد ردود بعد</p></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="toast" id="toast"></div>
    <script>
    const formId = {{ $form->id }};
    const csrfToken = '{{ csrf_token() }}';
    let selectedFieldId = null;
    let fieldCounter = {{ $form->fields->count() }};
    let fieldDataStore = {};
    let currentTheme = 'linear-gradient(135deg,#667eea 0%,#764ba2 100%)';

    @foreach($form->fields as $field)
    fieldDataStore[{{ $field->id }}] = {
        id: {{ $field->id }},
        label: {{ Illuminate\Support\Js::from($field->label) }},
        placeholder: {{ Illuminate\Support\Js::from($field->placeholder ?? '') }},
        help_text: {{ Illuminate\Support\Js::from($field->help_text ?? '') }},
        required: {{ $field->required ? 'true' : 'false' }},
        default_value: {{ Illuminate\Support\Js::from($field->default_value ?? '') }},
        field_type: {{ Illuminate\Support\Js::from($field->field_type) }},
        options: @json($field->options),
        settings: @json($field->settings)
    };
    @endforeach

    // Attach click handlers to all server-rendered fields
    document.querySelectorAll('.builder-field').forEach(function(el) {
        el.addEventListener('click', function(e) {
            if (e.target.closest('.del-btn')) {
                e.stopPropagation();
                deleteField(el.dataset.fieldId);
            } else {
                e.stopPropagation();
                selectField(el.dataset.fieldId);
            }
        });
    });

    function showToast(msg, type) {
        const t = document.getElementById('toast');
        t.textContent = '';
        const icon = document.createElement('i');
        icon.className = type === 'success' ? 'fas fa-check-circle' : type === 'error' ? 'fas fa-exclamation-circle' : 'fas fa-info-circle';
        t.appendChild(icon);
        t.appendChild(document.createTextNode(msg));
        t.className = 'toast ' + (type || '') + ' show';
        setTimeout(() => t.classList.remove('show'), 3000);
    }

    function switchMainTab(btn, tab) {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        document.getElementById('tab-' + tab).classList.add('active');
    }

    function switchSettingsTab(btn, tab) {
        document.querySelectorAll('.settings-tab').forEach(t => t.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('.settings-content').forEach(c => c.classList.add('hidden'));
        document.getElementById('settings-' + tab).classList.remove('hidden');
    }

    const fieldLabels = {
        text:'نص قصير',textarea:'نص طويل',email:'بريد إلكتروني',number:'رقم',phone:'هاتف',
        date:'تاريخ',time:'وقت',select:'قائمة منسدلة',radio:'اختيار واحد',checkbox:'اختيار متعدد',
        file:'رفع ملف',url:'رابط',section:'عنوان قسم',rating:'تقييم',signature:'توقيع',
        password:'كلمة مرور',hidden:'حقل مخفي',image:'صورة',video:'فيديو',price:'السعر'
    };

    const fieldPlaceholders = {
        text:'أدخل النص هنا',textarea:'أدخل النص الطويل هنا',email:'example@email.com',
        number:'0',phone:'05xxxxxxxx',date:'YYYY-MM-DD',time:'HH:MM',
        select:'اختر من القائمة...',url:'https://example.com',password:'••••••••',price:'0.00'
    };

    function getFieldPreviewHTML(type) {
        if (['text','email','number','phone','date','time','url','password'].includes(type))
            return '<div class="field-input">' + (fieldPlaceholders[type] || '') + '</div>';
        if (type === 'textarea') return '<div class="field-input" style="min-height:70px">أدخل النص الطويل هنا</div>';
        if (type === 'select') return '<div class="field-input">اختر من القائمة...</div>';
        if (type === 'radio' || type === 'checkbox') return '<div style="display:flex;flex-direction:column;gap:6px"><span style="color:#9ca3af;font-size:14px">أضف خيارات من تبويب "خيارات"</span></div>';
        if (type === 'file') return '<div class="field-input" style="text-align:center;padding:24px"><i class="fas fa-cloud-upload-alt" style="font-size:22px;color:#9ca3af;display:block;margin-bottom:6px"></i>اسحب الملف هنا</div>';
        if (type === 'image') return '<div class="field-input" style="text-align:center;padding:24px"><i class="fas fa-image" style="font-size:22px;color:#9ca3af;display:block;margin-bottom:6px"></i>رفع صورة</div>';
        if (type === 'video') return '<div class="field-input" style="text-align:center;padding:24px"><i class="fas fa-video" style="font-size:22px;color:#9ca3af;display:block;margin-bottom:6px"></i>رفع فيديو</div>';
        if (type === 'section') return '<h3 style="font-size:18px;font-weight:700;color:#1f2937;border-bottom:2px solid #e5e7eb;padding-bottom:8px">عنوان القسم</h3>';
        if (type === 'rating') return '<div style="display:flex;gap:6px"><i class="fas fa-star" style="font-size:24px;color:#e5e7eb"></i><i class="fas fa-star" style="font-size:24px;color:#e5e7eb"></i><i class="fas fa-star" style="font-size:24px;color:#e5e7eb"></i><i class="fas fa-star" style="font-size:24px;color:#e5e7eb"></i><i class="fas fa-star" style="font-size:24px;color:#e5e7eb"></i></div>';
        if (type === 'signature') return '<div class="field-input" style="text-align:center;padding:24px;border-style:dashed"><i class="fas fa-signature" style="font-size:22px;color:#9ca3af;display:block;margin-bottom:6px"></i>منطقة التوقيع</div>';
        if (type === 'hidden') return '<div style="font-size:12px;color:#9ca3af;padding:8px;background:#f3f4f6;border-radius:6px"><i class="fas fa-eye-slash"></i> حقل مخفي</div>';
        if (type === 'price') return '<div class="field-input">0.00 ر.س</div>';
        return '<div class="field-input">معاينة</div>';
    }

    function addField(type) {
        const container = document.getElementById('fields-container');
        const empty = document.getElementById('empty-state');
        if (empty) empty.remove();

        const tempId = 'new_' + Date.now();
        const label = fieldLabels[type] || type;
        const div = document.createElement('div');
        div.className = 'builder-field active';
        div.dataset.fieldId = tempId;
        div.dataset.fieldType = type;
        div.innerHTML = '<div class="drag-handle"><i class="fas fa-grip-vertical"></i></div>' +
            '<div class="field-actions"><button class="edit"><i class="fas fa-pen"></i></button>' +
            '<button class="del-btn"><i class="fas fa-trash"></i></button></div>' +
            '<label>' + label + '</label>' + getFieldPreviewHTML(type);

        div.addEventListener('click', function(e) {
            if (e.target.closest('.del-btn')) {
                e.stopPropagation();
                deleteField(div.dataset.fieldId);
            } else if (e.target.closest('.edit') || true) {
                e.stopPropagation();
                selectField(div.dataset.fieldId);
            }
        });

        container.appendChild(div);
        fieldDataStore[tempId] = { id: tempId, label, placeholder:'', help_text:'', required:false, default_value:'', field_type:type, options:null, settings:{ multi_file: true } };
        selectField(tempId);
        saveNewField(type, tempId, label, div);
    }

    function selectField(fieldId) {
        selectedFieldId = fieldId;
        document.querySelectorAll('.builder-field').forEach(el => el.classList.remove('active'));
        const field = document.querySelector('[data-field-id="' + fieldId + '"]');
        if (field) field.classList.add('active');

        const data = fieldDataStore[fieldId];
        if (!data) return;

        document.getElementById('no-selection').style.display = 'none';
        document.getElementById('field-settings-general').style.display = 'block';
        document.getElementById('setting-label').value = data.label || '';
        document.getElementById('setting-placeholder').value = data.placeholder || '';
        document.getElementById('setting-help').value = data.help_text || '';
        document.getElementById('setting-required').checked = data.required || false;
        document.getElementById('setting-type').value = fieldLabels[data.field_type] || data.field_type;
        document.getElementById('setting-default').value = data.default_value || '';

        const hasOptions = ['select','radio','checkbox'].includes(data.field_type);
        if (hasOptions) {
            document.getElementById('field-settings-options').style.display = 'block';
            document.getElementById('setting-options').value = (data.options || []).join('\n');
            document.querySelector('#settings-options .no-selection').style.display = 'none';
        } else {
            document.getElementById('field-settings-options').style.display = 'none';
            document.querySelector('#settings-options .no-selection').style.display = 'block';
        }
        document.getElementById('field-settings-advanced').style.display = 'block';
        document.querySelector('#settings-advanced .no-selection').style.display = 'none';
        document.getElementById('setting-unique').checked = data.settings && data.settings.unique || false;
    }

    function updateFieldSetting(prop, value) {
        if (!selectedFieldId) return;
        const data = fieldDataStore[selectedFieldId];
        if (!data) return;

        if (prop === 'settings') {
            data.settings = data.settings || {};
            Object.assign(data.settings, value);
        } else {
            data[prop] = value;
        }

        const fieldEl = document.querySelector('[data-field-id="' + selectedFieldId + '"]');
        if (fieldEl) {
            if (prop === 'label') {
                const labelEl = fieldEl.querySelector('label');
                if (labelEl) labelEl.innerHTML = value + (data.required ? '<span class="required">*</span>' : '');
            }
            if (prop === 'placeholder') {
                const inputEl = fieldEl.querySelector('.field-input');
                if (inputEl && !['radio','checkbox','file','image','video','section','rating','signature','hidden'].includes(data.field_type))
                    inputEl.textContent = value || (fieldPlaceholders[data.field_type] || '');
            }
            if (prop === 'help_text') {
                let helpEl = fieldEl.querySelector('.help-text');
                if (value) {
                    if (!helpEl) { helpEl = document.createElement('p'); helpEl.className = 'help-text'; fieldEl.appendChild(helpEl); }
                    helpEl.textContent = value;
                } else if (helpEl) helpEl.remove();
            }
            if (prop === 'required') {
                const labelEl = fieldEl.querySelector('label');
                if (labelEl) {
                    const req = labelEl.querySelector('.required');
                    if (value && !req) labelEl.innerHTML += '<span class="required">*</span>';
                    if (!value && req) req.remove();
                }
            }
        }

        if (!selectedFieldId.toString().startsWith('new_')) {
            const body = {};
            if (prop === 'settings') {
                body.settings = data.settings;
            } else {
                body[prop] = value;
            }
            fetch('/forms/' + formId + '/fields/' + selectedFieldId, {
                method: 'PUT', headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify(body)
            }).catch(err => showToast('خطأ في الحفظ', 'error'));
        }
    }

    function updateFieldOptions(value) {
        if (!selectedFieldId) return;
        const data = fieldDataStore[selectedFieldId];
        if (!data) return;
        const options = value.split('\n').filter(v => v.trim());
        data.options = options;

        const fieldEl = document.querySelector('[data-field-id="' + selectedFieldId + '"]');
        if (fieldEl && ['radio','checkbox'].includes(data.field_type)) {
            const oldList = fieldEl.querySelector('div[style*="flex-direction:column"]');
            if (oldList) {
                oldList.innerHTML = options.length > 0 ? options.map(opt =>
                    '<label style="display:flex;align-items:center;gap:8px;pointer-events:none"><input type="' + data.field_type + '" disabled style="width:16px;height:16px"><span style="color:#374151;font-size:14px">' + opt + '</span></label>'
                ).join('') : '<span style="color:#9ca3af;font-size:14px">لا توجد خيارات بعد</span>';
            }
        }

        if (!selectedFieldId.toString().startsWith('new_')) {
            fetch('/forms/' + formId + '/fields/' + selectedFieldId, {
                method: 'PUT', headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ options: options })
            }).catch(err => showToast('خطأ في حفظ الخيارات', 'error'));
        }
    }

    function saveNewField(type, tempId, label, divEl) {
        const settings = { multi_file: true };
        fetch('/forms/' + formId + '/fields', {
            method: 'POST', headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ field_type: type, label: label, order: fieldCounter++, settings: settings })
        })
        .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
        .then(data => {
            if (divEl) divEl.dataset.fieldId = data.id;
            fieldDataStore[data.id] = fieldDataStore[tempId];
            fieldDataStore[data.id].id = data.id;
            delete fieldDataStore[tempId];
            if (selectedFieldId === tempId) selectedFieldId = data.id;
            showToast('تم إضافة الحقل', 'success');
        })
        .catch(err => { console.error(err); showToast('خطأ في إضافة الحقل: ' + err.message, 'error'); });
    }

    function deleteField(fieldId) {
        if (!confirm('هل أنت متأكد من حذف هذا الحقل؟')) return;
        const field = document.querySelector('[data-field-id="' + fieldId + '"]');
        if (field) field.remove();
        if (selectedFieldId == fieldId) {
            selectedFieldId = null;
            document.getElementById('no-selection').style.display = 'block';
            document.getElementById('field-settings-general').style.display = 'none';
            document.getElementById('field-settings-options').style.display = 'none';
            document.getElementById('field-settings-advanced').style.display = 'none';
        }
        if (!fieldId.toString().startsWith('new_')) {
            fetch('/forms/' + formId + '/fields/' + fieldId, {
                method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken }
            }).catch(err => showToast('خطأ في حذف الحقل', 'error'));
        }
        delete fieldDataStore[fieldId];
        showToast('تم حذف الحقل', 'success');
    }

    function deleteSelectedField() {
        if (selectedFieldId) deleteField(selectedFieldId);
    }

    function updatePreviewTitle(val) { document.getElementById('preview-title').textContent = val || 'عنوان النموذج'; }
    function updatePreviewDescription(val) { document.getElementById('preview-description').textContent = val || 'وصف النموذج'; }

    function saveFormSetting(prop, value) {
        const body = {}; body[prop] = value; body.status = document.getElementById('settings-form-status')?.value || 'active';
        fetch('/forms/' + formId, {
            method: 'PUT', headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify(body)
        }).then(() => showToast('تم الحفظ', 'success'))
          .catch(() => showToast('خطأ في الحفظ', 'error'));
    }

    function saveAll() {
        const title = document.getElementById('settings-form-title')?.value || document.getElementById('preview-title').textContent;
        const description = document.getElementById('settings-form-description')?.value || '';
        const status = document.getElementById('settings-form-status')?.value || 'active';
        const thankYou = document.getElementById('settings-thank-you')?.value || '';
        const body = { title, description, status, thank_you_message: thankYou };
        if (document.getElementById('settings-require-login')) body.require_login = document.getElementById('settings-require-login').checked ? 1 : 0;
        if (document.getElementById('settings-enable-captcha')) body.enable_captcha = document.getElementById('settings-enable-captcha').checked ? 1 : 0;
        if (document.getElementById('settings-webhook')) body.webhook_url = document.getElementById('settings-webhook').value || null;
        fetch('/forms/' + formId, {
            method: 'PUT', headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify(body)
        }).then(() => showToast('تم حفظ النموذج بنجاح', 'success'))
          .catch(() => showToast('خطأ في الحفظ', 'error'));
    }

    function selectTheme(card, gradient) {
        document.querySelectorAll('.theme-card').forEach(c => c.classList.remove('selected'));
        card.classList.add('selected');
        currentTheme = gradient;
        document.getElementById('form-header-preview').style.background = gradient;
        const settings = { theme: gradient };
        fetch('/forms/' + formId + '/fields', {
            method: 'POST', headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ field_type: 'hidden', label: '_theme', settings: settings, order: -1 })
        }).catch(() => {});
        showToast('تم تطبيق المظهر', 'success');
    }

    function copyLink() {
        const url = document.getElementById('form-url').value;
        navigator.clipboard.writeText(url).then(() => showToast('تم نسخ الرابط', 'success'));
    }

    function previewForm() {
        window.open('{{ route('forms.public', $form->slug) }}', '_blank');
    }

    // Init Sortable for drag-reorder
    const ws = document.getElementById('fields-container');
    if (ws) {
        new Sortable(ws, {
            animation: 150, ghostClass: 'sortable-ghost', dragClass: 'sortable-drag',
            handle: '.drag-handle',
            onEnd: function(evt) {
                const fields = [];
                document.querySelectorAll('.builder-field').forEach((el, i) => {
                    const fid = el.dataset.fieldId;
                    if (!fid.toString().startsWith('new_')) fields.push({ id: parseInt(fid), order: i });
                });
                if (fields.length > 0) {
                    fetch('/forms/' + formId + '/fields/reorder', {
                        method: 'POST', headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: JSON.stringify({ fields })
                    }).catch(err => console.error('Reorder error:', err));
                }
            }
        });
    }
    </script>
</body>
</html>
