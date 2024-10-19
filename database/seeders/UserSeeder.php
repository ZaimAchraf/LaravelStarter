<?php

namespace Database\Seeders;

use App\Models\Role;
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
        $roles = [
            ['id' => 1, 'name' => 'SUPER_ADMIN'],
            ['id' => 2, 'name' => 'MANAGER'],
            ['id' => 3, 'name' => 'USER'],
            ['id' => 4, 'name' => 'EMPLOYEE'],
            ['id' => 5, 'name' => 'PROVIDER']
        ];

        // Ajouter les rôles dans la table s'ils n'existent pas
        foreach ($roles as $roleData) {
            Role::firstOrCreate(['id' => $roleData['id']], [
                'name' => $roleData['name']
            ]);
        }

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
