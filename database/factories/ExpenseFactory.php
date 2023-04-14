<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Grade;
use App\Models\School;
use App\Models\TuitionType;
use App\Models\AcademicYear;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tuition>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'note' => fake()->word(),
        ];
    }
}
