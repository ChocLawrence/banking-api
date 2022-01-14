<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\AccountType;

class AccountTypeTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_get_account_types()
    {
        $this->get('/api/accounttypes')
             ->seeStatusCode(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_account_type()
    {
        $user = User::factory()->create();

        $name = Str::random(10);

        $this->actingAs($user)
             ->json('POST', '/api/accounttypes',  ['name'=> $name,'minimum_amount'=>'10000.00','maximum_amount'=>'99999999.99']);
    
        $this->seeJson([
                    'message' => 'Account Type created successfully',
                ])
             ->assertResponseStatus(201);
             

    }

    public function test_delete_account_type()
    {
        $user = User::factory()->create();
        $accountType = AccountType::first();

        $response = $this->actingAs($user)    
                    ->json('DELETE', '/api/accounttypes/'.$accountType->id,  []);

        $response ->assertResponseStatus(200);

    }
}
