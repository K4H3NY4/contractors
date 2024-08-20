<?php

// database/factories/ProjectFactory.php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    // Define the model that this factory is for
    protected $model = Project::class;

    // Define the model's default state
    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),  // Create a user if needed
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => 'active',  // or 'completed', 'pending', etc.
            'total_cost' => $this->faker->randomFloat(2, 1000, 10000),
        ];
    }
}
