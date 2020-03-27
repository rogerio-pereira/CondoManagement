<?php

use App\Model\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Amin
        factory(User::class)->create([
            'name' => 'Rogerio Pereira',
            'email' => 'rogerio@test.com',
            'password' => bcrypt('123456'),
            'role' => 'Admin'
        ]);
        factory(User::class)->create([
            'name' => 'Maintenance',
            'email' => 'maintenance@test.com',
            'password' => bcrypt('123456'),
            'role' => 'Maintenance'
        ]);
        factory(User::class)->create([
            'name' => 'Tenant',
            'email' => 'tenant@test.com',
            'password' => bcrypt('123456'),
            'role' => 'Tenant'
        ]);

        //Admin
        factory(User::class, 3)->create([
            'role' => 'Admin'
        ]);

        //Maintenance
        factory(User::class, 3)->create([
            'role' => 'Maintenance'
        ]);

        //Tenants
        factory(User::class, 5)->create();
    }
}
