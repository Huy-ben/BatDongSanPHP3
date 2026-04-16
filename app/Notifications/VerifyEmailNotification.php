<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends VerifyEmail
{
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes((int) Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );

        return (new MailMessage)
            ->subject('Xác minh email cho ' . config('app.name'))
            ->view('emails.auth.action', [
                'appName' => config('app.name', 'BatDongSanPHP3'),
                'badge' => 'Kích hoạt tài khoản',
                'title' => 'Xác minh email của bạn',
                'intro' => 'Cảm ơn bạn đã đăng ký. Vui lòng xác minh địa chỉ email để hoàn tất kích hoạt tài khoản.',
                'supportingText' => 'Nhấn vào nút bên dưới để xác minh email và mở khóa toàn bộ tính năng của tài khoản.',
                'actionText' => 'Xác minh email',
                'actionUrl' => $verificationUrl,
                'closingText' => 'Nếu bạn không tạo tài khoản này, bạn có thể bỏ qua email.',
            ]);
    }
}