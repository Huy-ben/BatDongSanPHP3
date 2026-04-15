<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $categories = DB::table('categories')
            ->whereNotNull('parent_id')
            ->get();

        // ===== LOCATION DATA =====
        $locations = [
            'Hà Nội' => [
                'Cầu Giấy - Trần Duy Hưng',
                'Đống Đa - Xã Đàn',
                'Thanh Xuân - Nguyễn Trãi',
                'Hoàng Mai - Giải Phóng'
            ],
            'TP.HCM' => [
                'Quận 1 - Nguyễn Huệ',
                'Quận 7 - Phú Mỹ Hưng',
                'Bình Thạnh - Điện Biên Phủ',
                'Thủ Đức - Xa lộ Hà Nội'
            ],
            'Đà Nẵng' => [
                'Hải Châu - Nguyễn Văn Linh',
                'Sơn Trà - Võ Nguyên Giáp'
            ]
        ];

        foreach ($categories as $category) {

            for ($i = 1; $i <= 12; $i++) {

                // ===== LOCATION =====
                $city = array_rand($locations);
                $street = $locations[$city][array_rand($locations[$city])];

                $address = $city;
                $location = $street . ', ' . $city;

                // ===== TITLE =====
                $title = $this->generateTitle($category->category_name, $city);

                // ===== PRICE =====
                $isRent = str_contains(mb_strtolower($category->category_name), 'thuê');

                $price = $isRent
                    ? rand(3000000, 20000000)
                    : rand(500000000, 5000000000);

                // ===== AREA =====
                $area = rand(30, 200);

                // ===== DESCRIPTION =====
                $description = $this->generateDescription(
                    $title,
                    $area,
                    $price,
                    $location
                );

                // ===== INSERT =====
                DB::table('posts')->insert([
                    'title' => $title,
                    'seller_id' => rand(1, 2),
                    'category_id' => $category->id,
                    'slug' => Str::slug($title . '-' . uniqid()),
                    'price' => $price,
                    'area' => $area,
                    'location' => $location,
                    'address' => $address,
                    'description' => $description,
                    'status' => "1",
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    // ===== TITLE =====
    private function generateTitle($type, $city)
    {
        $templates = [
            "Cần bán :type tại :city, giá tốt, sổ riêng",
            ":type view đẹp tại :city, full nội thất",
            "Chính chủ :type tại :city, dọn vào ở ngay",
            ":type giá rẻ tại :city, vị trí đẹp",
            "Bán gấp :type tại :city, cơ hội đầu tư",
            ":type cao cấp tại :city, tiện ích đầy đủ",
        ];

        $template = $templates[array_rand($templates)];

        return str_replace(
            [':type', ':city'],
            [mb_strtolower($type), $city],
            $template
        );
    }

    // ===== DESCRIPTION =====
    private function generateDescription($title, $area, $price, $location)
    {
        return "
{$title}

- Diện tích: {$area} m²
- Giá: " . number_format($price) . " VNĐ
- Vị trí: {$location}

Mô tả chi tiết:
Bất động sản nằm ở vị trí đẹp, giao thông thuận tiện, gần trung tâm.
Khu dân cư an ninh, phù hợp để ở hoặc đầu tư lâu dài.

Tiện ích xung quanh:
- Gần chợ, siêu thị
- Gần trường học, bệnh viện
- Khu dân cư đông đúc

Liên hệ ngay để xem nhà trực tiếp.
";
    }
}