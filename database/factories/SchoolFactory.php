<?php

namespace Database\Factories;

use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\School>
 */
class SchoolFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'school_name' => fake()->name(),
            'province' => fake()->country(),
            'city' => fake()->city(),
            'postal_code' => fake()->postcode(),
            'address' => fake()->address(),
            'grade' => fake()->randomElement(School::GRADE_SCHOOL),
            'email' => fake()->email(),
            'phone' => '12345678910',
            'foundation_head_name' => fake()->name(),
            'foundation_head_tlpn' => "12345678910",
        ];
    }
}
