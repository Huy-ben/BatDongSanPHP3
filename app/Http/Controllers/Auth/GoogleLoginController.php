<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class GoogleLoginController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'credential' => ['required', 'string'],
        ]);

        $googleClientId = (string) config('services.google.client_id');

        if ($googleClientId === '') {
            throw ValidationException::withMessages([
                'google' => 'Chưa cấu hình GOOGLE_CLIENT_ID trên máy chủ.',
            ]);
        }

        $tokenInfoResponse = Http::get('https://oauth2.googleapis.com/tokeninfo', [
            'id_token' => $validated['credential'],
        ]);

        if (! $tokenInfoResponse->ok()) {
            throw ValidationException::withMessages([
                'google' => 'Không thể xác thực tài khoản Google. Vui lòng thử lại.',
            ]);
        }

        $tokenInfo = $tokenInfoResponse->json();

        if (($tokenInfo['aud'] ?? null) !== $googleClientId) {
            throw ValidationException::withMessages([
                'google' => 'Google Client ID không hợp lệ.',
            ]);
        }

        if (($tokenInfo['email_verified'] ?? 'false') !== 'true') {
            throw ValidationException::withMessages([
                'google' => 'Email Google chưa được xác minh.',
            ]);
        }

        $email = $tokenInfo['email'] ?? null;

        if (! is_string($email) || $email === '') {
            throw ValidationException::withMessages([
                'google' => 'Không lấy được email từ Google.',
            ]);
        }

        $name = $tokenInfo['name'] ?? Str::before($email, '@');

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => (string) $name,
                'password' => Str::random(40),
                'role' => '1',
            ]
        );

        if (! $user->email_verified_at) {
            $user->forceFill([
                'email_verified_at' => now(),
            ])->save();
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->intended(config('fortify.home'));
    }
}
