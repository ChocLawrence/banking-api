<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(DB::table('accounttypes')->count() == 0){

            DB::table('accounttypes')->insert([
            [
                'name' => 'fixed-deposit',
                'minimum_amount' => 0,
                'maximum_amount' => 50000000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'recurring-deposit',
                'minimum_amount' => 0,
                'maximum_amount' => 150000000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'savings',
                'minimum_amount' => 10000,
                'maximum_amount' => 100000000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'salary',
                'minimum_amount' => 0,
                'maximum_amount' => 200000000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'current',
                'minimum_amount' => 0,
                'maximum_amount' => 100000000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'nri',
                'minimum_amount' => 0,
                'maximum_amount' => 300000000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ]);
            
        }
    }
}
