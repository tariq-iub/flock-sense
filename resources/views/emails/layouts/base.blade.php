@php
    $brand = config('flocksense.brand_name', 'FlockSense');
    $support = config('flocksense.support_email');
    $logo = config('flocksense.logo_url');
@endphp

    <!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $subject ?? $brand }}</title>
</head>
<body style="margin:0;padding:0;background:#f5f7fb;font-family:Arial,Helvetica,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f5f7fb;padding:24px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:14px;overflow:hidden;box-shadow:0 6px 24px rgba(15,23,42,.08);">
                <tr>
                    <td style="padding:22px 24px;background:#0b3b3b;color:#fff;">
                        <div style="display:flex;align-items:center;gap:12px;">
                            @if($logo)
                                <img src="{{ $logo }}" alt="{{ $brand }}" height="28" style="display:block;">
                            @endif
                            <div style="font-size:18px;font-weight:700;letter-spacing:.2px;">{{ $brand }}</div>
                        </div>
                        @if(!empty($preheader))
                            <div style="font-size:0;opacity:0;height:0;overflow:hidden;">{{ $preheader }}</div>
                        @endif
                    </td>
                </tr>

                <tr>
                    <td style="padding:26px 24px;color:#0f172a;">
                        @yield('content')
                    </td>
                </tr>

                <tr>
                    <td style="padding:18px 24px;background:#f8fafc;color:#475569;font-size:12px;">
                        <div style="line-height:1.6;">
                            Need help? Contact us at <a href="mailto:{{ $support }}" style="color:#0b3b3b;text-decoration:none;font-weight:600;">{{ $support }}</a>.
                            <br>
                            © {{ date('Y') }} {{ $brand }}. All rights reserved.
                        </div>
                        @isset($unsubscribeUrl)
                            <div style="margin-top:10px;">
                                <a href="{{ $unsubscribeUrl }}" style="color:#64748b;text-decoration:underline;">Unsubscribe</a>
                            </div>
                        @endisset
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
