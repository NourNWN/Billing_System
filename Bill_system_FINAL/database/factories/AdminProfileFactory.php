<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AdminProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'business_name'=>$this->faker->company(),
            'phone_number'=>$this->faker->phoneNumber(),
            'city'=>$this->faker->country(),
            'region'=>$this->faker->city(),
            'logo'=>$this->faker->imageUrl(),
        ];
    }
}
