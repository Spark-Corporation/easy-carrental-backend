<?php

namespace App\Models;

use App\Http\Resources\InvoiceResource;
use Illuminate\Database\Eloquent\Model;
use App\Models\Setting;

/**
 * App\Models\Reservation
 * @mixin \Illuminate\Database\Eloquent\Builder<\App\Models\Reservation>
 * @method static \Illuminate\Database\Eloquent\Builder query()
 * @method static \Illuminate\Database\Eloquent\Builder selectRaw(string $expression, array $bindings = [])
 * @method static \Illuminate\Database\Eloquent\Builder whereBetween(string $column, array $values)
 * @method static \Illuminate\Database\Eloquent\Builder groupBy(string|array $columns)
 * @method static \Illuminate\Database\Eloquent\Builder orderBy(string $column, string $direction = 'asc')
 * @method static int count()
 */

class Reservation extends Model
{
    protected $fillable = ['id','reservationNumber', 'user_id', 'car_id', 'driver_id', 'dateStart', 'dateBack', 'driverAmount', 'type', 'status'];

    public function calculateDriverAmount(float $driverDailyRate): float
    {
        $days = 0;

        if ($this->dateStart && $this->dateBack) {
            $startDate = \Carbon\Carbon::parse($this->dateStart);
            $endDate   = \Carbon\Carbon::parse($this->dateBack);

            if ($endDate->greaterThanOrEqualTo($startDate)) {
                $days = $startDate->diffInDays($endDate);
                $days = round($days);
            }
        }

        // Calcul seulement si réservation avec chauffeur
        if ($this->type === 'leasing') {
            return $days * $driverDailyRate;
        }

        return 0;
    }

    public function getComputedStatusAttribute()
    {
        // Statuts fixés par action humaine
        if (in_array($this->status, ['En attente', 'Refusée', 'Annulée'])) {
            return $this->status;
        }

        // Si validée → calculer en fonction des dates
        if ($this->status === 'Validée') {
            $start = \Carbon\Carbon::parse($this->dateStart);
            $end   = \Carbon\Carbon::parse($this->dateBack);
            $now   = now();

            if ($start->gt($now)) {
                return 'A venir';
            } elseif ($start->lte($now) && $end->gte($now)) {
                return 'En cours';
            } elseif ($end->lt($now)) {
                return 'Terminée';
            }
        }

        // Par défaut, on renvoie le statut métier
        return $this->status;
    }


    protected static function boot()
    {
        parent::boot();

        static::saving(function ($reservation) {
            if (empty($reservation->reservationNumber)) {
                $lastId = Reservation::max('id') + 1;
                $reservation->reservationNumber = 'RES-' . str_pad($lastId, 10, '0', STR_PAD_LEFT);
            }

            $driverDailyRate = Setting::get('driverDailyRate', 0); // tarif global fixé par l’entreprise

            if (empty($reservation->status)) {
                $reservation->status = 'En attente'; // valeur par défaut
            }
            $reservation->driverAmount = $reservation->calculateDriverAmount($driverDailyRate);
        });
    }

    public function isActive()
    {
        $start = \Carbon\Carbon::parse($this->dateStart);
        $end   = \Carbon\Carbon::parse($this->dateBack);
        $now   = now();

        return $this->status === 'Validée' && $start->lte($now) && $end->gte($now);
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function carBack()
    {
        return $this->hasOne(CarBack::class);
    }
}
