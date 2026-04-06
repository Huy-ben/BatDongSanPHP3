<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ContactFormMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ContactController extends Controller
{
    public function index()
    {
        return inertia('Client/Contact');
    }

    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:2000'],
            'agreed' => ['accepted'],
        ]);

        $mailable = new ContactFormMail($validated);

        if (! empty($validated['email'])) {
            $mailable->replyTo($validated['email'], $validated['name']);
        }

        try {
            Mail::to(config('mail.contact_to_address', config('mail.from.address')))->send($mailable);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'message' => 'Cau hinh mail chua hop le hoac Gmail dang tu choi xac thuc. Hay kiem tra MAIL_USERNAME, MAIL_PASSWORD va MAIL_SCHEME.',
            ], 500);
        }

        return response()->json([
            'message' => 'Yeu cau lien he da duoc gui thanh cong. Chung toi se phan hoi som nhat.',
        ]);
    }
}
