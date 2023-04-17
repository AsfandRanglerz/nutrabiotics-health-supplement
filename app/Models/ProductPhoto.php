<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPhoto extends Model
{
    use HasFactory;
    protected $fillable=['photo','product_id'];
    public function product()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }
    public function getPhotoAttribute($path)
    {
        if ($path){
            return asset($path);
        }else{
            return null;
        }
    }

    protected $hidden = ['created_at','updated_at','id','product_id'];

}
