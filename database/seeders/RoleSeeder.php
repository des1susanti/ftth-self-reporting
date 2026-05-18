<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['name' => 'pelanggan']);
        Role::create(['name' => 'teknisi']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'manager']);
    }
}