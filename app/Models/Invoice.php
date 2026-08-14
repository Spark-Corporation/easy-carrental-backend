<?php

namespace App\Models;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;
/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin \Illuminate\Database\Query\Builder
 * @property int $id
 * @property int $reservation_id
 * @property string $invoiceNumber
 * @property numeric $driverAmount
 * @property numeric $reductionAmount
 * @property numeric $tvaAmount
 * @property numeric $amount
 * @property numeric $totalAmount
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payment
 * @property-read int|null $payment_count
 * @property-read \App\Models\Reservation $reservation
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereDriverAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereInvoiceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereReductionAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereReservationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereTvaAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereMoth($value)
 * /**
 * App\Models\Invoice
 *
 * @method static int sum(string $column)
 * @method static \Illuminate\Database\Eloquent\Builder whereMonth(string $column, int $value)
 * @method static \Illuminate\Database\Eloquent\Builder whereYear(string $column, int $value)
 * @method static \Illuminate\Database\Eloquent\Builder query()
 */


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
            $tvaRate = Setting::get('tvaRate', 0);
            $reductionRate = setting::get('reductionRate',0);

            if ($invoice->reservation) {
                $startDate = $invoice->reservation->dateStart ? \Carbon\Carbon::parse($invoice->reservation->dateStart) : null;
                $endDate = $invoice->reservation->dateBack ? \Carbon\Carbon::parse($invoice->reservation->dateBack) : null;

                if ($startDate && $endDate && $endDate->greaterThanOrEqualTo($startDate)) {
                    $days = $startDate->diffInDays($endDate);
                    $days = round($days);
                }

                $dayAmount = (float) ($invoice->reservation->car->dayAmount ?? 0);
            }

            $baseAmount = max(0, $days * $dayAmount);
            $reductionAmount = max(0, (float) ($baseAmount * $reductionRate ?? 0));
            $driverAmount = max(0, (float) ($invoice->reservation->driverAmount ?? 0));

            $netPrice = max(0, $baseAmount - $reductionAmount + $driverAmount);
            $tvaAmount = max(0, $netPrice * $tvaRate);
            $totalAmount = max(0, $netPrice + $tvaAmount);

            $invoice->reductionAmount = round($reductionAmount, 2);
            $invoice->amount = round($baseAmount, 2);
            $invoice->tvaAmount = round($tvaAmount, 2);
            $invoice->totalAmount = round($totalAmount, 2);
        });
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
    public function payment()
    {
        return $this->hasMany(Payment::class);
    }
}
