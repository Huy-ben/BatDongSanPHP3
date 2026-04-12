<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;

class PostController extends Controller
{
    public function sell()
    {
        return $this->listingByType('Bán');
    }

    public function rent()
    {
        return $this->listingByType('Cho thuê');
    }

    private function listingByType(string $type)
    {
        $posts = Post::query()
            ->with([
                'category:id,category_name,parent_id',
                'thumbnailImage:id,product_id,image_url',
                'seller:id,name,phone_number',
            ])
            ->whereRelation('category.parentCategory', 'category_name', $type)
            ->orderByDesc('id')
            ->paginate(12)
            ->through(function (Post $post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'price' => $post->price,
                    'area' => $post->area,
                    'address' => $post->address,
                    'location' => $post->location,
                    'description' => $post->description,
                    'created_at' => $post->created_at,
                    'category_name' => $post->category?->category_name ?? '',
                    'img' => $post->thumbnailImage?->image_url ?? '',
                    'seller_name' => $post->seller?->name ?? '',
                    'seller_phone' => $post->seller?->phone_number ?? '',
                ];
            });
        return response()->json($posts);
    }
}
