<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Thông báo từ ' . ($appName ?? config('app.name')) }}</title>
</head>
<body style="margin:0;padding:0;background:#f4f7fb;font-family:'Segoe UI',Tahoma,Arial,sans-serif;color:#1f2937;">
    @php
        $appName = $appName ?? config('app.name', 'BatDongSanPHP3');
        $logoUrl = 'https://res.cloudinary.com/djbobb5oe/image/upload/v1774891267/BTB_Logo_lxnhfh_ainsl1_py1quw.png';
    @endphp

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#f4f7fb;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width:640px;background:#ffffff;border-radius:18px;overflow:hidden;border:1px solid #e6ebf2;box-shadow:0 18px 40px rgba(15,23,42,0.08);">
                    <tr>
                        <td style="background:linear-gradient(135deg,#ff9c22,#fb923c 55%,#f97316);padding:28px 28px 26px;color:#ffffff;position:relative;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td style="vertical-align:middle;width:64px;">
                                        <img src="{{ $logoUrl }}" alt="{{ $appName }}" width="48" height="48" style="display:block;border-radius:14px;background:rgba(255,255,255,0.18);padding:6px;">
                                    </td>
                                    <td style="padding-left:14px;vertical-align:middle;">
                                        <div style="font-size:18px;font-weight:700;line-height:1.3;">{{ $appName }}</div>
                                        <div style="font-size:13px;opacity:0.92;line-height:1.4;">{{ $badge ?? 'Thông báo tài khoản' }}</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:30px 28px 18px;">
                            <h1 style="margin:0 0 12px;font-size:24px;line-height:1.35;color:#111827;">{{ $title }}</h1>
                            <p style="margin:0 0 14px;font-size:15px;color:#374151;line-height:1.7;">{{ $intro }}</p>
                            @if(!empty($supportingText))
                                <p style="margin:0 0 18px;font-size:14px;color:#6b7280;line-height:1.7;">{{ $supportingText }}</p>
                            @endif

                            <div style="margin:24px 0 22px;">
                                <a href="{{ $actionUrl }}" style="display:inline-block;background:#111827;color:#ffffff;text-decoration:none;font-size:15px;font-weight:700;line-height:1;padding:14px 22px;border-radius:999px;">{{ $actionText }}</a>
                            </div>

                            <div style="border:1px solid #e5e7eb;border-radius:14px;background:#f9fafb;padding:16px 18px;">
                                <div style="font-size:13px;color:#6b7280;margin-bottom:6px;">Lưu ý an toàn</div>
                                <div style="font-size:14px;line-height:1.7;color:#1f2937;">{{ $closingText }}</div>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="border-top:1px solid #eef2f7;padding:14px 28px;background:#fcfdff;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td style="font-size:12px;color:#6b7280;">Được gửi tự động từ {{ $appName }}</td>
                                    <td align="right" style="font-size:12px;color:#9ca3af;">Vui lòng không trả lời email này</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>