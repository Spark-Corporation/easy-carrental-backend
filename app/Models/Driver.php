<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = ['id','lastname','firstname','photo','phone','status'];

    public function reservations() {
        return $this->hasMany(Reservation::class);
    }
}
