<?php

namespace Database\Factories;

use App\Models\AccountType;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountTypeFactory extends Factory
{
    protected $model = AccountType::class;

    public function definition(): array
    {
    	return [
    	    'name' => $this->faker->unique()->randomElement(['current' ,'saving','deposit','salary']),
            'minimum_amount' => '10000.00',
            'maximum_amount' => '1000000000',
    	];
    }
}
