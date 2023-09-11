<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CostumerProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_name'=>$this->faker->userName(),
            'first_name'=>$this->faker->firstName(),
            'last_name'=>$this->faker->lastName(),
            'phone_number'=>$this->faker->phoneNumber(),
            'active'=>$this->faker->boolean(),
        ];
    }
}
