<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تحديث حالة المعاملة الطبية</title>
    <style>
        body { font-family: 'Tahoma', Arial, sans-serif; background: #f5f7fb; margin: 0; padding: 24px; color: #1f2937; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
        .header { background: linear-gradient(135deg,#3b82f6,#1d4ed8); padding: 28px; color: #fff; text-align: center; }
        .header h1 { margin: 0; font-size: 22px; }
        .body { padding: 28px; line-height: 1.8; font-size: 14px; }
        .info-box { background: #f0f9ff; border-right: 4px solid #3b82f6; padding: 14px 16px; border-radius: 8px; margin: 18px 0; }
        .status-badge { display: inline-block; padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; }
        .status-approved { background: #dcfce7; color: #166534; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        .status-incomplete { background: #fef9c3; color: #854d0e; }
        .footer { background: #f9fafb; padding: 18px; text-align: center; font-size: 12px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏥 تحديث حالة المعاملة الطبية</h1>
        </div>
        <div class="body">
            <p>السلام عليكم،</p>
            <p>نود إبلاغكم بتحديث حالة المعاملة الطبية رقم <strong>#{{ $submission->id }}</strong> الخاصة بنموذج <strong>{{ $form->title }}</strong>.</p>

            <div class="info-box">
                <p style="margin: 0 0 6px 0;"><strong>رقم المعاملة:</strong> #{{ $submission->id }}</p>
                <p style="margin: 0 0 6px 0;"><strong>تاريخ الإرسال:</strong> {{ $submission->submitted_at->format('Y-m-d H:i') }}</p>
                <p style="margin: 0;"><strong>الحالة الجديدة:</strong> 
                    <span class="status-badge status-{{ $status }}">
                        @if($status == 'approved') تمت الموافقة
                        @elseif($status == 'rejected') تم الرفض
                        @elseif($status == 'incomplete') ناقص المعلومات
                        @endif
                    </span>
                </p>
            </div>

            <p><strong>المدقق:</strong> {{ $auditor->name }}</p>
            <p><strong>تاريخ المراجعة:</strong> {{ $submission->reviewed_at->format('Y-m-d H:i') }}</p>

            @if($submission->review_notes)
                <div style="background: #f9fafb; padding: 16px; border-radius: 8px; margin: 18px 0;">
                    <p style="margin: 0 0 8px 0; font-weight: 600;">ملاحظات التدقيق:</p>
                    <p style="margin: 0;">{{ $submission->review_notes }}</p>
                </div>
            @endif

            @if($status == 'rejected')
                <p style="color: #dc2626; font-weight: 600;">يرجى مراجعة أسباب الرفض وتقديم معاملة جديدة بعد استيفاء المتطلبات.</p>
            @elseif($status == 'incomplete')
                <p style="color: #d97706; font-weight: 600;">يرجى استكمال المعلومات الناقصة وإعادة إرسال المعاملة.</p>
            @else
                <p style="color: #166534; font-weight: 600;">تمت الموافقة على المعاملة بنجاح.</p>
            @endif

            <p>شكراً لتعاملكم معنا.</p>
        </div>
        <div class="footer">
            هذه رسالة آلية من نظام التدقيق الطبي Almusanada. يرجى عدم الرد عليها.
        </div>
    </div>
</body>
</html>
