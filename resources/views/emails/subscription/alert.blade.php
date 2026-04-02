<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nieuwe documenten voor uw attendering</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #1f2937; max-width: 560px; margin: 0 auto; padding: 20px; background: #f9fafb; }
        .card { background: #fff; border-radius: 12px; overflow: hidden; border: 1px solid #e5e7eb; }
        .header { background: #155EEF; padding: 24px 28px; }
        .header h1 { color: #fff; font-size: 18px; margin: 0; font-weight: 600; }
        .header p { color: #B2CCFF; font-size: 13px; margin: 6px 0 0; }
        .body { padding: 28px; }
        .body > p { font-size: 14px; color: #374151; margin: 0 0 16px; }
        .badge { display: inline-block; background: #EFF4FF; color: #155EEF; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; margin-bottom: 16px; }
        .doc { border: 1px solid #e5e7eb; border-radius: 8px; padding: 14px 16px; margin: 8px 0; }
        .doc:hover { border-color: #155EEF; }
        .doc-title { font-size: 14px; font-weight: 600; color: #111827; text-decoration: none; }
        .doc-title:hover { color: #155EEF; }
        .doc-meta { font-size: 12px; color: #6b7280; margin-top: 4px; }
        .doc-desc { font-size: 13px; color: #4b5563; margin-top: 6px; line-height: 1.5; }
        .btn { display: inline-block; background: #155EEF; color: #fff !important; text-decoration: none; padding: 10px 24px; border-radius: 8px; font-size: 13px; font-weight: 600; margin: 16px 0; }
        .footer { padding: 20px 28px; border-top: 1px solid #e5e7eb; }
        .footer p { font-size: 11px; color: #9ca3af; margin: 0 0 4px; }
        .footer a { color: #155EEF; text-decoration: none; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>Nieuwe documenten gevonden</h1>
            <p>oPub Attendering &mdash; {{ $subscription->search_query ?: 'Alle documenten' }}</p>
        </div>
        <div class="body">
            <span class="badge">{{ count($documents) }} {{ count($documents) === 1 ? 'nieuw document' : 'nieuwe documenten' }}</span>

            <p>Er {{ count($documents) === 1 ? 'is' : 'zijn' }} <strong>{{ count($documents) }} {{ count($documents) === 1 ? 'nieuw document' : 'nieuwe documenten' }}</strong> gevonden voor uw attendering.</p>

            @foreach($documents as $doc)
            <div class="doc">
                <a href="{{ config('app.url') }}/open-overheid/documents/{{ $doc['external_id'] }}" class="doc-title">{{ $doc['title'] }}</a>
                <div class="doc-meta">
                    @if(!empty($doc['organisation'])){{ $doc['organisation'] }}@endif
                    @if(!empty($doc['organisation']) && !empty($doc['publication_date'])) &middot; @endif
                    @if(!empty($doc['publication_date'])){{ $doc['publication_date'] }}@endif
                </div>
                @if(!empty($doc['description']))
                <div class="doc-desc">{{ Str::limit($doc['description'], 200) }}</div>
                @endif
            </div>
            @endforeach

            <p style="text-align:center;">
                <a href="{{ config('app.url') }}/zoeken?q={{ urlencode($subscription->search_query ?? '*') }}" class="btn">Alle resultaten bekijken</a>
            </p>
        </div>
        <div class="footer">
            <p>Dit is een automatische melding van uw attendering op <a href="{{ config('app.url') }}">oPub.nl</a>.</p>
            <p>Frequentie: {{ $subscription->frequency_label }} &middot; <a href="{{ $unsubscribeUrl }}">Uitschrijven</a></p>
        </div>
    </div>
</body>
</html>
