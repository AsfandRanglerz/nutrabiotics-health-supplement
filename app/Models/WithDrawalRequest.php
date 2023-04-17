<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithDrawalRequest extends Model
{
    use HasFactory;
    protected $fillable = ['status'];

    // public function pharmacy()
    // {
    //     return $this->belongsTo(Pharmacy::class,'pharmacy_id');
    // }

    public function pharmacy()
    {
        return $this->belongsTo('App\Models\Pharmacy', 'pharmacy_id', 'id');
    }
}
