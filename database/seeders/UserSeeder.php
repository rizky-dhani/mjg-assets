<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superadmin = User::create([
            'userId' => Str::orderedUuid(),
            'name' => 'Super Admin',
            'email' => 'superadmin@medquest.co.id',
            'password' => Hash::make('Superadmin2025!'),
        ]);
        $superadmin->assignRole('Super Admin');

        $manager = User::create([
            'userId' => Str::orderedUuid(),
            'name' => 'Muh Ardyansyah',
            'email' => 'muh.ardyansyah@medquest.co.id',
            'password' => Hash::make('Medquest.1'),
        ]);
        $manager->assignRole('Admin');

        $supervisor = User::create([
            'userId' => Str::orderedUuid(),
            'name' => 'Febry Ulfa Rizaldo',
            'email' => 'febry.rizaldo@medquest.co.id',
            'password' => Hash::make('Medquest.1'),
        ]);
        $supervisor->assignRole('Admin');
        
        $system = User::create([
            'userId' => Str::orderedUuid(),
            'name' => 'Rizky Dhani Ismail',
            'email' => 'rizky.dhani@medquest.co.id',
            'password' => Hash::make('Medquest.1'),
        ]);
        $system->assignRole('Admin');
        
        $support1 = User::create([
            'userId' => Str::orderedUuid(),
            'name' => 'Yoga Saputra',
            'email' => 'yoga.saputra@medquest.co.id',
            'password' => Hash::make('Medquest.1'),
        ]);
        $support1->assignRole('Admin');
        
        $support2 = User::create([
            'userId' => Str::orderedUuid(),
            'name' => 'Pantas Hutapea',
            'email' => 'pantas.hutapea@medquest.co.id',
            'password' => Hash::make('Medquest.1'),
        ]);
        $support2->assignRole('Admin');
    }
}
