<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['name' => 'admin', 'guard_name' => 'api'],
            ['name' => 'pimpinan', 'guard_name' => 'api'],
            ['name' => 'HR', 'guard_name' => 'api'],
            ['name' => 'user', 'guard_name' => 'api'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name'], 'guard_name' => $role['guard_name']], $role);
        }
    }
}