<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvoiceDetail>
 */
class InvoiceDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'item_name' => fake()->name(),
            'price' => fake()->randomNumber(5, true),
            'invoice_id' => Invoice::factory(),
            'wallet_id' => Wallet::factory()
        ];
    }
}
