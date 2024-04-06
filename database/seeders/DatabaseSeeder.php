<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::create(['name' => 'superadmin']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'client']);

        User::create([
            'name' => 'Abdellatif El Ghali',
            'email' => 'superadmin@superadmin.com',
            'password' => bcrypt('superadmin'),
            'role_id' => 1
        ]);

        User::create([
            'name' => 'Rania El Haddad',
            'email' => 'admin@admin.com',
            'password' => bcrypt('adminadmin'),
            'role_id' => 2
        ]);

        User::create([
            'name' => 'Hicham El Naimi',
            'email' => 'client@client.com',
            'password' => bcrypt('clientclient'),
            'role_id' => 3
        ]);

        Course::create([
            'title' => 'Introduction to Programming',
            'description' => 'Learn the basics of programming using Python',
            'teacher_name' => 'John Smith'
        ]);

        Course::create([
            'title' => 'Web Development',
            'description' => 'Build dynamic web applications using Laravel',
            'teacher_name' => 'Jane Doe'
        ]);

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

    }
}
