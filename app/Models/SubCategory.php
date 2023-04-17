<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;
    protected $fillable = ['name','category_id','image'];
    public function product(){
        return $this->hasMany(Product::class, 'subcategory_id');
    }

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function getImageAttribute($path)
    {
        if ($path){
            return asset($path);
        }else{
            return null;
        }
    }
    
}
