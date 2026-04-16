<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $notifications = Notification::query()
            ->where('user_id', $request->user()->id)
            ->latest('id')
            ->get([
                'id',
                'title',
                'content',
                'is_read',
                'created_at',
            ]);

        return response()->json($notifications);
    }

    public function markAsRead(Request $request, Notification $notification): JsonResponse
    {
        if ((int) $notification->user_id !== (int) $request->user()->id) {
            abort(403);
        }

        if (! $notification->is_read) {
            $notification->update(['is_read' => true]);
        }

        return response()->json([
            'message' => 'Đã đánh dấu đã đọc.',
        ]);
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        Notification::query()
            ->where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'message' => 'Đã đánh dấu tất cả là đã đọc.',
        ]);
    }
}
