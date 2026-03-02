<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

class RentalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = Faker::create();
        return [
            'start_date' => $faker->date(),
            'end_date' => $faker->date(),
            'total_price' => $faker->randomFloat(2,0,10)
        ];
    }
}
