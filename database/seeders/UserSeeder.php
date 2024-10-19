<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
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
        // Create a default user
        User::create([
            'name' => 'AautoBody',
            'email' => 'aeautobody.repair@gmail.com',
            'username' => 'johndoe',
            'password' => Hash::make('autobody1234'), // Hashing the password
            'sexe' => 'M',
            'phone' => '0535607454',
            'adresse' => 'N 322, Lot Ennamae QI, Bensouda FES',
            'is_active' => true,
            'role_id' => 1 // Assuming a role_id of 1
        ]);

        // Add more users as needed
    }
}
