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
            ['id' => 3, 'name' => 'USER']
        ];

        // Ajouter les rôles dans la table s'ils n'existent pas
        foreach ($roles as $roleData) {
            Role::firstOrCreate(['id' => $roleData['id']], [
                'name' => $roleData['name']
            ]);
        }

        // Create a default user
        User::create([
            'name' => 'Achraf Zaim',
            'email' => 'achraf.zaime@gmail.com',
            'username' => 'Zaim_Achraf',
            'password' => Hash::make('achraf1234'), 
            'phone' => '0625456869',
            // 'adresse' => 'N 322, California Street, Casablanca',
            'is_active' => true,
            'role_id' => 1 // Assigning the SUPER_ADMIN role
        ]);

        // Add more users as needed
    }
}
