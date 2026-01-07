<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #1e40af;
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9fafb;
            padding: 20px;
            border: 1px solid #e5e7eb;
            border-top: none;
        }
        .field {
            margin-bottom: 15px;
        }
        .field-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 5px;
            display: block;
        }
        .field-value {
            color: #6b7280;
            padding: 8px 12px;
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
        }
        .message-box {
            background-color: white;
            padding: 15px;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            white-space: pre-wrap;
            color: #374151;
        }
        .button {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 24px;
            background-color: #1e40af;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
        }
        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0; font-size: 24px;">New Contact Form Submission</h1>
    </div>
    
    <div class="content">
        <p style="margin-top: 0;">A new contact form submission has been received:</p>
        
        <div class="field">
            <span class="field-label">Name:</span>
            <div class="field-value">{{ $submission->full_name }}</div>
        </div>
        
        @if($submission->organisation)
        <div class="field">
            <span class="field-label">Organisation:</span>
            <div class="field-value">{{ $submission->organisation }}</div>
        </div>
        @endif
        
        <div class="field">
            <span class="field-label">Email:</span>
            <div class="field-value">
                <a href="mailto:{{ $submission->email }}" style="color: #1e40af; text-decoration: none;">{{ $submission->email }}</a>
            </div>
        </div>
        
        @if($submission->phone)
        <div class="field">
            <span class="field-label">Phone:</span>
            <div class="field-value">
                <a href="tel:{{ $submission->phone }}" style="color: #1e40af; text-decoration: none;">{{ $submission->phone }}</a>
            </div>
        </div>
        @endif
        
        <div class="field">
            <span class="field-label">Subject:</span>
            <div class="field-value">{{ $submission->subject_label }}</div>
        </div>
        
        <div class="field">
            <span class="field-label">Message:</span>
            <div class="message-box">{{ $submission->message }}</div>
        </div>
        
        <a href="{{ route('admin.contact-submissions.show', $submission) }}" class="button">View in Admin Panel</a>
        
        <div class="footer">
            <p style="margin: 0;">This is an automated notification from {{ get_setting('site_title', config('app.name', 'Open Overheid')) }}.</p>
            <p style="margin: 5px 0 0 0;">Submitted on: {{ $submission->created_at->format('d M Y, H:i') }}</p>
        </div>
    </div>
</body>
</html>
