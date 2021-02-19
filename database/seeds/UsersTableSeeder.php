<?php

use Illuminate\Database\Seeder;
use App\User; 

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'João Pedro Lopes',
            'email' => 'janesgbs@gmail.com',
            'password' => bcrypt('12345'),
        ]);
        User::create([
            'name' => 'Carlos Ferreira',
            'email' => 'calor@especializati.com',
            'password' => bcrypt('123456'),
        ]);
    }
}
