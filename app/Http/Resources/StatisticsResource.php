<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatisticsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'totals' => [
                'cars'         => $this->totalCars,
                'drivers' => $this->totalDrivers,
                'reservations' => $this->totalReservations,
                'clients' => $this->totalClients,
                'admins' => $this->totalAdmins,

                'activeReservations' => $this->activeReservations,

                'availableDrivers'      => $this->availableDrivers,
                'unAvailableDrivers' => $this->unAvailableDrivers,
                'busyDrivers'        => $this->busyDrivers,
                'monthlyRevenue'      => $this->monthlyRevenue,

                'availableCars'   => $this->carsAvailable,
                'unAvailableCars' => $this->carsUnAvailable,
                'rentedCars'      => $this->carsRented,
                'brokenCars'      => $this->carsBroken,
                'carsInRepair' => $this->carsInRepair,
                'waitingForDriver' => $this->waitingForDriver,
                'maintenanceCostMonthly' => $this->maintenanceCostMonthly,
            ],
            'reservationActivity' => $this->reservationActivity,
        ];
    }
}
