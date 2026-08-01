<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تأكيد استلام الطلب</title>
    <style>
        body { font-family: 'Tahoma', Arial, sans-serif; background: #f5f7fb; margin: 0; padding: 24px; color: #1f2937; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
        .header { background: linear-gradient(135deg,#10b981,#059669); padding: 28px; color: #fff; text-align: center; }
        .header h1 { margin: 0; font-size: 22px; }
        .body { padding: 28px; line-height: 1.8; font-size: 14px; }
        .info-box { background: #f0fdf4; border-right: 4px solid #10b981; padding: 14px 16px; border-radius: 8px; margin: 18px 0; }
        .footer { background: #f9fafb; padding: 18px; text-align: center; font-size: 12px; color: #6b7280; }
        .badge { display: inline-block; background: #10b981; color: #fff; padding: 4px 12px; border-radius: 20px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✓ تم استلام طلبك بنجاح</h1>
        </div>
        <div class="body">
            <p>مرحباً،</p>
            <p>نشكرك على تعبئة نموذج <strong>{{ $form->title }}</strong>. لقد تم استلام طلبك بنجاح وهو الآن قيد المراجعة.</p>

            <div class="info-box">
                <p style="margin: 0 0 6px 0;"><strong>رقم الطلب:</strong> #{{ $submission->id }}</p>
                <p style="margin: 0 0 6px 0;"><strong>تاريخ الإرسال:</strong> {{ $submission->submitted_at->format('Y-m-d H:i') }}</p>
                <p style="margin: 0;"><strong>الحالة:</strong> <span class="badge">قيد المراجعة</span></p>
            </div>

            @if($form->thank_you_message)
                <p>{{ $form->thank_you_message }}</p>
            @else
                <p>سيقوم فريقنا بمراجعة طلبك في أقرب وقت ممكن. ستصلك رسالة أخرى عند تحديث حالة الطلب.</p>
            @endif

            <p>شكراً لتعاملكم معنا.</p>
        </div>
        <div class="footer">
            هذه رسالة آلية، يرجى عدم الرد عليها مباشرة.
        </div>
    </div>
</body>
</html>
