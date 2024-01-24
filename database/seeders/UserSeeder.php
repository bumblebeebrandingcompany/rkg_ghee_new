<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => "RKG SuperAdmin",
            'email' => 'superadmin@app.com',
            'password' => Hash::make('123456'),
            'role' => 'super_admin',
            'phone_no1' => '12345678',
            'reference_id' => '1001'
        ]);
    }
}
