<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'lastname'    => 'Nougnankey',
            'firstname'   => 'Faure',
            'email'       => 'faure@example.com',
            'password'    => Hash::make('password123'),
            'phone'       => '900000001',
            'pieceType'    => 'CNI',
            'pieceNumber'   => '123456789',
            'address'     => 'Sagbado',
            'role'        => 'admin',
            'active'      => true,
        ]);

        User::create([
            'lastname'    => 'Doe',
            'firstname'   => 'John',
            'email'       => 'john@example.com',
            'password'    => Hash::make('password123'),
            'phone'       => '900000002',
            'pieceType'    => 'CNI',
            'pieceNumber'   => '123456789',
            'address'     => 'Lomé',
            'role'        => 'client',
            'active'      => true,
        ]);

        User::create([
            'lastname'    => 'Smith',
            'firstname'   => 'Anna',
            'email'       => 'anna@example.com',
            'password'    => Hash::make('password123'),
            'phone'       => '900000003',
            'pieceType'    => 'CNI',
            'pieceNumber'   => '987654321',
            'address'     => 'Kara',
            'role'        => 'client',
            'active'      => true,
        ]);

        User::create([
            'lastname'    => 'Koffi',
            'firstname'   => 'Michel',
            'email'       => 'michel@example.com',
            'password'    => Hash::make('password123'),
            'phone'       => '900000004',
            'pieceType'    => 'CNI',
            'pieceNumber'   => '123456789',
            'address'     => 'Atakpamé',
            'role'        => 'client',
            'active'      => false,
        ]);

        User::create([
            'lastname'    => 'Akou',
            'firstname'   => 'Sarah',
            'email'       => 'sarah@example.com',
            'password'    => Hash::make('password123'),
            'phone'       => '900000005',
            'pieceType'    => 'CNI',
            'pieceNumber'   => '987654321',
            'address'     => 'Tsévié',
            'role'        => 'client',
            'active'      => true,
        ]);
    }
}
