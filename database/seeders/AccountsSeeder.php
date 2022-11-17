<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(DB::table('accounts')->count() == 0){

            DB::table('accounts')->insert([
            [
                'account_number' => 1000000001,
                'balance' => 500000,
                'pin' => '$2y$10$KFrMWz7AL5b5cIuQVJzNl.AFFdsjYeE96STetKF0V5yk7uv4UScBO',
                'transfer_without_pin' => false,
                'account_type' => 1,
                'user_id' => 4,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'account_number' => 1000000011,
                'balance' => 1500000,
                'pin' => null,
                'transfer_without_pin' => true,
                'account_type' => 2,
                'user_id' => 4,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'account_number' => 1000000002,
                'balance' => 500000,
                'pin' => null,
                'transfer_without_pin' => true,
                'account_type' => 1,
                'user_id' => 5,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'account_number' => 1000000022,
                'balance' => 1500000,
                'pin' => null,
                'transfer_without_pin' => true,
                'account_type' => 2,
                'user_id' => 5,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'account_number' => 1000000003,
                'balance' => 500000,
                'pin' => null,
                'transfer_without_pin' => true,
                'account_type' => 1,
                'user_id' => 4,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'account_number' => 1000000033,
                'balance' => 1500000,
                'pin' => null,
                'transfer_without_pin' => true,
                'account_type' => 2,
                'user_id' => 4,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'account_number' => 1000000004,
                'balance' => 500000,
                'pin' => null,
                'transfer_without_pin' => true,
                'account_type' => 1,
                'user_id' => 5,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'account_number' => 1000000044,
                'balance' => 1500000,
                'pin' => null,
                'transfer_without_pin' => true,
                'account_type' => 2,
                'user_id' => 5,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
            
        }
    }
}
