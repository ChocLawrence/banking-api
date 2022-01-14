<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use TestCase;
use App\Models\User;
use App\Models\Account;
use App\Models\AccountType;

class AccountTest extends TestCase
{

    public function test_get_all_account()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)    
                    ->json('GET', '/api/accounts',  []);

        $response ->assertResponseStatus(200);

    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_account()
    {
        $user = User::factory()->create();
        $accountType = AccountType::first();

        $this->actingAs($user)
             ->json('POST', '/api/accounts',  ['acount_number'=>'447409569','balance'=>11000.00,'account_type'=> (string) $accountType->id,'user_id'=>'1']);
    
        $this->seeJson([
                    'message' => 'Account created successfully',
                ])
             ->assertResponseStatus(201);
             

    }


    public function test_get_account()
    {
        $user = User::factory()->create();
        $account = Account::first();

        $response = $this->actingAs($user)    
                    ->json('GET', '/api/accounts/'.$account->id,  []);

        $response ->assertResponseStatus(200);

    }

    public function test_delete_account()
    {
        $user = User::factory()->create();
        $account = Account::first();
        $user->role_id = 2;
        $user->save();

        $response = $this->actingAs($user)    
                    ->json('DELETE', '/api/accounts/'.$account->id,  []);

        $response ->seeJson([
            'message' => 'Account deleted successfully',
        ])
        ->assertResponseStatus(200);

    }
    
}