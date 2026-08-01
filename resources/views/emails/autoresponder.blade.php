<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Submission Confirmation</title>
    <style>
        body { font-family: 'Tahoma', Arial, sans-serif; background: #f5f7fb; margin: 0; padding: 24px; color: #1f2937; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
        .header { background: linear-gradient(135deg,#10b981,#059669); padding: 28px; color: #fff; text-align: center; }
        .header h1 { margin: 0; font-size: 22px; }
        .body { padding: 28px; line-height: 1.8; font-size: 14px; }
        .info-box { background: #f0fdf4; border-left: 4px solid #10b981; padding: 14px 16px; border-radius: 8px; margin: 18px 0; }
        .footer { background: #f9fafb; padding: 18px; text-align: center; font-size: 12px; color: #6b7280; }
        .badge { display: inline-block; background: #10b981; color: #fff; padding: 4px 12px; border-radius: 20px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✓ Your Submission Was Received Successfully</h1>
        </div>
        <div class="body">
            <p>Hello,</p>
            <p>Thank you for filling out the form <strong>{{ $form->title }}</strong>. Your submission has been received successfully and is now under review.</p>

            <div class="info-box">
                <p style="margin: 0 0 6px 0;"><strong>Submission ID:</strong> #{{ $submission->id }}</p>
                <p style="margin: 0 0 6px 0;"><strong>Submitted At:</strong> {{ $submission->submitted_at->format('Y-m-d H:i') }}</p>
                <p style="margin: 0;"><strong>Status:</strong> <span class="badge">Under Review</span></p>
            </div>

            @if($form->thank_you_message)
                <p>{{ $form->thank_you_message }}</p>
            @else
                <p>Our team will review your submission as soon as possible. You will receive another notification when the status is updated.</p>
            @endif

            <p>Thank you for working with us.</p>
        </div>
        <div class="footer">
            This is an automated message. Please do not reply directly.
        </div>
    </div>
</body>
</html>
