<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use TestCase;
use App\Models\User;
use App\Models\Account;
use App\Models\AccountType;

class AuthenticationTest extends TestCase
{

    public function test_register()
    {
        $payload = [
            'firstname' => 'Lee',
            'lastname' => 'Jones',
            'phone' => '+237635363435',
            'gender' => 'male',
            'email' => 'elangolawrences@gmail.com', 
            'password' => '123456',
            'confirm_password' => '123456'];
        $response = $this->json('post','/api/register',$payload);

        $response->assertResponseStatus(200);
    }

    public function test_login()
    {
        $payload = ['email' => 'elangolawrence@gmail.com', 'password' => '123456'];
        $response = $this->json('post','/api/login',$payload);

        $response->assertResponseStatus(200);
    }
}