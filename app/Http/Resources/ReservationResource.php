<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Setting;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
   public function toArray(Request $request): array
    {
        $days = null;
        $carAmount = null;
        $reductionAmount = null;
        $tvaAmount = null;

        if ($this->dateStart && $this->dateBack && $this->car) {
            $startDate = \Carbon\Carbon::parse($this->dateStart);
            $endDate   = \Carbon\Carbon::parse($this->dateBack);
            $days      = $startDate->diffInDays($endDate);

            $dayAmount = $this->car->dayAmount ?? 0;
            $baseAmount = $days * $dayAmount;

            $reductionRate   = Setting::get('reductionRate', 0);
            $tvaRate         = Setting::get('tvaRate', 0);
            $driverDailyRate = Setting::get('driverDailyRate', 0);

            $reductionAmount = $baseAmount * $reductionRate;
            $driverAmount    = $this->type === 'leasing' ? $driverDailyRate * $days : 0;

            $netPrice   = $baseAmount - $reductionAmount + $driverAmount;
            $tvaAmount  = $netPrice * $tvaRate;
            $totalAmount = $netPrice + $tvaAmount;
        }
        return [
            'id'         => $this->id,
            'reservationNumber' => $this->reservationNumber,
            'dateStart' => $this->dateStart,
            'dateBack'  => $this->dateBack,
            'driverAmount'  => $this->driverAmount,
            'type'       => $this->type,
            'status'     => $this->status,
            'computed_status' => $this->computed_status,

            'user' => new UserResource($this->whenLoaded('user')),

            'car' => new CarResource($this->whenLoaded('car')),
            'driver' => new DriverResource($this->whenLoaded('driver')),
            'invoice'        => new InvoiceResource($this->whenLoaded('invoice')),

            'totalAmount' => $this->invoice?->totalAmount,

            // Champs calculés (estimate)
            'days'            => $days,
            // 'carAmount'       => $carAmount,
            // 'reductionAmount' => $reductionAmount,
            // 'tvaAmount'       => $tvaAmount,

            'created_at' => $this->created_at->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at->format('d/m/Y H:i'),
        ];
    }
}
