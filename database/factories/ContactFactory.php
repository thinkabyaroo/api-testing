<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'=>$this->faker->realText(10),
            'phone'=>$this->faker->realText(300),
            'photo'=>'default.png',
            'user_id'=>rand(1,5)
        ];
    }
}
