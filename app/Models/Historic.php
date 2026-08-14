<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Historic extends Model
{
    protected $fillable = ['id','user_id','action','actionDate','description'];

    protected $casts = [
        'actionDate' => 'datetime',
    ];

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
