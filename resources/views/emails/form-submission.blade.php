<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Form Submission</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
            <h1 style="color: #1a73e8; margin-top: 0;">New Form Submission</h1>
            
            <p style="margin-bottom: 20px;">
                A new submission has been received for form: <strong>{{ $form->title }}</strong>
            </p>

            <div style="background: white; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <h3 style="margin-top: 0; color: #333;">Submission Information</h3>
                <p><strong>Submission ID:</strong> #{{ $submission->id }}</p>
                <p><strong>Submitted At:</strong> {{ $submission->submitted_at->format('Y-m-d H:i') }}</p>
                <p><strong>IP Address:</strong> {{ $submission->ip_address }}</p>
                @if($submission->user)
                    <p><strong>User:</strong> {{ $submission->user->name }}</p>
                @endif
            </div>

            <div style="background: white; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <h3 style="margin-top: 0; color: #333;">Submitted Data</h3>
                @foreach($submission->submissionData as $data)
                    <div style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                        <p style="margin: 0 0 5px 0; font-weight: bold;">{{ $data->field->label }}</p>
                        @if($data->field->field_type === 'file' && $data->file_data)
                            <p style="margin: 0;">File: {{ $data->file_data['name'] }}</p>
                        @elseif($data->field->field_type === 'checkbox' && $data->value)
                            <p style="margin: 0;">{{ implode(', ', json_decode($data->value)) }}</p>
                        @else
                            <p style="margin: 0;">{{ $data->value }}</p>
                        @endif
                    </div>
                @endforeach
            </div>

            <p style="margin-bottom: 20px;">
                You can view the full submission details from the admin panel.
            </p>

            <div style="text-align: center;">
                <a href="{{ route('submissions.show', [$form, $submission]) }}" 
                   style="background: #1a73e8; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;">
                    View Submission
                </a>
            </div>
        </div>

        <p style="text-align: center; color: #666; font-size: 12px; margin-top: 20px;">
            This notification was sent automatically from the Almusanada system
        </p>
    </div>
</body>
</html>
