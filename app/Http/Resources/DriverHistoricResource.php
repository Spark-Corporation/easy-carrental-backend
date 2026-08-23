<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverHistoricResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'dateStart'      => $this->dateStart,
            'dateBack'      => $this->dateBack,
            'imatriculation'       => $this->car ? $this->car->imatriculation : null,
            'mark'       => $this->car ? $this->car->mark: null,
            'model'       => $this->car ? $this->car->model : null,
            'client' => $this->user ? $this->user->lastname . ' ' . $this->user->firstname : null,
            'startKm'         => $this->startKm,
           'returnKm'        => $this->carBack?->returnKm ?? null,
            'distance'        => ($this->carBack?->returnKm && $this->car?->kilometrage)
                                 ? $this->carBack->returnKm - $this->car->kilometrage
                                 : null,
            'status'    => $this->status,
        ];
    }
}
