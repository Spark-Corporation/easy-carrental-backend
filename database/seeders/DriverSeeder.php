<?php

namespace Database\Seeders;

use App\Models\Driver;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Driver::create([
            'lastname'   => 'Nougnankey',
            'firstname'  => 'Faure',
            'photo'      => 'faure.jpg',
            'phone'      => '910000001',
            'status'     => 'affecté',
        ]);

        Driver::create([
            'lastname'   => 'Doe',
            'firstname'  => 'John',
            'photo'      => 'john.jpg',
            'phone'      => '910000002',
            'status'     => 'affecté',
        ]);

        Driver::create([
            'lastname'   => 'Smith',
            'firstname'  => 'Anna',
            'photo'      => 'anna.jpg',
            'phone'      => '910000003',
            'status'     => 'indisponible',
        ]);

        Driver::create([
            'lastname'   => 'Koffi',
            'firstname'  => 'Michel',
            'photo'      => 'michel.jpg',
            'phone'      => '910000004',
            'status'     => 'disponible',
        ]);

        Driver::create([
            'lastname'   => 'Akou',
            'firstname'  => 'Sarah',
            'photo'      => 'sarah.jpg',
            'phone'      => '910000005',
            'status'     => 'indisponible',
        ]);
    }
}
