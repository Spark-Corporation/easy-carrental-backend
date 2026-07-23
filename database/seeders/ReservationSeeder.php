<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reservation;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Reservation::create([
            'user_id'        => 1,
            'car_id'         => 1,
            'dateStart'     => '2026-07-01',
            'dateBack'      => '2026-07-05',
            'driverAmount'  => 15000.00,
            'type'          => 'leasing',
            'status'        => 'en attente',
        ]);

        Reservation::create([
            'user_id'        => 2,
            'car_id'         => 2,
            'dateStart'     => '2026-07-10',
            'dateBack'      => '2026-07-15',
            'driverAmount'  => 25000.00,
            'type'          => 'reservation',
            'status'        => 'en attente',
        ]);

        Reservation::create([
            'user_id'        => 3,
            'car_id'         => 3,
            'dateStart'     => '2026-07-20',
            'dateBack'      => '2026-07-22',
            'driverAmount'  => 8000.00,
            'type'          => 'reservation',
            'status'        => 'annulée',
        ]);

        Reservation::create([
            'user_id'        => 4,
            'car_id'         => 4,
            'dateStart'     => '2026-07-25',
            'dateBack'      => '2026-07-30',
            'driverAmount'  => 18000.00,
            'type'          => 'reservation',
            'status'        => 'en cours',
        ]);

        Reservation::create([
            'user_id' => 5 ,
            'car_id'  => 5,
            'dateStart'     => '2026-08-01',
            'dateBack'      => '2026-08-03',
            'driverAmount'  => 12000.00,
            'type'          => 'leasing',
            'status'        => 'terminée',
        ]);
    }
}
