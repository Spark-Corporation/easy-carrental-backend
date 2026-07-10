<?php

namespace App\Models;

use App\Http\Resources\InvoiceResource;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = ['id','user_id','car_id','driver_id','dateStart','dateBack','dayAmount','driverAmount','type','status'];

    public function User() {
        return $this->belongsTo(User::class);
    }

    public function car() {
        return $this->belongsTo(Car::class);
    }
    public function driver() {
        return $this->belongsTo(Driver::class);
    }
    public function invoice() {
        return $this->hasOne(Invoice::class);
    }

}
