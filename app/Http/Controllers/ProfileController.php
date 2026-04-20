<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\ListingPackage;
use App\Models\Order;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Response;

class ProfileController extends Controller
{
    public function show(Request $request): Response
    {
        $user = $request->user();
        $page = $request->query('page', 1);

        $activePackage = ListingPackage::query()
            ->where('user_id', $user->id)
            ->where('status', '1')
            ->whereDate('expiry_date', '>=', now()->toDateString())
            ->orderByDesc('expiry_date')
            ->first(['id', 'package_name', 'package_type', 'expiry_date']);

        $postsData = [
            'data' => [],
            'current_page' => 1,
            'last_page' => 1,
            'total' => 0,
            'per_page' => 12,
        ];

        if ($activePackage) {
            $posts = Post::query()
                ->with('thumbnailImage')
                ->where('seller_id', $user->id)
                ->whereIn('status', Post::STATUSES)
                ->latest()
                ->paginate(12, ['*'], 'page', $page);

            $postsData = [
                'data' => $posts->map(function (Post $post): array {
                    return [
                        'id' => $post->id,
                        'title' => $post->title,
                        'price' => (int) $post->price,
                        'area' => (float) $post->area,
                        'address' => $post->address,
                        'status' => (string) $post->status,
                        'created_at' => $post->created_at?->format('d/m/Y'),
                        'thumbnail' => $post->thumbnailImage?->image_url,
                        'edit_url' => route('post-edit', $post),
                    ];
                })->values()->all(),
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'total' => $posts->total(),
                'per_page' => $posts->perPage(),
            ];
        }

        return inertia('Client/Profile', [
            'profile' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'avatar' => $user->avatar,
            ],
            'hasActivePackage' => (bool) $activePackage,
            'activePackage' => $activePackage,
            'posts' => $postsData,
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        if ($validated['email'] !== $user->email) {
            $user->email_verified_at = null;
        }

        $user->fill($validated);
        $user->save();

        return back();
    }

    public function paymentHistory(Request $request): Response
    {
        $user = $request->user();
        $page = (int) $request->query('page', 1);

        $orders = Order::query()
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10, ['*'], 'page', $page);

        return inertia('Client/PaymentHistory', [
            'orders' => [
                'data' => $orders->map(function (Order $order): array {
                    return [
                        'id' => $order->id,
                        'type' => (string) $order->type,
                        'package_name' => (string) $order->package_name,
                        'amount' => (float) $order->amount,
                        'transaction_code' => (string) $order->transaction_code,
                        'created_at' => $order->created_at?->format('d/m/Y H:i'),
                    ];
                })->values()->all(),
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ],
        ]);
    }

    public function uploadAvatar(Request $request): JsonResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'max:2048'],
        ]);

        $user = $request->user();

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && str_contains($user->avatar, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $user->avatar);
                Storage::disk('public')->delete($oldPath);
            }

            // Store new avatar
            $path = $request->file('avatar')->store('avatars', 'public');
            $url = '/storage/' . $path;

            $user->update(['avatar' => $url]);

            return response()->json([
                'success' => true,
                'avatar' => $url,
                'message' => 'Avatar updated successfully',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No file uploaded',
        ], 400);
    }
}
