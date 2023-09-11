<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'bill_number'=>'inv001',
            'logo'=>$this->faker->imageUrl(),
            'date_of_create'=>$this->faker->date(),
            'due_date'=>$this->faker->date(),
           // 'discount'=>$this->faker->numberBetween(10, 50),
           // 'rate_vat'=>$this->faker->numberBetween(5, 10),
           // 'value_vat'=>$this->faker->randomNumber(2),
           // 'total'=>$this->faker->randomNumber(4),
           // 'status'=>$this->faker->creditCardDetails(),
        ];
    }
}
