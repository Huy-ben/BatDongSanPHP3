<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ListingPackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomElement([0, 179000, 359000]),
            'expiry_date' => $this->faker->dateTimeBetween('-30 days', '+90 days'),
            'package_type' => $this->faker->randomElement(['0', '1', '2']),
            'package_name' => $this->faker->randomElement(['Trial', 'VIP', 'SVIP']),
            'status' => '1',
            'is_featured' => $this->faker->boolean(),
        ];
    }

    public function trial(): static
    {
        return $this->state(fn (array $attributes) => [
            'package_name' => 'Trial',
            'package_type' => '0',
            'price' => 0,
            'description' => 'Gói dùng thử 1 tháng',
            'expiry_date' => Carbon::now()->addMonth(),
            'status' => '1',
        ]);
    }

    public function vip(): static
    {
        return $this->state(fn (array $attributes) => [
            'package_name' => 'VIP',
            'package_type' => '1',
            'price' => 179000,
            'description' => 'Gói VIP 3 tháng',
            'expiry_date' => Carbon::now()->addMonths(3),
            'status' => '1',
        ]);
    }

    public function svip(): static
    {
        return $this->state(fn (array $attributes) => [
            'package_name' => 'SVIP',
            'package_type' => '2',
            'price' => 359000,
            'description' => 'Gói SVIP 3 tháng',
            'expiry_date' => Carbon::now()->addMonths(3),
            'status' => '1',
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expiry_date' => Carbon::now()->subDays(5),
            'status' => '0',
        ]);
    }
}
