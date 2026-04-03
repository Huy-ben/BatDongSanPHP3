<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('posts')->insert(
            [
                'title' => 'Sản phẩm 5',
                'seller_id' => 1,
                'category_id' => 17,
                'price' => 100000,
                'area' => 100,
                'address' => '123 Đường ABC, Quận XYZ',
                'status' => 1,
            ]
        );
    }
}
