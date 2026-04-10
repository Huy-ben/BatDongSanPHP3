<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function data()
    {
        $posts = DB::table('posts')
            ->leftJoin('images as thumbnail', function ($join) {
                $join->on('thumbnail.product_id', '=', 'posts.id')
                    ->where('thumbnail.is_thumbnail', '=', 1);
            })
            ->join('categories as child_category', 'child_category.id', '=', 'posts.category_id')
            ->leftJoin('categories as parent_category', 'parent_category.id', '=', 'child_category.parent_id')
            ->select(
                'posts.id',
                'posts.title',
                'posts.price',
                'posts.area',
                'posts.address',
                'child_category.category_name',
                DB::raw('COALESCE(parent_category.category_name, "") as listing_type'),
                DB::raw('COALESCE(thumbnail.image_url, "") as img')
            )
            ->orderByDesc('posts.id')
            ->limit(24)
            ->get();

        $categories = DB::table('categories as child_category')
            ->join('categories as parent_category', 'parent_category.id', '=', 'child_category.parent_id')
            ->where('parent_category.category_name', 'Bán')
            ->whereNotNull('child_category.image')
            ->select('child_category.id', 'child_category.category_name', 'child_category.image')
            ->orderBy('child_category.id')
            ->get();

        $blogs = DB::table('blogs')
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
