<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bevestig uw attendering</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #1f2937; max-width: 560px; margin: 0 auto; padding: 20px; background: #f9fafb; }
        .card { background: #fff; border-radius: 12px; overflow: hidden; border: 1px solid #e5e7eb; }
        .header { background: #155EEF; padding: 24px 28px; }
        .header h1 { color: #fff; font-size: 18px; margin: 0; font-weight: 600; }
        .header p { color: #B2CCFF; font-size: 13px; margin: 6px 0 0; }
        .body { padding: 28px; }
        .body p { font-size: 14px; color: #374151; margin: 0 0 16px; }
        .detail { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 14px 16px; margin: 16px 0; }
        .detail-row { display: flex; justify-content: space-between; font-size: 13px; padding: 4px 0; }
        .detail-label { color: #6b7280; }
        .detail-value { color: #111827; font-weight: 500; }
        .btn { display: inline-block; background: #155EEF; color: #fff !important; text-decoration: none; padding: 12px 28px; border-radius: 8px; font-size: 14px; font-weight: 600; margin: 8px 0; }
        .btn:hover { background: #004EEB; }
        .footer { padding: 20px 28px; border-top: 1px solid #e5e7eb; }
        .footer p { font-size: 11px; color: #9ca3af; margin: 0; }
        .footer a { color: #155EEF; text-decoration: none; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>Bevestig uw attendering</h1>
            <p>oPub &mdash; Open source Woo-zoekplatform</p>
        </div>
        <div class="body">
            <p>U heeft een attendering aangemaakt op oPub.nl. Bevestig uw e-mailadres om meldingen te ontvangen bij nieuwe publicaties.</p>

            <div class="detail">
                <div class="detail-row">
                    <span class="detail-label">Zoekterm</span>
                    <span class="detail-value">{{ $subscription->search_query ?: 'Alle documenten' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Frequentie</span>
                    <span class="detail-value">{{ $subscription->frequency_label }}</span>
                </div>
                @if($subscription->formatted_filters !== 'Geen filters')
                <div class="detail-row">
                    <span class="detail-label">Filters</span>
                    <span class="detail-value">{{ $subscription->formatted_filters }}</span>
                </div>
                @endif
            </div>

            <p style="text-align:center; margin: 24px 0;">
                <a href="{{ $verifyUrl }}" class="btn">Attendering bevestigen</a>
            </p>

            <p style="font-size:12px; color:#6b7280;">
                Werkt de knop niet? Kopieer en plak deze link in uw browser:<br>
                <a href="{{ $verifyUrl }}" style="color:#155EEF; word-break:break-all;">{{ $verifyUrl }}</a>
            </p>
        </div>
        <div class="footer">
            <p>U ontvangt deze e-mail omdat er een attendering is aangemaakt met dit adres op <a href="{{ config('app.url') }}">oPub.nl</a>.<br>Heeft u dit niet gedaan? U kunt deze e-mail veilig negeren.</p>
        </div>
    </div>
</body>
</html>
