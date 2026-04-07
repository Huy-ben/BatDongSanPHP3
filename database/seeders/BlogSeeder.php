<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Blog;
class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $blogs = [
            [
                'user_id' => 1,
                'title' => 'Bài viết 1',
                'slug' => 'bai-viet-1',
                'category_id' => 17,
                'content' => 'Nội dung bài viết 1',
                'image' => 'blog1.jpg',
                'excerpt' => 'Trích dẫn bài viết 1',
                'status' => 'draft',
            ],
            [
                'user_id' => 2,
                'title' => 'Bài viết 2',
                'slug' => 'bai-viet-2',
                'category_id' => 17,
                'content' => 'Nội dung bài viết 2',
                'image' => 'blog2.jpg',
                'excerpt' => 'Trích dẫn bài viết 2',
                'status' => 'published',
            ],
        ];

        foreach ($blogs as $blog) {
            Blog::create($blog);
        }
    }
}
