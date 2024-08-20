<?php

// database/factories/PaymentFactory.php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    // Define the model that this factory is for
    protected $model = Payment::class;

    // Define the model's default state
    public function definition()
    {
        return [
            'project_id' => Project::factory(), // Link to a Project, can create one if necessary
            'amount' => $this->faker->randomFloat(2, 100, 10000), // Random amount between 100 and 10,000
            'status' => $this->faker->randomElement(['pending', 'completed']), // Random status
        ];
    }
}
