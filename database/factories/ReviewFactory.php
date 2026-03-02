<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

class ReviewFactory extends Factory
{
    public function definition(): array
    {
        $faker = Faker::create();

        return [
            'comment' => $faker->text(),
            'rating' => $faker->numberBetween(1,5) //Je ne sais pas, j'ai deviné ce que serait un "range" de rating (1 à 5 étoiles? peut-être?)
        ];
    }
}