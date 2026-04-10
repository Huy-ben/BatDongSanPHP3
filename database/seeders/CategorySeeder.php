<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ===== LEVEL 1 =====
        $postRootId = DB::table('categories')->insertGetId([
            'category_name' => 'Bài viết',
            'description' => 'Tin tức, blog bất động sản',
            'parent_id' => null,
            'image' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $listingRootId = DB::table('categories')->insertGetId([
            'category_name' => 'Tin đăng',
            'description' => 'Danh sách tin đăng bất động sản',
            'parent_id' => null,
            'image' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // ===== LEVEL 2 =====
        $banId = DB::table('categories')->insertGetId([
            'category_name' => 'Bán',
            'description' => 'Bất động sản bán',
            'parent_id' => $listingRootId,
            'image' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $thueId = DB::table('categories')->insertGetId([
            'category_name' => 'Cho thuê',
            'description' => 'Bất động sản cho thuê',
            'parent_id' => $listingRootId,
            'image' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // ===== LEVEL 3 =====
        $types = [
            [
                'name' => 'Căn hộ chung cư',
                'image' => 'https://res.cloudinary.com/djbobb5oe/image/upload/q_auto/f_auto/v1775789971/chungcu_dpxj1v.jpg'
            ],
            [
                'name' => 'Nhà riêng',
                'image' => 'https://res.cloudinary.com/djbobb5oe/image/upload/q_auto/f_auto/v1775789970/nharieng_yd0ftv.webp'
            ],
            [
                'name' => 'Biệt thự',
                'image' => 'https://res.cloudinary.com/djbobb5oe/image/upload/q_auto/f_auto/v1775789971/bietthu_vbod7p.png'
            ],
            [
                'name' => 'Đất nền',
                'image' => 'https://res.cloudinary.com/djbobb5oe/image/upload/q_auto/f_auto/v1775789971/datnen_whbit5.png'
            ],
            [
                'name' => 'Shophouse',
                'image' => 'https://res.cloudinary.com/djbobb5oe/image/upload/q_auto/f_auto/v1775789971/shophouse_blxxwj.jpg'
            ],
            [
                'name' => 'Condotel',
                'image' => 'https://res.cloudinary.com/djbobb5oe/image/upload/q_auto/f_auto/v1775789970/condotel_tuaewc.jpg'
            ],
        ];

        // ===== BÁN =====
        foreach ($types as $item) {
            DB::table('categories')->insert([
                'category_name' => $item['name'],
                'description' => 'Bán ' . $item['name'],
                'parent_id' => $banId,
                'image' => $item['image'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // ===== THUÊ =====
        foreach ($types as $item) {
            DB::table('categories')->insert([
                'category_name' => 'Cho thuê ' . $item['name'],
                'description' => 'Cho thuê ' . $item['name'],
                'parent_id' => $thueId,
                'image' => $item['image'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}