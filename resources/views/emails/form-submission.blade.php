<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رد جديد على النموذج</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
            <h1 style="color: #1a73e8; margin-top: 0;">رد جديد على النموذج</h1>
            
            <p style="margin-bottom: 20px;">
                تم استلام رد جديد على النموذج: <strong>{{ $form->title }}</strong>
            </p>

            <div style="background: white; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <h3 style="margin-top: 0; color: #333;">معلومات الرد</h3>
                <p><strong>رقم الرد:</strong> #{{ $submission->id }}</p>
                <p><strong>تاريخ الإرسال:</strong> {{ $submission->submitted_at->format('Y-m-d H:i') }}</p>
                <p><strong>عنوان IP:</strong> {{ $submission->ip_address }}</p>
                @if($submission->user)
                    <p><strong>المستخدم:</strong> {{ $submission->user->name }}</p>
                @endif
            </div>

            <div style="background: white; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <h3 style="margin-top: 0; color: #333;">البيانات المرسلة</h3>
                @foreach($submission->submissionData as $data)
                    <div style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                        <p style="margin: 0 0 5px 0; font-weight: bold;">{{ $data->field->label }}</p>
                        @if($data->field->field_type === 'file' && $data->file_data)
                            <p style="margin: 0;">ملف: {{ $data->file_data['name'] }}</p>
                        @elseif($data->field->field_type === 'checkbox' && $data->value)
                            <p style="margin: 0;">{{ implode(', ', json_decode($data->value)) }}</p>
                        @else
                            <p style="margin: 0;">{{ $data->value }}</p>
                        @endif
                    </div>
                @endforeach
            </div>

            <p style="margin-bottom: 20px;">
                يمكنك عرض تفاصيل الرد الكاملة من خلال لوحة التحكم.
            </p>

            <div style="text-align: center;">
                <a href="{{ route('submissions.show', [$form, $submission]) }}" 
                   style="background: #1a73e8; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;">
                    عرض الرد
                </a>
            </div>
        </div>

        <p style="text-align: center; color: #666; font-size: 12px; margin-top: 20px;">
            تم إرسال هذا الإشعار تلقائياً من نظام Almusanada
        </p>
    </div>
</body>
</html>
