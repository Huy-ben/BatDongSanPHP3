<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function data(Request $request)
    {
        if (! $request->bearerToken()) {
            return response()->json([
                'message' => 'Bearer token is required.',
            ], 401);
        }

        $posts = Post::query()
            ->with([
                'category:id,category_name,parent_id',
                'category.parentCategory:id,category_name',
                'thumbnailImage:id,product_id,image_url',
            ])
            ->where('status', Post::STATUS_PUBLISHED)
            ->orderByDesc('id')
            ->limit(24)
            ->get()
            ->map(function (Post $post) {
                return [
                    'id' => $post->id,
                    'slug' => $post->slug,
                    'title' => $post->title,
                    'price' => $post->price,
                    'area' => $post->area,
                    'address' => $post->address,
                    'category_name' => $post->category?->category_name ?? '',
                    'listing_type' => $post->category?->parentCategory?->category_name ?? '',
                    'img' => $post->thumbnailImage?->image_url ?? '',
                ];
            });

        $categories = Category::query()
            ->whereRelation('parentCategory', 'category_name', 'Bán')
            ->whereNotNull('image')
            ->select('id', 'category_name', 'image')
            ->orderBy('id')
            ->get();

        $blogs = Blog::query()
            ->where('status', 'published')
            ->select('id', 'title', 'slug', 'image', 'excerpt', 'created_at')
            ->orderByDesc('id')
            ->limit(6)
            ->get();

        return response()->json([
            'posts' => $posts,
            'categories' => $categories,
            'blogs' => $blogs,
        ]);
    }
}
