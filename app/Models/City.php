<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    public function state()
    {
        return $this->belongsTo('App\Models\State', 'state_id', 'id');
    }
    public function pharmacy(){
        return $this->hasMany(Pharmacy::class, 'city');
    }
}
