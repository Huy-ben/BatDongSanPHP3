<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->updateOrInsert([
            'id' => 17,
        ], [
            'category_name' => 'Nhà đất bán',
            'description' => 'Nhà đất bán nè',
        ]);
    }
}
