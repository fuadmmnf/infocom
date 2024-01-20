<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'services' => $this->faker->words(4),
            'customer_id' => $this->faker->unique()->word,
            'address' => $this->faker->streetAddress . ',' . $this->faker->streetName . ',' . $this->faker->city . ',' . $this->faker->country,
            'installation_date' => $this->faker->dateTime,
            'client_type' => $this->faker->randomElement(['Home', 'Corporate']),
//            'connectivity_type' => $this->faker->randomElement(['Dedicated', 'Shared']),
//            'bandwidth_allocation' => $this->faker->numberBetween(1, 20),
//            'allocated_ip' => $this->faker->ipv4,
//            'selling_price_bdt_excluding_vat' => $this->faker->numberBetween(1000, 100000),
        ];
    }
}
