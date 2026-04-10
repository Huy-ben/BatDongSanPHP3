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
        // Create multiple posts for user 1 (seller_id = 1) to test pagination
        for ($i = 1; $i <= 15; $i++) {
            DB::table('posts')->insert(
                [
                    'title' => 'Sản phẩm ' . $i,
                    'seller_id' => 1,
                    'category_id' => 17,
                    'price' => 100000 * $i,
                    'area' => 100 + ($i * 10),
                    'address' => '123 Đường ABC ' . $i . ', Quận ' . $i,
                    'status' => $i % 3 === 0 ? '0' : '1',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
