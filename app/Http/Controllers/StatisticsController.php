<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Reservation;
use App\Models\Driver;
use App\Models\Invoice;
use App\Models\User;
use App\Http\Resources\StatisticsResource;
use App\Models\Payment;
use App\Models\Panne;

class StatisticsController extends Controller
{
    public function index()
    {
        $data = (object) [
            'totalCars' => Car::query()->count(),
            'totalDrivers' => Driver::query()->count(),
            'totalReservations' => Reservation::query()->count(),
            'totalClients' => User::query()->where('active', true)->where('role', 'client')->count(),
            'totalAdmins' => User::query()->where('active', true)->where('role', 'admin')->count(),
            'activeReservations' => Reservation::query()
                ->where('status', 'En cours')
                ->count(),
            'availableDrivers' => Driver::query()->where('active', true)->where('status', 'Disponible')->count(),
            'unAvailableDrivers' => Driver::query()->where('active', true)->where('status', 'Indisponible')->count(),
            'busyDrivers' => Driver::query()->where('active', true)->where('status', 'Affecté')->count(),
            'monthlyRevenue' => Payment::query()->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount'),

            'carsAvailable' => Car::query()->where('active', true)->where('status', 'Disponible')->count(),
            'carsUnAvailable' => Car::query()->where('active', true)->where('status', 'Indisponible')->count(),
            'carsRented' => Car::query()->where('active', true)->where('status', 'Louée')->count(),
            'carsBroken' => Car::query()->where('active', true)->where('status', 'En panne')->count(),
            'carsInRepair' => Car::whereHas('pannes', fn($q) => $q->where('status', 'En réparation'))->count(),


            'waitingForDriver' => Reservation::query()->whereIn('status', ['En attente', 'Validée'])
                ->whereNull('driver_id')
                ->where('driverAmount', '>', 0)
                ->count(),
            // coût total de maintenance du mois en cours
            'maintenanceCostMonthly' => Panne::query()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('panneAmount'),

            'reservationActivity' => Reservation::query()
                ->selectRaw('DAYNAME(created_at) as day, COUNT(*) as count')
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->where('status', '!=', 'Annulée') //exclure les annulées
                ->groupBy('day')
                ->orderBy('day')
                ->get(),


        ];

        return new StatisticsResource($data);
    }

    public function topCars()
    {
        $vehicles = Reservation::query()
            ->join('invoices', 'reservations.id', '=', 'invoices.reservation_id')
            ->selectRaw('reservations.car_id,
                     COUNT(reservations.id) as reservations_count,
                     SUM(CASE WHEN reservations.status = "Terminée" THEN 1 ELSE 0 END) as completed_count,
                     SUM(CASE WHEN reservations.status = "Terminée" THEN invoices.totalAmount ELSE 0 END) as revenue')
            ->groupBy('reservations.car_id')
            ->orderByDesc('reservations_count')
            ->take(5)
            ->get();

        $vehicles = $vehicles->map(function ($v) {
            $car = Car::with('category')->find($v->car_id);

            return [
                'id'                 => $car->id,
                'photo_url'       => $car->photo ? asset('storage/' . $car->photo) : null,
                'mark'               => $car->mark,
                'model'              => $car->model,
                'imatriculation'              => $car->imatriculation,
                'category'           => $car->category->name ?? null,
                'reservations'       => $v->reservations_count,
                'locationsTerminees' => $v->completed_count,
                'revenus'            => $v->revenue,
            ];
        });

        return response()->json($vehicles);
    }
}
