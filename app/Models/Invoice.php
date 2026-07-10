<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['id','reservation_id','invoiceNumber','driverAmount','reductionAmount','tvaAmount','amount','totalAmount','status'];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($invoice) {
            if (empty($invoice->invoiceNumber)) {
                $lastId = Invoice::max('id') + 1;
                $invoice->invoiceNumber = 'FAC-' . str_pad($lastId, 10, '0', STR_PAD_LEFT);
            }

            $days = 0;
            $dayAmount = 0;

            if ($invoice->reservation) {
                $startDate = $invoice->reservation->dateStart ? \Carbon\Carbon::parse($invoice->reservation->dateStart) : null;
                $endDate = $invoice->reservation->dateBack ? \Carbon\Carbon::parse($invoice->reservation->dateBack) : null;

                if ($startDate && $endDate && $endDate->greaterThanOrEqualTo($startDate)) {
                    $days = $startDate->diffInDays($endDate);
                }

                $dayAmount = (float) ($invoice->reservation->dayAmount ?? 0);
            }

            $baseAmount = max(0, $days * $dayAmount);
            $reductionAmount = max(0, (float) ($invoice->reductionAmount ?? 0));
            $driverAmount = max(0, (float) ($invoice->driverAmount ?? 0));

            $netPrice = max(0, $baseAmount - $reductionAmount + $driverAmount);
            $tvaAmount = max(0, $netPrice * 0.18);
            $totalAmount = max(0, $netPrice + $tvaAmount);

            $invoice->amount = round($baseAmount, 2);
            $invoice->tvaAmount = round($tvaAmount, 2);
            $invoice->totalAmount = round($totalAmount, 2);
        });
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
