<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;
    public function country()
    {
        return $this->belongsTo('App\Models\Country', 'country_id', 'id');
    }
    public function city(){
        return $this->hasMany(City::class, 'state_id');
    }

    public function pharmacy(){
        return $this->hasMany(Pharmacy::class, 'state');
    }
}
