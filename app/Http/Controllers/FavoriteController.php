<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function ids(Request $request): JsonResponse
    {
        $ids = Favorite::query()
            ->where('user_id', $request->user()->id)
            ->orderByDesc('id')
            ->pluck('product_id')
            ->values();

        return response()->json([
            'ids' => $ids,
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $favorites = Favorite::query()
            ->where('user_id', $request->user()->id)
            ->with([
                'post.category:id,category_name,parent_id',
                'post.category.parentCategory:id,category_name',
                'post.thumbnailImage:id,product_id,image_url',
                'post.seller:id,name,phone_number',
            ])
            ->latest('id')
            ->get()
            ->map(function (Favorite $favorite) {
                $post = $favorite->post;

                if (! $post || (string) $post->status !== Post::STATUS_PUBLISHED) {
                    return null;
                }

                return [
                    'id' => $post->id,
                    'favorite_id' => $favorite->id,
                    'title' => $post->title,
                    'price' => $post->price,
                    'area' => $post->area,
                    'address' => $post->address,
                    'description' => $post->description,
                    'created_at' => $post->created_at,
                    'category_name' => $post->category?->category_name ?? '',
                    'listing_type' => $post->category?->parentCategory?->category_name ?? '',
                    'img' => $post->thumbnailImage?->image_url ?? '',
                    'seller_name' => $post->seller?->name ?? '',
                    'seller_phone' => $post->seller?->phone_number ?? '',
                ];
            })
            ->filter()
            ->values();

        return response()->json($favorites);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'post_id' => ['required', 'integer', 'exists:posts,id'],
        ]);

        $post = Post::query()->findOrFail((int) $validated['post_id']);

        if ((string) $post->status !== Post::STATUS_PUBLISHED) {
            return response()->json([
                'message' => 'Tin đăng không khả dụng để lưu.',
            ], 422);
        }

        Favorite::query()->firstOrCreate([
            'user_id' => $request->user()->id,
            'product_id' => $post->id,
        ]);

        return response()->json([
            'message' => 'Đã thêm vào yêu thích.',
        ]);
    }

    public function destroy(Request $request, Post $post): JsonResponse
    {
        Favorite::query()
            ->where('user_id', $request->user()->id)
            ->where('product_id', $post->id)
            ->delete();

        return response()->json([
            'message' => 'Đã xóa khỏi yêu thích.',
        ]);
    }

    public function clear(Request $request): JsonResponse
    {
        Favorite::query()
            ->where('user_id', $request->user()->id)
            ->delete();

        return response()->json([
            'message' => 'Đã xóa toàn bộ danh sách yêu thích.',
        ]);
    }
}
