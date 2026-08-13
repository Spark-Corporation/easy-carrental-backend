<?php

namespace App\Models;
use App\Models\Reservation;

use Illuminate\Database\Eloquent\Model;

class carBack extends Model
{
    protected $fillable = [ 'id','returnKm','fluelLevel','state','domage','comment',  'returnDate','reservation_id'];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
