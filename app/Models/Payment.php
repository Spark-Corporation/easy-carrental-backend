<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['id','invoice_id','modePayment','amount'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
