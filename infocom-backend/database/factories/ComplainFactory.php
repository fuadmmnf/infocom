<?php

namespace Database\Factories;

use App\Models\Complain;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComplainFactory extends Factory
{
    protected $model = Complain::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->word,
            'ticket_source' => $this->faker->randomElement(['customer', 'agent', 'email']),
            'complain_text' => $this->faker->paragraph,
            'complain_summary' => $this->faker->paragraph(2),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high', 'urgent']),
            'complain_time' => $this->faker->dateTime
        ];
    }
}
