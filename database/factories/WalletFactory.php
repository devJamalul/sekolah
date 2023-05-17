<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Grade;
use App\Models\School;
use App\Models\TuitionType;
use App\Models\AcademicYear;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Wallet>
 */
class WalletFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $randomNumber = rand(1, 2000);

        return [
            'school_id' => session('school_id') ?? 2,
            'name' => fake()->word(),
            'init_value' => $randomNumber
        ];
    }
}
