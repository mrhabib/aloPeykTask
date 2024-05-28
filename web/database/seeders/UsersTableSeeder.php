<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'habib saleh',
            'email' => 'habib@saleh.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123456'),
            'phone' => '09114229210',
            'remember_token' => '6655658656',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
