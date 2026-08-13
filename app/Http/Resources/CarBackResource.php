<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarBackResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'reservation_id' => $this->reservation_id,
            'returnKm'       => $this->returnKm,
            'fluelLevel'     => $this->fluelLevel,
            'state'          => $this->state,
            'domage'         => $this->domage,
            'comment'        => $this->comment,
            'returnDate' => $this->returnDate,
            'created_at'     => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'     => $this->updated_at?->format('Y-m-d H:i:s'),

            'reservation'    => new ReservationResource($this->whenLoaded('reservation')),
        ];
    }
}
