<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $imageLinks = [
            'https://res.cloudinary.com/djbobb5oe/image/upload/q_auto/f_auto/v1775789971/chungcu_dpxj1v.jpg',
            'https://res.cloudinary.com/djbobb5oe/image/upload/q_auto/f_auto/v1775789970/nharieng_yd0ftv.webp',
            'https://res.cloudinary.com/djbobb5oe/image/upload/q_auto/f_auto/v1775789971/bietthu_vbod7p.png',
            'https://res.cloudinary.com/djbobb5oe/image/upload/q_auto/f_auto/v1775789971/datnen_whbit5.png',
            'https://res.cloudinary.com/djbobb5oe/image/upload/q_auto/f_auto/v1775789971/shophouse_blxxwj.jpg',
            'https://res.cloudinary.com/djbobb5oe/image/upload/q_auto/f_auto/v1775789970/condotel_tuaewc.jpg',
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('images')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $posts = DB::table('posts')->select('id')->orderBy('id')->get();

        foreach ($posts as $index => $post) {
            // Seed 4 images per post: first is thumbnail, remaining are gallery images.
            for ($offset = 0; $offset < 4; $offset++) {
                $imageUrl = $imageLinks[($index + $offset) % count($imageLinks)];

                DB::table('images')->insert([
                    'product_id' => $post->id,
                    'image_url' => $imageUrl,
                    'is_thumbnail' => $offset === 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
