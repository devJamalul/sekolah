<?php

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'school_id' => session('school_id') ?? 2,
            'invoice_number' => str()->random(10),
            'invoice_date' => now()->format('Y-m-d'),
            'due_date' => now()->addMonth()->format('Y-m-d'),
            'note' => fake()->sentence(),
            'payment_status' => Invoice::STATUS_PENDING,
            'is_posted' => Invoice::POSTED_DRAFT,
            'is_original' => true
        ];
    }
}
