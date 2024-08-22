<?php

namespace Database\Factories;

use App\Models\Contractor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractorFactory extends Factory
{
    protected $model = Contractor::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'specialty' => $this->faker->word,
            'availability' => $this->faker->randomElement(['full-time', 'part-time', 'freelance']),
        ];
    }
}
