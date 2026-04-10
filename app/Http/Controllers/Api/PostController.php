<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

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
        $posts = DB::table('posts')
            ->join('categories as child_category', 'child_category.id', '=', 'posts.category_id')
            ->join('categories as parent_category', 'parent_category.id', '=', 'child_category.parent_id')
            ->leftJoin('images as thumbnail', function ($join) {
                $join->on('thumbnail.product_id', '=', 'posts.id')
                    ->where('thumbnail.is_thumbnail', '=', 1);
            })
            ->leftJoin('users', 'users.id', '=', 'posts.seller_id')
            ->where('parent_category.category_name', $type)
            ->select(
                'posts.id',
                'posts.title',
                'posts.price',
                'posts.area',
                'posts.address',
                'posts.location',
                'posts.description',
                'posts.created_at',
                'child_category.category_name as category_name',
                DB::raw('COALESCE(thumbnail.image_url, "") as img'),
                DB::raw('COALESCE(users.name, "") as seller_name'),
                DB::raw('COALESCE(users.phone_number, "") as seller_phone')
            )
            ->orderByDesc('posts.id')
            ->paginate(12);

        return response()->json($posts);
    }
}
