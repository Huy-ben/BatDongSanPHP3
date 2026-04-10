<?php

namespace Database\Seeders;

use App\Models\ListingPackage;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ListingPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User 1 - Active VIP package
        ListingPackage::factory()
            ->vip()
            ->for(\App\Models\User::find(1), 'user')
            ->create();

        // User 2 - Expired Trial package (to show "no active package" state)
        ListingPackage::factory()
            ->expired()
            ->state([
                'user_id' => 2,
                'package_name' => 'Trial',
                'package_type' => '0',
                'price' => 0,
                'description' => 'Gói dùng thử hết hạn',
            ])
            ->create();

        // User 2 - Active SVIP package (bonus)
        ListingPackage::factory()
            ->svip()
            ->state([
                'user_id' => 2,
            ])
            ->create();
    }
}
