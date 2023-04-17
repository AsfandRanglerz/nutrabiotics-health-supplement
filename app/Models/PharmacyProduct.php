<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyProduct extends Model
{
    use HasFactory;
    protected $fillable = [
        'stock',
        'pharmacy_id',
        'product_id'
    ];
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id','id');
    }
    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class,'pharmacy_id');
    }


}
