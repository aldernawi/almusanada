<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Medical Audit Status Update</title>
    <style>
        body { font-family: 'Tahoma', Arial, sans-serif; background: #f5f7fb; margin: 0; padding: 24px; color: #1f2937; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
        .header { background: linear-gradient(135deg,#3b82f6,#1d4ed8); padding: 28px; color: #fff; text-align: center; }
        .header h1 { margin: 0; font-size: 22px; }
        .body { padding: 28px; line-height: 1.8; font-size: 14px; }
        .info-box { background: #f0f9ff; border-left: 4px solid #3b82f6; padding: 14px 16px; border-radius: 8px; margin: 18px 0; }
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
            <h1>🏥 Medical Audit Status Update</h1>
        </div>
        <div class="body">
            <p>Dear User,</p>
            <p>We would like to inform you that the status of medical audit submission <strong>#{{ $submission->id }}</strong> for form <strong>{{ $form->title }}</strong> has been updated.</p>

            <div class="info-box">
                <p style="margin: 0 0 6px 0;"><strong>Submission ID:</strong> #{{ $submission->id }}</p>
                <p style="margin: 0 0 6px 0;"><strong>Submitted At:</strong> {{ $submission->submitted_at->format('Y-m-d H:i') }}</p>
                <p style="margin: 0;"><strong>New Status:</strong> 
                    <span class="status-badge status-{{ $status }}">
                        @if($status == 'approved') Approved
                        @elseif($status == 'rejected') Rejected
                        @elseif($status == 'incomplete') Incomplete
                        @endif
                    </span>
                </p>
            </div>

            <p><strong>Auditor:</strong> {{ $auditor->name }}</p>
            <p><strong>Review Date:</strong> {{ $submission->reviewed_at->format('Y-m-d H:i') }}</p>

            @if($submission->review_notes)
                <div style="background: #f9fafb; padding: 16px; border-radius: 8px; margin: 18px 0;">
                    <p style="margin: 0 0 8px 0; font-weight: 600;">Audit Notes:</p>
                    <p style="margin: 0;">{{ $submission->review_notes }}</p>
                </div>
            @endif

            @if($status == 'rejected')
                <p style="color: #dc2626; font-weight: 600;">Please review the rejection reasons and submit a new application after meeting the requirements.</p>
            @elseif($status == 'incomplete')
                <p style="color: #d97706; font-weight: 600;">Please complete the missing information and resubmit the application.</p>
            @else
                <p style="color: #166534; font-weight: 600;">The submission has been successfully approved.</p>
            @endif

            <p>Thank you for working with us.</p>
        </div>
        <div class="footer">
            This is an automated message from the Almusanada Medical Auditing System. Please do not reply.
        </div>
    </div>
</body>
</html>
