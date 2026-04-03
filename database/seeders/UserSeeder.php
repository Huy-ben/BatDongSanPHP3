<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert(
            [
            [
                'name' => 'Phạm Huy Bền',
                'email' => 'benpham06@gmail.com',
                'password' => Hash::make('password'),
                'phone_number' => '0123456789',
                'role' => '1',
            ],
            [
                'name' => 'Nguyễn Văn A',
                'email' => 'nguyenvana@example.com',
                'password' => Hash::make('password'),
                'phone_number' => '0987654321',
                'role' => '0',
            ]
            ]
        );
    }
}
