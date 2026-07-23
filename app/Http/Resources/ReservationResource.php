<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
   public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'dateStart' => $this->dateStart,
            'dateBack'  => $this->dateBack,
            'driverAmount'  => $this->driverAmount,
            'type'       => $this->type,
            'status'     => $this->status,

            'user' => new UserResource($this->whenLoaded('user')),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'car' => new CarResource($this->whenLoaded('car')),
            'driver' => new DriverResource($this->whenLoaded('driver')),

            'amount' => $this->invoice?->amount,

            'created_at' => $this->created_at->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at->format('d/m/Y H:i'),
        ];
    }
}
