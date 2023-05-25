<?php

namespace Database\Factories;

use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentType>
 */
class PaymentTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'school_id' => session('school_id') ?? 1,
            'name' => fake()->randomElement(['manual', 'cash', 'qris', 'bca', 'bni']),
            'wallet_id' => Wallet::factory()
        ];
    }
}
