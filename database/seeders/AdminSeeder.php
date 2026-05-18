<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin PLN',
            'email' => 'admin@pln-iconplus.com',
            'password' => bcrypt('admin123'),
        ]);
        $admin->assignRole('admin');

        $manager = User::create([
            'name' => 'Manager PLN',
            'email' => 'manager@pln-iconplus.com',
            'password' => bcrypt('manager123'),
        ]);
        $manager->assignRole('manager');

        $teknisi = User::create([
            'name' => 'Teknisi 1',
            'email' => 'teknisi@pln-iconplus.com',
            'password' => bcrypt('teknisi123'),
        ]);
        $teknisi->assignRole('teknisi');
    }
}