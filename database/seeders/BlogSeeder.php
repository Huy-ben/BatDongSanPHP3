<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // lấy category "Bài viết"
        $postRoot = DB::table('categories')
            ->where('category_name', 'Bài viết')
            ->first();

        if (!$postRoot) return;

        // lấy tất cả category con của "Bài viết"
        $categories = DB::table('categories')
            ->where('parent_id', $postRoot->id)
            ->get();

        // nếu chưa có category con thì dùng luôn root
        if ($categories->isEmpty()) {
            $categories = collect([$postRoot]);
        }

        $titles = [
            'Kinh nghiệm mua nhà lần đầu',
            'Những lưu ý khi đầu tư bất động sản',
            'Xu hướng thị trường bất động sản 2025',
            'Có nên mua chung cư hay không?',
            'Top khu vực đáng đầu tư hiện nay',
            'Cách định giá bất động sản chính xác',
            'Thị trường nhà đất đang tăng hay giảm?',
            'Những sai lầm khi mua nhà',
            'Kinh nghiệm thuê nhà giá rẻ',
            'Đầu tư đất nền có an toàn không?',
        ];

        $images = [
            'https://res.cloudinary.com/djbobb5oe/image/upload/q_auto/f_auto/v1775789971/chungcu_dpxj1v.jpg',
            'https://res.cloudinary.com/djbobb5oe/image/upload/q_auto/f_auto/v1775789970/nharieng_yd0ftv.webp',
            'https://res.cloudinary.com/djbobb5oe/image/upload/q_auto/f_auto/v1775789971/bietthu_vbod7p.png',
        ];

        foreach ($titles as $title) {

            $category = $categories->random();

            $content = $this->generateContent($title);

            DB::table('blogs')->insert([
                'user_id' => rand(1, 2),
                'title' => $title,
                'category_id' => $category->id,
                'slug' => Str::slug($title . '-' . uniqid()),
                'content' => $content,
                'image' => $images[array_rand($images)],
                'excerpt' => Str::limit(strip_tags($content), 150),
                'status' => 'published',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    private function generateContent($title)
    {
        return "
            <h2>{$title}</h2>

            <p>Bất động sản luôn là một trong những lĩnh vực đầu tư hấp dẫn hiện nay. Tuy nhiên, để đạt được hiệu quả cao, người mua cần trang bị đầy đủ kiến thức và kinh nghiệm.</p>

            <p>Trong bài viết này, chúng tôi sẽ chia sẻ những thông tin hữu ích giúp bạn hiểu rõ hơn về thị trường cũng như cách đưa ra quyết định chính xác.</p>

            <h3>1. Nghiên cứu thị trường</h3>
            <p>Trước khi đầu tư, bạn cần tìm hiểu kỹ khu vực, giá cả và tiềm năng phát triển.</p>

            <h3>2. Xác định nhu cầu</h3>
            <p>Bạn mua để ở hay đầu tư? Điều này ảnh hưởng rất lớn đến quyết định.</p>

            <h3>3. Kiểm tra pháp lý</h3>
            <p>Đảm bảo bất động sản có đầy đủ giấy tờ hợp pháp.</p>
            
            <h3>Kết luận</h3>
            <p>Hy vọng bài viết giúp bạn có thêm kinh nghiệm trong việc đầu tư bất động sản.</p>
            ";
    }
}