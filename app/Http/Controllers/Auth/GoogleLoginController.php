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
                'google' => 'Chua cau hinh GOOGLE_CLIENT_ID tren server.',
            ]);
        }

        $tokenInfoResponse = Http::get('https://oauth2.googleapis.com/tokeninfo', [
            'id_token' => $validated['credential'],
        ]);

        if (! $tokenInfoResponse->ok()) {
            throw ValidationException::withMessages([
                'google' => 'Khong the xac thuc tai khoan Google. Vui long thu lai.',
            ]);
        }

        $tokenInfo = $tokenInfoResponse->json();

        if (($tokenInfo['aud'] ?? null) !== $googleClientId) {
            throw ValidationException::withMessages([
                'google' => 'Google Client ID khong hop le.',
            ]);
        }

        if (($tokenInfo['email_verified'] ?? 'false') !== 'true') {
            throw ValidationException::withMessages([
                'google' => 'Email Google chua duoc xac minh.',
            ]);
        }

        $email = $tokenInfo['email'] ?? null;

        if (! is_string($email) || $email === '') {
            throw ValidationException::withMessages([
                'google' => 'Khong lay duoc email tu Google.',
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
