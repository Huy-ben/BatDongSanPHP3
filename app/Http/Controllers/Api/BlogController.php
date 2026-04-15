<?php

namespace App\Http\Controllers\Api;

use App\Models\Blog;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::query()
            ->where('status', 'published')
            ->latest()
            ->get();

        return response()->json($blogs);
    }

    public function show(Blog $blog)
    {
        if ($blog->status !== 'published') {
            abort(404);
        }

        return response()->json($blog);
    }
}
