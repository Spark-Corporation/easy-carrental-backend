<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->reservation->user->id,
            'reservation_id' => $this->reservation_id,
            'invoiceNumber' => $this->invoiceNumber,
            'driverAmount' => $this->driverAmount,
            'reductionAmount' => $this->reductionAmount,
            'tvaAmount' => $this->tvaAmount,
            'amount' => $this->amount,
            'totalAmount' => $this->totalAmount,
            'status' => $this->status,
            'reservation' => new ReservationResource($this->whenLoaded('reservation')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
