<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Panne extends Model
{
    protected $fillable = ['id','name','description','panneAmont'];
}
