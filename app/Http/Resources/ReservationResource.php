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
            'date_start' => $this->date_start,
            'date_back'  => $this->date_back,
            'dayAmount'     => $this->dayAmount,
            'status'     => $this->status,

            'client' => new UserResource($this->whenLoaded('client')),
            'car' => new CarResource($this->whenLoaded('car')),
            
            'created_at' => $this->created_at->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at->format('d/m/Y H:i'),
        ];
    }
}
