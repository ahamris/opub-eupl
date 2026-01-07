<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank you for contacting us</title>
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
        .message {
            background-color: white;
            padding: 20px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 12px;
        }
        .contact-info {
            background-color: white;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
            border-left: 4px solid #1e40af;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0; font-size: 24px;">Thank you for contacting us</h1>
    </div>
    
    <div class="content">
        <p>Dear {{ $submission->full_name }},</p>
        
        <div class="message">
            <p>Thank you for reaching out to us. We have received your message regarding <strong>{{ $submission->subject_label }}</strong> and will get back to you as soon as possible.</p>
            
            <p>We typically respond within 1-2 business days. If your inquiry is urgent, please feel free to contact us directly.</p>
        </div>
        
        @if(get_setting('contact_email') || get_setting('contact_phone'))
        <div class="contact-info">
            <p style="margin: 0 0 10px 0; font-weight: 600; color: #374151;">Our contact information:</p>
            @if(get_setting('contact_email'))
                <p style="margin: 5px 0;">Email: <a href="mailto:{{ get_setting('contact_email') }}" style="color: #1e40af; text-decoration: none;">{{ get_setting('contact_email') }}</a></p>
            @endif
            @if(get_setting('contact_phone'))
                <p style="margin: 5px 0;">Phone: <a href="tel:{{ get_setting('contact_phone') }}" style="color: #1e40af; text-decoration: none;">{{ get_setting('contact_phone') }}</a></p>
            @endif
        </div>
        @endif
        
        <p>Best regards,<br>
        <strong>{{ get_setting('site_title', config('app.name', 'Open Overheid')) }} Team</strong></p>
        
        <div class="footer">
            <p style="margin: 0;">This is an automated confirmation email. Please do not reply to this message.</p>
            <p style="margin: 5px 0 0 0;">Your submission reference: #{{ $submission->id }}</p>
        </div>
    </div>
</body>
</html>
