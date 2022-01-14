<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use TestCase;
use App\Models\User;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\AccountType;

class TransactionTest extends TestCase
{

    public function test_get_all_transactions()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)    
                    ->json('GET', '/api/transactions',  []);

        $response ->assertResponseStatus(200);

    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_transaction()
    {
        $user = User::factory()->create();
        $sender_account = Account::first();
        $receiver_account = Account::latest()->first();


        $this->actingAs($user)
             ->json('POST', '/api/transactions',  ['sender_account'=> $sender_account->account_number,
             'receiver_account'=> $receiver_account->account_number ,
             'pin'=>'01995',
             'amount'=> 50000.00]);
    
        $this->seeJson([
                    'message' => 'Transaction effected successfully',
                ])
             ->assertResponseStatus(201);
             

    }


    public function test_get_transaction()
    {
        $user = User::factory()->create();
        $transaction = Transaction::first();

        $response = $this->actingAs($user)    
                    ->json('GET', '/api/transactions/'.$transaction->id,  []);

        $response ->assertResponseStatus(200);

    }
    
}