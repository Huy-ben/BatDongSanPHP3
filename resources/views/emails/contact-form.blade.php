<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin liên hệ mới</title>
</head>
<body style="margin:0;padding:0;background:#f4f7fb;font-family:'Segoe UI',Tahoma,Arial,sans-serif;color:#1f2937;">
    @php
        $appName = config('app.name', 'BatDongSanPHP3');
        $logoUrl = "https://res.cloudinary.com/djbobb5oe/image/upload/v1774891267/BTB_Logo_lxnhfh_ainsl1_py1quw.png";
    @endphp

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#f4f7fb;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width:640px;background:#ffffff;border-radius:16px;overflow:hidden;border:1px solid #e6ebf2;">
                    <tr>
                        <td style="background:linear-gradient(135deg,#f97316,#ef4444);padding:20px 24px;color:#ffffff;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td style="vertical-align:middle;">
                                        <img src="{{ $logoUrl }}" alt="{{ $appName }}" width="44" height="44" style="display:block;border-radius:10px;background:rgba(255,255,255,0.18);padding:6px;">
                                    </td>
                                    <td style="padding-left:12px;vertical-align:middle;">
                                        <div style="font-size:18px;font-weight:700;line-height:1.3;">{{ $appName }}</div>
                                        <div style="font-size:13px;opacity:0.9;line-height:1.4;">Thông báo yêu cầu liên hệ mới</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:24px;">
                            <h1 style="margin:0 0 10px;font-size:22px;line-height:1.35;color:#111827;">Bạn vừa nhận được một yêu cầu liên hệ mới</h1>
                            <p style="margin:0 0 18px;font-size:14px;color:#4b5563;line-height:1.6;">Dưới đây là thông tin chi tiết từ biểu mẫu liên hệ trên website.</p>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border-collapse:separate;border-spacing:0 8px;">
                                <tr>
                                    <td style="width:150px;font-size:13px;color:#6b7280;vertical-align:top;">Họ và tên</td>
                                    <td style="font-size:14px;font-weight:600;color:#111827;">{{ $contactData['name'] }}</td>
                                </tr>
                                <tr>
                                    <td style="width:150px;font-size:13px;color:#6b7280;vertical-align:top;">Số điện thoại</td>
                                    <td style="font-size:14px;font-weight:600;color:#111827;">{{ $contactData['phone'] }}</td>
                                </tr>
                                <tr>
                                    <td style="width:150px;font-size:13px;color:#6b7280;vertical-align:top;">Email</td>
                                    <td style="font-size:14px;font-weight:600;color:#111827;">{{ $contactData['email'] ?: 'Không cung cấp' }}</td>
                                </tr>
                                <tr>
                                    <td style="width:150px;font-size:13px;color:#6b7280;vertical-align:top;">Chủ đề</td>
                                    <td style="font-size:14px;font-weight:600;color:#111827;">{{ $contactData['subject'] }}</td>
                                </tr>
                            </table>

                            <div style="margin-top:18px;border:1px solid #e5e7eb;border-radius:12px;background:#f9fafb;padding:14px 16px;">
                                <div style="font-size:13px;color:#6b7280;margin-bottom:6px;">Nội dung</div>
                                <div style="font-size:14px;line-height:1.65;color:#1f2937;white-space:pre-line;">{{ $contactData['message'] }}</div>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="border-top:1px solid #eef2f7;padding:14px 24px;background:#fcfdff;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td style="font-size:12px;color:#6b7280;">Gửi lúc: {{ now()->format('d/m/Y H:i:s') }}</td>
                                    <td align="right" style="font-size:12px;color:#9ca3af;">Tự động từ biểu mẫu liên hệ</td>
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
