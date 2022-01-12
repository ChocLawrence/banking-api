<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\AccountType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AccountFactory extends Factory
{
    protected $model = Account::class;
    $accountTypes = AccountType::all()->pluck('id')->toArray();

    public function definition(): array
    {
    	return [
    	    'account_number' => Str::random(10),
            'balance' => $this->faker->numberBetween($min=10001.00, $max=1000000000.00),
            'type' => $this->faker->randomElement($this->accountTypes)
    	];
    }
}
