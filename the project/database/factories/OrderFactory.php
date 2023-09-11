<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product'=>$this->faker->title(),
            'quantity'=>$this->faker->numberBetween(1, 50),
            'price_one'=>$this->faker->randomNumber(2),
        ];
    }
}
