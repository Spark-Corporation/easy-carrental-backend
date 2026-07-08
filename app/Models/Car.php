<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = ['id','category_id','mark','type','model','color','photo','imatriculation','description','prix_km','state','place','door','kilometrage','niveauCarburant','domage'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
