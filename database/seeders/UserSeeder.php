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
        for ($i=0; $i < 10; $i++) { 
            DB::table('users')->insert([
                'name' => 'Usuario' . $i ,
                'email' => 'usuario' . $i . '@gmail.com',
                'password' => Hash::make('secret'),
                'level' =>  $i % 5,
            ]);
        }
    }
}
