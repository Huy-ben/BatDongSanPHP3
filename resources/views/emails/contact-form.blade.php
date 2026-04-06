<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thong tin lien he moi</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.6;">
    <h2 style="margin-bottom: 16px;">Co yeu cau lien he moi tu website</h2>

    <p><strong>Ho va ten:</strong> {{ $contactData['name'] }}</p>
    <p><strong>So dien thoai:</strong> {{ $contactData['phone'] }}</p>
    <p><strong>Email:</strong> {{ $contactData['email'] ?: 'Khong cung cap' }}</p>
    <p><strong>Chu de:</strong> {{ $contactData['subject'] }}</p>
    <p><strong>Noi dung:</strong></p>
    <p style="white-space: pre-line;">{{ $contactData['message'] }}</p>

    <hr style="margin: 20px 0; border: 0; border-top: 1px solid #e5e7eb;">
    <p style="font-size: 12px; color: #6b7280;">Gui luc: {{ now()->format('d/m/Y H:i:s') }}</p>
</body>
</html>
