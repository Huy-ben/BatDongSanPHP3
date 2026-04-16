<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Đặt lại mật khẩu cho ' . config('app.name'))
            ->view('emails.auth.action', [
                'appName' => config('app.name', 'BatDongSanPHP3'),
                'badge' => 'Khôi phục tài khoản',
                'title' => 'Yêu cầu đặt lại mật khẩu',
                'intro' => 'Chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.',
                'supportingText' => 'Nhấn vào nút bên dưới để tạo mật khẩu mới. Liên kết này sẽ hết hạn sau ' . config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60) . ' phút.',
                'actionText' => 'Đặt lại mật khẩu',
                'actionUrl' => $url,
                'closingText' => 'Nếu bạn không yêu cầu đặt lại mật khẩu, hãy bỏ qua email này và không cần thực hiện thêm thao tác nào.',
            ]);
    }
}