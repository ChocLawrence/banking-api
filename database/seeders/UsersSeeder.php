<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        if(DB::table('users')->count() == 0){

            DB::table('users')->insert([
            [
                'role_id' => 1,
                'firstname' => 'Kerick',
                'lastname' => 'Fru',
                'middlename' => null,
                'username' => 'frurick',
                'middlename' => null,
                'gender' => 'male',
                'image' => null,
                'address' => null,
                'email' => 'fru@gmail.com',
                'bio' => null,
                'phone' => null,
                'password' => Hash::make('pass123'),
                'email_verified_at' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'role_id' => 2,
                'firstname' => 'Joseph',
                'lastname' => 'Marnick',
                'middlename' => null,
                'username' => 'marnjoe',
                'middlename' => null,
                'gender' => 'male',
                'image' => null,
                'address' => null,
                'email' => 'marnjoe@gmail.com',
                'bio' => null,
                'phone' => null,
                'password' => Hash::make('pass123'),
                'email_verified_at' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'role_id' => 2,
                'firstname' => 'Mary',
                'lastname' => 'Lesther',
                'middlename' => null,
                'username' => 'maryles',
                'middlename' => null,
                'gender' => 'female',
                'image' => null,
                'address' => null,
                'email' => 'maryles@gmail.com',
                'bio' => null,
                'phone' => null,
                'password' => Hash::make('pass123'),
                'email_verified_at' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'role_id' => 3,
                'firstname' => 'Paul',
                'lastname' => 'Mboe',
                'middlename' => null,
                'username' => 'paulmax',
                'middlename' => null,
                'gender' => 'male',
                'image' => null,
                'address' => null,
                'email' => 'paulmax@gmail.com',
                'bio' => null,
                'phone' => null,
                'password' => Hash::make('pass123'),
                'email_verified_at' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'role_id' => 3,
                'firstname' => 'Therese',
                'lastname' => 'Claire',
                'middlename' => null,
                'username' => 'thessy',
                'middlename' => null,
                'gender' => 'female',
                'image' => null,
                'address' => null,
                'email' => 'thessy@gmail.com',
                'bio' => null,
                'phone' => null,
                'password' => Hash::make('pass123'),
                'email_verified_at' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]

        ]);
            
        }
    }
}
